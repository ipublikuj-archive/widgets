<?php
/**
 * AccessFilter.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:WidgetsManager!
 * @subpackage	Filters
 * @since		5.0
 *
 * @date		16.09.14
 */

namespace IPub\WidgetsManager\Filters;

use Nette;
use Nette\Application\UI;

class AccessFilter extends FilterIterator
{
	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		return TRUE;
	}
}