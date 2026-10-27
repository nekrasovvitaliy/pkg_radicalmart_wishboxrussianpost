<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Shipping;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Defines the trait for a ShippingNotificationService aware class.
 *
 * @since 1.0.0
 */
trait ShippingNotificationServiceAwareTrait
{
	/**
	 * @var ShippingNotificationService|null
	 *
	 * @since 1.0.0
	 */
	private ?ShippingNotificationService $shippingNotificationService = null;

	/**
	 * Get the shipping notification service.
	 *
	 * @return ShippingNotificationService
	 *
	 * @throws ContainerExceptionInterface When the container cannot create the service.
	 * @throws NotFoundExceptionInterface When the service is not registered.
	 *
	 * @since 1.0.0
	 */
	protected function getShippingNotificationService(): ShippingNotificationService
	{
		if ($this->shippingNotificationService !== null)
		{
			return $this->shippingNotificationService;
		}

		$this->shippingNotificationService = $this->getContainer()->get(ShippingNotificationService::class);

		return $this->shippingNotificationService;
	}

	/**
	 * Set the shipping notification service.
	 *
	 * @param   ShippingNotificationService  $shippingNotificationService  Shipping notification service
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function setShippingNotificationService(ShippingNotificationService $shippingNotificationService): void
	{
		$this->shippingNotificationService = $shippingNotificationService;
	}
}
