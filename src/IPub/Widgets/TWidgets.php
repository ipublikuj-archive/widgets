<?php
/**
 * TWidgets.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           18.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets;

use Nette\Application;

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
	public function injectWidgets(Components\IControl $widgetsFactory) : void
	{
		$this->widgetsFactory = $widgetsFactory;
	}

	/**
	 * Widgets component
	 *
	 * @return Application\UI\Multiplier
	 */
	public function createComponentWidgets() : Application\UI\Multiplier
	{
		return new Application\UI\Multiplier(function ($position) : Components\Control {
			return $this->widgetsFactory->create($position);
		});
	}
}
