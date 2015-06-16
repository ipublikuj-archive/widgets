<?php
/**
 * AccessFilter.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Filters
 * @since		5.0
 *
 * @date		16.09.14
 */

namespace IPub\Widgets\Filters;

use Nette;
use Nette\Application\UI;

class AccessFilter extends FilterIterator
{
	const CLASSNAME = __CLASS__;

	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		return TRUE;
	}
}