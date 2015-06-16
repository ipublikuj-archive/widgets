<?php
/**
 * WidgetLoader.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Loaders
 * @since		5.0
 *
 * @date		12.06.15
 */

namespace IPub\Widgets\Loaders;

use IPub;
use IPub\Widgets\Exceptions;

use IPub\Packages;

class WidgetLoader extends Packages\Loaders\JsonLoader
{
	const CLASSNAME = __CLASS__;

	/**
	 * {@inheritdoc}
	 */
	public function load($json, $class = 'IPub\Widgets\Entities\Widget')
	{
		return parent::load($json, $class);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function loadConfig(array $config, $class)
	{
		$package = parent::loadConfig($config, $class);

		if ($package->getType() != 'widget') {
			throw new Exceptions\UnexpectedValueException('Package "'.$config['name'].'" has no type "widget" defined.');
		}

		return $package;
	}
}
