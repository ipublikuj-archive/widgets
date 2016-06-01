<?php
/**
 * IFactory.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Widgets
 * @since          1.0.0
 *
 * @date           17.09.14
 */

namespace IPub\Widgets\Widgets;

use IPub;
use IPub\Widgets\Entities;

/**
 * Widgets control factory definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Widgets
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IFactory
{
	/**
	 * @param Entities\IData $data
	 *
	 * @return NULL
	 */
	public function create(Entities\IData $data);
}
