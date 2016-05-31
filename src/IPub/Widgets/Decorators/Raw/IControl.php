<?php
/**
 * IControl.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 * @since          1.0.0
 *
 * @date           18.06.15
 */

namespace IPub\Widgets\Decorators\Raw;

use IPub;
use IPub\Widgets\Decorators;

/**
 * Widgets raw decorator control factory
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IControl extends Decorators\IFactory
{
	const CLASSNAME = __CLASS__;

	/**
	 * @return Control
	 */
	public function create();
}
