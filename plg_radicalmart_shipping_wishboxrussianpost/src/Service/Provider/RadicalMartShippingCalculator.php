<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Provider;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Calculator\RadicalMartShippingCalculatorService;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorService;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Service provider for RadicalMart shipping calculator services.
 *
 * @since 1.0.0
 */
class RadicalMartShippingCalculator implements ServiceProviderInterface
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
			RadicalMartShippingCalculatorService::class,
			function (Container $container) {
				return new RadicalMartShippingCalculatorService(
					$container->get(CalculatorService::class),
					$container->get(DispatcherInterface::class)
				);
			}
		);
	}
}
