<?php
/**
 * Test: IPub\Widgets\Extension
 * @testCase
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Tests
 * @since		5.0
 *
 * @date		20.06.15
 */

namespace IPubTests\Widgets;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\Widgets;

require __DIR__ . '/../bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Widgets\DI\WidgetsExtension::register($config);

		$config->addConfig(__DIR__ . '/files/config.neon', $config::NONE);

		// Define config variables
		$config->addParameters([
			'appDir' => __DIR__,
		]);

		return $config->createContainer();
	}

	public function testCompilersServices()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('widgets.widgets.manager') instanceof IPub\Widgets\WidgetsManager);
		Assert::true($dic->getService('widgets.widgets.component') instanceof IPub\Widgets\Components\IControl);
		Assert::true($dic->getService('widgets.decorators.manager') instanceof IPub\Widgets\DecoratorsManager);
		Assert::true($dic->getService('widgets.decorator.raw') instanceof IPub\Widgets\Decorators\Raw\IControl);
	}
}

\run(new ExtensionTest());