<?php
/**
 * IControl.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 * @since          1.0.0
 *
 * @date           18.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets\Decorators\Raw;

use IPub\Widgets\Decorators;

/**
 * Widgets raw decorator control factory
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IControl extends Decorators\IFactory
{
	/**
	 * @return Control
	 */
	public function create() : Control;
}
