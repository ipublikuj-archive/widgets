<?php
/**
 * TWidgets.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           18.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets;

use Nette;
use Nette\Application;

use IPub;
use IPub\Widgets\Components;

trait TWidgets
{
	/**
	 * @var Components\IControl
	 */
	protected $widgetsFactory;

	/**
	 * @param Components\IControl $widgetsFactory
	 */
	public function injectWidgets(Components\IControl $widgetsFactory)
	{
		$this->widgetsFactory = $widgetsFactory;
	}

	/**
	 * Widgets component
	 *
	 * @return Application\UI\Multiplier
	 */
	public function createComponentWidgets()
	{
		return new Application\UI\Multiplier(function ($position) {
			return $this->widgetsFactory->create($position);
		});
	}
}
