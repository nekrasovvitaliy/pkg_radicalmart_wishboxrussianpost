<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMart\WishboxRussianPostOrderRegistration\Extension\WishboxRussianPostOrderRegistration;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\Provider\RadicalMartOrderRegistration as RadicalMartOrderRegistrationProvider;

defined('_JEXEC') or die;

return new class implements ServiceProviderInterface {

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(Container $container): void
	{
		$container->registerServiceProvider(new RadicalMartOrderRegistrationProvider);

		$container->set(
			PluginInterface::class,
			static function (Container $container): PluginInterface {
				$dispatcher = $container->get(DispatcherInterface::class);
				$config     = (array) PluginHelper::getPlugin('radicalmart', 'wishboxrussianpostorderregistration');

				$plugin = new WishboxRussianPostOrderRegistration($dispatcher, $config);
				$plugin->setApplication(Factory::getApplication());
				$plugin->setContainer($container);

				return $plugin;
			}
		);
	}
};
