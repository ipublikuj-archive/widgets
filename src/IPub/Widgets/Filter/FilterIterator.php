<?php
/**
 * FilterIterator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Filter
 * @since          1.0.0
 *
 * @date           26.06.15
 */

namespace IPub\Widgets\Filter;

use Nette;
use Nette\Application;

/**
 * Widgets filter iterator
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Filter
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class FilterIterator extends \FilterIterator
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var Application\Application
	 */
	protected static $app;

	/**
	 * Gets the application.
	 *
	 * @return Application\Application
	 */
	public function getApplication()
	{
		return self::$app;
	}

	/**
	 * Sets the application.
	 *
	 * @param Application\Application $app
	 */
	public static function setApplication(Application\Application $app)
	{
		self::$app = $app;
	}

	/**
	 * Constructor
	 *
	 * @param \Iterator $iterator
	 * @param array $options
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		parent::__construct($iterator);

		$this->options = $options;
	}
}
