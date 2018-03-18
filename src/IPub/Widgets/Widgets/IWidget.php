<?php
/**
 * IWidget.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Widgets
 * @since          1.0.0
 *
 * @date           15.09.14
 */

declare(strict_types = 1);

namespace IPub\Widgets\Widgets;

/**
 * Widgets control interface
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Widgets
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IWidget
{
	/**
	 * Get current widget name
	 *
	 * @return string
	 */
	function getName();

	/**
	 * Get current widget description
	 *
	 * @return string
	 */
	function getDescription() : string;

	/**
	 * Get current widget priority
	 *
	 * @return int
	 */
	function getPriority() : int;

	/**
	 * Get current widget status
	 *
	 * @return bool
	 */
	function getStatus() : bool;

	/**
	 * Get current widget position name
	 *
	 * @return string
	 */
	function getPosition() : string;
}
