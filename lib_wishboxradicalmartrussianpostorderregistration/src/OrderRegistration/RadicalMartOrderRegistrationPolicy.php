<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later
 */

namespace WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration;

use Joomla\CMS\Component\ComponentHelper;
use stdClass;

/**
 * @since 1.0.0
 */
class RadicalMartOrderRegistrationPolicy
{
	/**
	 * @param   stdClass  $order  Order
	 *
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function canRegister(stdClass $order): bool
	{
		$componentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');
		$allowedStatusIds = [
			(int) $componentParams->get('wishboxradicalmartrussianpostorderregistration.ready_status_id', 0),
			(int) $componentParams->get('wishboxradicalmartrussianpostorderregistration.error_status_id', 0),
		];

		return $this->isWishboxRussianPostOrder($order)
			&& in_array((int) ($order->status->id ?? 0), $allowedStatusIds, true);
	}

	/**
	 * @param   stdClass  $order  Order
	 *
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function canUpdate(stdClass $order): bool
	{
		$componentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');
		$allowedStatusIds = [
			(int) $componentParams->get('wishboxradicalmartrussianpostorderregistration.pending_status_id', 0),
			(int) $componentParams->get('wishboxradicalmartrussianpostorderregistration.completed_status_id', 0),
		];

		return $this->isWishboxRussianPostOrder($order)
			&& in_array((int) ($order->status->id ?? 0), $allowedStatusIds, true);
	}

	/**
	 * @param   stdClass  $order  Order
	 *
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	private function isWishboxRussianPostOrder(stdClass $order): bool
	{
		return ($order->shipping->plugin ?? '') == 'wishboxrussianpost';
	}
}
