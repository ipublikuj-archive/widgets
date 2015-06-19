<?php
/**
 * IWidget.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Widgets
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\Widgets\Widgets;

use IPub;
use IPub\Widgets;
use IPub\Widgets\Entities;

interface IControl
{
	/**
	 * Set widget display data
	 *
	 * @param Entities\IData $data
	 *
	 * @return $this
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
}