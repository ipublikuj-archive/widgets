<?php
/**
 * Widget.php
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

use Nette;
use Nette\Utils;

use IPub;
use IPub\Widgets;

use IPub\Packages;

class Widget extends Packages\Entities\Package implements IWidget
{
	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * {@inheritdoc}
	 */
	public function getExtensionName()
	{
		// Create extension name from package name
		return lcfirst(implode('', array_map('ucfirst', explode('-', Utils\Strings::webalize($this->name)))));
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPath($path)
	{
		// Path can not be overwritten
		if ($this->path === NULL) {
			$this->path = (string) $path;
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setConfig($value = NULL, $key = NULL)
	{
		if ($key !== NULL) {
			$keys = explode('.', $key);

			if (count($keys) > 1) {
				$val = &$this->config;
				$last = array_pop($keys);

				foreach ($keys as $key) {
					if (!isset($val[$key]) || !is_array($val[$key]))
						$val[$key] = [];

					$val = &$val[$key];
				}

				$val[$last] = $value;

			} else {
				$this->config[$keys[0]] = $value;
			}

		} else {
			$this->config = $value;
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfig($key = NULL, $default = NULL)
	{
		if (NULL === $key) {
			return $this->config;
		}

		$array = $this->config;

		if (isset($array[$key])) {
			return $array[$key];
		}

		foreach (explode('.', $key) as $segment) {

			if (!is_array($array) || !array_key_exists($segment, $array)) {
				return $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Widget's enable hook
	 */
	public function enable()
	{
	}

	/**
	 * Widget's disable hook
	 */
	public function disable()
	{
	}

	/**
	 * Widget's uninstall hook
	 */
	public function uninstall()
	{
	}
}