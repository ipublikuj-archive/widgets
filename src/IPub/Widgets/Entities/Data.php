<?php
/**
 * Data.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Entities
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\Widgets\Entities;

use Nette;
use Nette\Utils;

class Data extends Nette\Object implements IData
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $position;

	/**
	 * @var int
	 */
	protected $priority;

	/**
	 * @var bool
	 */
	protected $status;

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @param array $data
	 */
	public function __construct(array $data = NULL)
	{
		if ($data != NULL) {
			foreach ($data as $property => $value) {
				if (property_exists($this, $property)) {
					$this->{$property} = $value;
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority()
	{
		return $this->priority;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStatus()
	{
		return $this->status ? TRUE : FALSE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStyle()
	{
		return $this->getParam('template.style', 'default');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBadge()
	{
		return $this->getParam('template.badge', NULL);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIcon()
	{
		return $this->getParam('template.icon', NULL);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParams(array $params)
	{
		$this->params = $params;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParam($key, $value = NULL)
	{
		$keys = explode('.', $key);

		if (count($keys) > 1) {
			$val = &$this->params;
			$last = array_pop($keys);

			foreach ($keys as $key) {
				if (!isset($val[$key]) || !is_array($val[$key])) {
									$val[$key] = array();
				}

				$val = &$val[$key];
			}

			$val[$last] = $value;

		} else {
			$this->params[$keys[0]] = $value;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParam($key, $default = NULL)
	{
		$keys = explode('.', $key);

		if (array_key_exists($keys[0], $this->params)) {
			if (is_array($this->params[$keys[0]]) || $this->params[$keys[0]] instanceof Utils\ArrayHash) {
				$val = NULL;

				foreach ($keys as $key) {
					if (isset($val)) {
						if (isset($val[$key])) {
							$val = $val[$key];
						} else {
							$val = NULL;
						}

					} else {
						$val = isset($this->params[$key]) ? $this->params[$key] : $default;
					}
				}

				return (isset($val) ? $val : $default);

			} else {
				return trim($this->params[$keys[0]]);
			}
		}

		return $default;
	}
}
