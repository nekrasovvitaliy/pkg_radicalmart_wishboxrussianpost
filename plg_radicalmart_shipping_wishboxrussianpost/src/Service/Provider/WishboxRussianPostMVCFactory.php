<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Provider;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\WishboxRussianPost\Administrator\Extension\WishboxRussianPostComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Service provider for the Wishbox Russian Post MVC factory.
 *
 * @since 1.0.0
 */
class WishboxRussianPostMVCFactory implements ServiceProviderInterface
{
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
		$container->set(
			MVCFactoryInterface::class,
			function (Container $container) {
				$app = Factory::getApplication();

				/** @var WishboxRussianPostComponent $wishboxRussianPostComponent */
				$wishboxRussianPostComponent = $app->bootComponent('com_wishboxrussianpost');

				return $wishboxRussianPostComponent->getMVCFactory();
			}
		);
	}
}
