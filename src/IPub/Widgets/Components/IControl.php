<?php
/**
 * IControl.php
 *
 * @copyright      Vice v copyright.php
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Components
 * @since          1.0.0
 *
 * @date           24.07.13
 */

declare(strict_types = 1);

namespace IPub\Widgets\Components;

/**
 * Widgets container control factory definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Components
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IControl
{
	/**
	 * @param string $position
	 *
	 * @return Control
	 */
	function create(string $position = 'default') : Control;
}
