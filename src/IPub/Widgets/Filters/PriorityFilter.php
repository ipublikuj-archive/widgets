<?php
/**
 * PriorityFilter.php
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

class PriorityFilter extends FilterIterator
{
	const CLASSNAME = __CLASS__;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		$elements = iterator_to_array($iterator, FALSE);

		$iterator->uasort(function($a, $b) use ($elements) {

			$priorityA = (int) $a->getPriority();
			$priorityB = (int) $b->getPriority();

			if ($priorityA == $priorityB) {
				$priorityA = array_search($a, $elements);
				$priorityB = array_search($b, $elements);
			}

			return ($priorityA < $priorityB) ? -1 : 1;
		});

		parent::__construct($iterator, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		return TRUE;
	}
}