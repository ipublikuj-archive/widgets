<?php
/**
 * FilterIterator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets\Filters;

use IPub;
use IPub\Widgets\Widgets;

/**
 * Widgets filter iterator
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
abstract class FilterIterator extends \FilterIterator
{
	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @param Widgets\IWidget[]|\Iterator $iterator
	 * @param array $options
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		parent::__construct($iterator);

		$this->options = $options;
	}
}
