<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Shipping;

use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Interface to be implemented by classes depending on a shipping notification service.
 *
 * @since 1.0.0
 */
interface ShippingNotificationServiceAwareInterface
{
	/**
	 * Set the shipping notification service.
	 *
	 * @param   ShippingNotificationService  $shippingNotificationService  Shipping notification service
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function setShippingNotificationService(ShippingNotificationService $shippingNotificationService): void;
}
