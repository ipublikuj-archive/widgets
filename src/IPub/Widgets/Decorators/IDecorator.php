<?php
/**
 * IDecorator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 * @since          1.0.0
 *
 * @date           16.09.14
 */

namespace IPub\Widgets\Decorators;

use IPub;
use IPub\Widgets;

/**
 * Widgets decorator interface definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDecorator
{
	/**
	 * Render widget with decorator
	 *
	 * @param Widgets\Widgets\IControl $widget
	 */
	public function render(Widgets\Widgets\IControl $widget);
}
