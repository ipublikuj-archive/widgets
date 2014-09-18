<?php
/**
 * IWidget.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:WidgetsManager!
 * @subpackage	Widgets
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\WidgetsManager\Widgets;

use IPub;
use IPub\WidgetsManager;
use IPub\WidgetsManager\Entities;

interface IWidget
{
	/**
	 * Define widget type
	 */
	const WIDGET_TYPE = 'widget.notDefined';

	/**
	 * Define template files
	 */
	const TEMPLATE_DEFAULT	= 'default';	// Default widget template

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

	/**
	 * Get current widget position name
	 *
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getPosition();

	/**
	 * Get current widget priority
	 *
	 * @return int
	 *
	 * @throws \LogicException
	 */
	public function getPriority();
}