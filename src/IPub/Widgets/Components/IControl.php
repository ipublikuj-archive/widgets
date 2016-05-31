<?php
/**
 * IControl.php
 *
 * @copyright      Vice v copyright.php
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Components
 * @since          1.0.0
 *
 * @date           24.07.13
 */

namespace IPub\Widgets\Components;

/**
 * Widgets container control factory definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Components
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IControl
{
	const INTERFACENAME = __CLASS__;

	/**
	 * @param string $position
	 *
	 * @return Control
	 */
	function create($position = 'default');
}
