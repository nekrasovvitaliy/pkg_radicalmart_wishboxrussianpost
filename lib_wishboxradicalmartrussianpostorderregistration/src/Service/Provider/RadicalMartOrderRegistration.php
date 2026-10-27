<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\Provider;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use WishboxRadicalMartLibrary\Service\Order\OrderService;
use WishboxRadicalMartLibrary\Service\Provider\OrderServiceProvider;
use WishboxRussianPostLibrary\Extension\Service\Provider\RequestFactory;
use WishboxRussianPostLibrary\Service\Provider\OrderRegistration as OrderRegistrationProvider;
use WishboxRussianPostLibrary\Service\Provider\RussianPostClientProvider;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration\RadicalMartOrderRegistrationPolicy;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration\RadicalMartOrderRegistrationService;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Registers RadicalMart order registration services.
 *
 * @since 1.0.0
 */
final class RadicalMartOrderRegistration implements ServiceProviderInterface
{
	/**
	 * Register RadicalMart order registration services in the DI container.
	 *
	 * @param   Container  $container  DI container.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(Container $container): void
	{
		$container->registerServiceProvider(new OrderServiceProvider);
		$container->registerServiceProvider(new RequestFactory);
		$container->registerServiceProvider(new RussianPostClientProvider);
		$container->registerServiceProvider(new OrderRegistrationProvider);

		$container->set(
			RadicalMartOrderRegistrationPolicy::class,
			static fn(): RadicalMartOrderRegistrationPolicy => new RadicalMartOrderRegistrationPolicy
		);

		$container->set(
			RadicalMartOrderRegistrationService::class,
			static function (Container $container): RadicalMartOrderRegistrationService {
				$radicalMartOrderRegistrationService = new RadicalMartOrderRegistrationService(
					$container->get(DispatcherInterface::class),
					$container->get(RadicalMartOrderRegistrationPolicy::class),
					$container->get(OrderService::class)
				);
				$radicalMartOrderRegistrationService->setContainer($container);

				return $radicalMartOrderRegistrationService;
			}
		);
	}
}
