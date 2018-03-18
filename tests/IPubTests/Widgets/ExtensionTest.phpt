<?php
/**
 * Test: IPub\Widgets\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           20.06.15
 */

declare(strict_types = 1);

namespace IPubTests\Widgets;

use Nette;

use Tester;
use Tester\Assert;

use IPub\Widgets;
use IPub\Widgets\Components;
use IPub\Widgets\Decorators;
use IPub\Widgets\Managers;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	public function testCompilersServices() : void
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('widgets.widgets.manager') instanceof Managers\WidgetsManager);
		Assert::true($dic->getService('widgets.widgets.component') instanceof Components\IControl);
		Assert::true($dic->getService('widgets.decorators.manager') instanceof Managers\DecoratorsManager);
		Assert::true($dic->getService('widgets.decorator.raw') instanceof Decorators\Raw\IControl);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Widgets\DI\WidgetsExtension::register($config);

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'config.neon');

		// Define config variables
		$config->addParameters([
			'appDir' => __DIR__,
		]);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
