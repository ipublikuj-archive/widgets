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
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IControl
{
	/**
	 * Set widget display data
	 *
	 * @param Entities\IData $data
	 */
	public function setData(Entities\IData $data);

	/**
	 * Get current widget name
	 *
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getName();

	/**
	 * Get current widget description
	 *
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getDescription();

	/**
	 * Get current widget priority
	 *
	 * @return int
	 *
	 * @throws \LogicException
	 */
	public function getPriority();

	/**
	 * Get current widget status
	 *
	 * @return int
	 *
	 * @throws \LogicException
	 */
	public function getStatus();

	/**
	 * Get current widget position name
	 *
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getPosition();
}
