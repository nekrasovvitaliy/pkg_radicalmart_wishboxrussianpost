<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrsov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later;
 */

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Extension\WishboxRussianPost;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Provider\RadicalMartShippingCalculator as RadicalMartShippingCalculatorProvider;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Provider\ShippingNotification as ShippingNotificationProvider;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Provider\WishboxRussianPostMVCFactory as WishboxRussianPostMVCFactoryProvider;
use WishboxRussianPostLibrary\Extension\Service\Provider\RequestFactory;
use WishboxRussianPostLibrary\Service\Provider\Calculator;
use WishboxRussianPostLibrary\Service\Provider\RussianPostClientProvider;

defined('_JEXEC') or die;

return new class implements ServiceProviderInterface {

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return void
	 *
	 * @since        1.0.0
	 */
	public function register(Container $container): void
	{
		$container->registerServiceProvider(new RequestFactory);
		$container->registerServiceProvider(new RussianPostClientProvider);
		$container->registerServiceProvider(new Calculator);
		$container->registerServiceProvider(new WishboxRussianPostMVCFactoryProvider);
		$container->registerServiceProvider(new RadicalMartShippingCalculatorProvider);
		$container->registerServiceProvider(new ShippingNotificationProvider);

		$container->set(
			PluginInterface::class,
			static function (Container $container): PluginInterface {
				$dispatcher = $container->get(DispatcherInterface::class);
				$config     = (array) PluginHelper::getPlugin('radicalmart_shipping', 'wishboxrussianpost');

				$plugin = new WishboxRussianPost($dispatcher, $config);
				$plugin->setApplication(Factory::getApplication());
				$plugin->setContainer($container);
				$plugin->setDatabase($container->get(DatabaseInterface::class));

				return $plugin;
			}
		);
	}
};
