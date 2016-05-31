<?php
/**
 * IFactory.php
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

namespace IPub\Widgets\Decorators;

/**
 * Widgets decorator factory definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Decorators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IFactory
{
	/**
	 * @return Decorator
	 */
	public function create();
}
