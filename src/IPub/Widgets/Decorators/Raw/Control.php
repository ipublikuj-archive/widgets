<?php
/**
 * Control.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Decorators
 * @since		5.0
 *
 * @date		18.06.15
 */

namespace IPub\Widgets\Decorators\Raw;

use Nette;
use Nette\Application;

use IPub;
use IPub\Widgets\Decorators;

/**
 * Widgets decorator control definition
 *
 * @package		iPublikuj:Widgets!
 * @subpackage	Decorators
 *
 * @property-read Application\UI\ITemplate $template
 */
class Control extends Decorators\Decorator
{
	const CLASSNAME = __CLASS__;
}