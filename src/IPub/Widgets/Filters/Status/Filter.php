<?php
/**
 * Filter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets\Filters\Status;

use IPub\Widgets\Filters;

/**
 * Widgets status filter
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Filter extends Filters\FilterIterator
{
	/**
	 * @var integer
	 */
	protected $status;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		parent::__construct($iterator, $options);

		$this->status = isset($options['status']) ? $options['status'] : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function accept() : bool
	{
		if ($this->status === NULL) {
			return TRUE;
		}

		return $this->status === parent::current()->getStatus();
	}
}
