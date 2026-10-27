<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later
 */

namespace WishboxRadicalMartCdekOrderRegistrationLibrary\Service\OrderRegistration;

use stdClass;

/**
 * @since 1.0.0
 */
class RadicalMartOrderSyncPolicy
{
	/**
	 * @param   stdClass  $order  Order
	 *
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function canSync(stdClass $order): bool
	{
		return ($order->shipping->plugin ?? '') == 'wishboxrussianpost';
	}
}
