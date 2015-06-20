<?php
/**
 * WidgetsRepository.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Repository
 * @since		5.0
 *
 * @date		12.06.15
 */

namespace IPub\Widgets\Repository;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Widgets\Entities;

use IPub\Packages;

/**
 * Widgets repository finder definition
 *
 * @package		iPublikuj:Widgets!
 * @subpackage	Repository
 *
 * @method Entities\IWidget[] getPackages
 * @method Entities\IWidget|null findPackage($name, $version = 'latest')
 * @method Entities\IWidget[] findPackages($name, $version = 'latest')
 */
class WidgetsRepository extends Packages\Repository\InstalledRepository
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Packages\Loaders\ILoader
	 */
	protected $loader;

	/**
	 * @param string $path
	 * @param Packages\Loaders\ILoader $loader
	 */
	public function __construct($path, Packages\Loaders\ILoader $loader)
	{
		parent::__construct($path);

		$this->loader = $loader;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function initialize()
	{
		parent::initialize();

		if (empty($this->packages)) {
			foreach (Utils\Finder::findFiles("composer.json")->from($this->path) as $config) {
				$this->addPackage($this->loader->load($config));
			}
		}
	}
}
