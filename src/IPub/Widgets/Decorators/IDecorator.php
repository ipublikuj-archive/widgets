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

declare(strict_types = 1);

namespace IPub\Widgets\Decorators;

use IPub;
use IPub\Widgets;

/**
 * Widgets decorator interface definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IDecorator
{
	/**
	 * Render widget with decorator
	 *
	 * @param Widgets\Widgets\IWidget $widget
	 */
	public function render(Widgets\Widgets\IWidget $widget);
}
