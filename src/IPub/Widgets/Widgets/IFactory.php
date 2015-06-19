<?php
/**
 * IFactory.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Widgets
 * @since		5.0
 *
 * @date		17.09.14
 */

namespace IPub\Widgets\Widgets;

use IPub;
use IPub\Widgets\Entities;

interface IFactory
{
	/**
	 * @param Entities\IData $data
	 *
	 * @return null
	 */
	public function create(Entities\IData $data);
}