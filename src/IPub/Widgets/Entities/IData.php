<?php
/**
 * IData.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Entities
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\Widgets\Entities;

interface IData
{
	/**
	 * Get stored widget title
	 *
	 * @return string
	 */
	public function getTitle();

	/**
	 * Get stored widget description
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 * Get stored widget position name
	 *
	 * @return int
	 */
	public function getPosition();

	/**
	 * Get stored widget priority
	 *
	 * @return int
	 */
	public function getPriority();

	/**
	 * Get stored widget priority
	 *
	 * @return bool
	 */
	public function getStatus();

	/**
	 * Get stored widget style name
	 *
	 * @return string
	 */
	public function getStyle();

	/**
	 * Get stored widget badge name
	 *
	 * @return string
	 */
	public function getBadge();

	/**
	 * Get stored widget icon name
	 *
	 * @return string
	 */
	public function getIcon();

	/**
	 * Set stored widget params
	 *
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setParams(array $params);

	/**
	 * Get stored widget params
	 *
	 * @return array
	 */
	public function getParams();

	/**
	 * Set stored widget param
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setParam($key, $value = NULL);

	/**
	 * Get stored widget param
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed
	 */
	public function getParam($key, $default = NULL);
}