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
use IPub\Widgets;
use IPub\Widgets\Decorators;

class Control extends Application\UI\Control implements Decorators\IDecorator
{
	const CLASSNAME = __CLASS__;

	public function render(Widgets\Widgets\IControl $widget)
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/template/default.latte');

		$template->widget = $widget;

		$template->render();
	}
}