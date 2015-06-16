<?php
/**
 * IWidget.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Entities
 * @since		5.0
 *
 * @date		12.06.15
 */

namespace IPub\Widgets\Entities;

use IPub;
use IPub\Widgets;
use IPub\Packages;

interface IWidget extends Packages\Entities\IPackage
{
	/**
	 * @return string
	 */
	public function getExtensionName();

	/**
	 * @param string $path
	 *
	 * @return $this
	 */
	public function setPath($path);

	/**
	 * Returns the widgets's absolute path
	 *
	 * @return string
	 */
	public function getPath();

	/**
	 * Set the widgets's config
	 *
	 * @param mixed|null $value
	 * @param string|null $key
	 *
	 * @return $this
	 */
	public function setConfig($value = NULL, $key = NULL);

	/**
	 * Returns the widgets's config
	 *
	 * @param  mixed $key
	 * @param  mixed $default
	 *
	 * @return array
	 */
	public function getConfig($key = NULL, $default = NULL);

	/**
	 * Widgets's enable hook
	 */
	public function enable();

	/**
	 * Widgets's disable hook
	 */
	public function disable();

	/**
	 * Widgets's uninstall hook
	 */
	public function uninstall();
}