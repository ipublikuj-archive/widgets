<?php
/**
 * Macros.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Latte
 * @since		5.0
 *
 * @date		18.06.15
 */

namespace IPub\Widgets\Latte;

use Nette;
use Nette\Utils;

use Latte;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;
use Latte\Macros\MacroSet;

use IPub;

class Macros extends MacroSet
{
	/**
	 * Register latte macros
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);

		/**
		 * {widget /}
		 */
		$me->addMacro('widget', array($me, 'macroWidget'), '}');

		return $me;
	}

	/**
	 * {control name[:method] [params]}
	 */
	public function macroWidget(MacroNode $node, PhpWriter $writer)
	{
		$words = $node->tokenizer->fetchWords();
		if (!$words) {
			throw new Latte\CompileException('Missing control name in {control}');
		}
		$name = $writer->formatWord($words[0]);
		$method = isset($words[1]) ? ucfirst($words[1]) : '';
		$method = Utils\Strings::match($method, '#^\w*\z#') ? "render$method" : "{\"render$method\"}";
		$param = $writer->formatArray();
		if (!Utils\Strings::contains($node->args, '=>')) {
			$param = substr($param, 6, -1); // removes array()
		}
		return ($name[0] === '$' ? "if (is_object($name)) \$_l->tmp = $name; else " : '')
		. '$_l->tmp = $_control->getComponent(' . $name . '); '
		. 'if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); '
		. ($node->modifiers === '' ? "\$_l->tmp->$method($param)" : $writer->write("ob_start(); \$_l->tmp->$method($param); echo %modify(ob_get_clean())"));
	}
}