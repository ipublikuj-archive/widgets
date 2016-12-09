<?php
/**
 * Macros.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Latte
 * @since          1.0.0
 *
 * @date           10.10.14
 */

declare(strict_types = 1);

namespace IPub\Widgets\Latte;

use Nette;
use Nette\Bridges;
use Nette\Utils;

use Latte;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * Permissions latte macros definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Latte
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Macros extends Bridges\ApplicationLatte\UIMacros
{
	/**
	 * @param Compiler $compiler
	 *
	 * @return void
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);

		/**
		 * {widget positionName}
		 */
		$me->addMacro('widgets', [$me, 'macroWidgets']);
	}

	/**
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 *
	 * @return string
	 * 
	 * @throws Latte\CompileException
	 */
	public static function macroWidgets(MacroNode $node, PhpWriter $writer) : string
	{
		$words = $node->tokenizer->fetchWords();
		if (!$words) {
			throw new Latte\CompileException('Missing widgets position name in {widgets}');
		}
		$name = $writer->formatWord($words[0]);
		$method = isset($words[1]) ? ucfirst($words[1]) : '';
		$method = Utils\Strings::match($method, '#^\w*\z#') ? "render$method" : "{\"render$method\"}";

		$tokens = $node->tokenizer;
		$pos = $tokens->position;
		$param = $writer->formatArray();
		$tokens->position = $pos;
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('=>') && !$tokens->depth) {
				$wrap = TRUE;
				break;
			}
		}
		if (empty($wrap)) {
			$param = substr($param, 1, -1); // removes array() or []
		}
		$prefix = '';
		if (is_string($name) && strpos($name, '-') === FALSE) {
			$prefix = '"widgets-" .';
		}
		return "/* line $node->startLine */ "
		. ($name[0] === '$' ? "if (is_object($name)) \$_tmp = $name; else " : '')
		. '$_tmp = $this->global->uiControl->getComponent('. $prefix . $name . '); '
		. 'if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE); '
		. ($node->modifiers === ''
			? "\$_tmp->$method($param);"
			: $writer->write("ob_start(function () {}); \$_tmp->$method($param); echo %modify(ob_get_clean());")
		);
	}
}
