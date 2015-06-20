<?php
/**
 * IControl.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Components
 * @since		5.0
 *
 * @date		24.07.13
 */

namespace IPub\Widgets\Components;

interface IControl
{
	const CLASSNAME = __CLASS__;

	/**
	 * @param string $position
	 *
	 * @return Control
	 */
	function create($position = 'default');
}