<?php
/**
 * IData.php
 *
 * @copyright	More in license.md
 * @license		https://www.ipublikuj.eu
 * @author		Adam Kadlec https://www.ipublikuj.eu
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
	public function getTitle() : string;

	/**
	 * Get stored widget description
	 *
	 * @return string|NULL
	 */
	public function getDescription() : ?string;

	/**
	 * Get stored widget position name
	 *
	 * @return string
	 */
	public function getPosition() : string;

	/**
	 * Get stored widget priority
	 *
	 * @return int
	 */
	public function getPriority() : int;

	/**
	 * Get stored widget priority
	 *
	 * @return bool
	 */
	public function getStatus() : bool;

	/**
	 * Get stored widget style name
	 *
	 * @return string
	 */
	public function getStyle() : string;

	/**
	 * Get stored widget badge name
	 *
	 * @return string|NULL
	 */
	public function getBadge() : ?string;

	/**
	 * Get stored widget icon name
	 *
	 * @return string|NULL
	 */
	public function getIcon() : ?string;

	/**
	 * Set stored widget params
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	public function setParams(array $params) : void;

	/**
	 * Get stored widget params
	 *
	 * @return array
	 */
	public function getParams() : array;

	/**
	 * Set stored widget param
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return void
	 */
	public function setParam(string $key, ?string $value = NULL) : void;

	/**
	 * Get stored widget param
	 *
	 * @param string $key
	 * @param string|NULL $default
	 *
	 * @return string|NULL
	 */
	public function getParam(string $key, ?string $default = NULL) : ?string;
}
