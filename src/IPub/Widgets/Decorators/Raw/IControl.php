<?php
/**
 * IControl.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Decorators
 * @since		5.0
 *
 * @date		18.06.15
 */

namespace IPub\Widgets\Decorators\Raw;

use IPub;
use IPub\Widgets\Decorators;

interface IControl extends Decorators\IFactory
{
	const CLASSNAME = __CLASS__;

	/**
	 * Define decorator name
	 */
	const DECORATOR_NAME = 'raw';

	/**
	 * @return Control
	 */
	public function create();
}