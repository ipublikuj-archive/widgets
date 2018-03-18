<?php
/**
 * IFilter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           08.12.16
 */

declare(strict_types = 1);

namespace IPub\Widgets\Filters\Priority;

use IPub\Widgets\Filters;

/**
 * Widgets priority filter factory
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IFilter extends Filters\IFactory
{
	/**
	 * @param \Iterator $iterator
	 * @param array $options
	 *
	 * @return Filter
	 */
	public function create(\Iterator $iterator, array $options = []) : Filter;
}
