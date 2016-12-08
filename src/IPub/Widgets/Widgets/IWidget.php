<?php
/**
 * IWidget.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Widgets
 * @since          1.0.0
 *
 * @date           15.09.14
 */

declare(strict_types = 1);

namespace IPub\Widgets\Widgets;

use IPub;
use IPub\Widgets;
use IPub\Widgets\Entities;

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
	function getDescription();

	/**
	 * Get current widget priority
	 *
	 * @return int
	 */
	function getPriority();

	/**
	 * Get current widget status
	 *
	 * @return int
	 */
	function getStatus();

	/**
	 * Get current widget position name
	 *
	 * @return string
	 */
	function getPosition();
}
