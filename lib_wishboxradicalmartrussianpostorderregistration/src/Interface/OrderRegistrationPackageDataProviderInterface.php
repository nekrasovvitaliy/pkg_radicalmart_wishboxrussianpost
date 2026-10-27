<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace WishboxRadicalMartRussianPostOrderRegistrationLibrary\Interface;

use stdClass;
use WishboxRussianPostLibrary\Interface\OrderRegistrationDataProviderInterface;

/**
 * @since 1.0.0
 */
interface OrderRegistrationPackageDataProviderInterface extends OrderRegistrationDataProviderInterface
{
	/**
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function shouldUseDefaultPackageWeight(): bool;

	/**
	 * @return integer
	 *
	 * @since 1.0.0
	 */
	public function getDefaultPackageWeight(): int;

	/**
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function getOrderPackageDimensions(): array;

	/**
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function getDefaultPackageDimensions(): array;

	/**
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function shouldUseDefaultProductCost(): bool;

	/**
	 * @return float
	 *
	 * @since 1.0.0
	 */
	public function getDefaultProductCost(): float;

	/**
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function shouldUseProductPayment(): bool;

	/**
	 * @return integer
	 *
	 * @since 1.0.0
	 */
	public function getProductPaymentVatRate(): int;

	/**
	 * @return stdClass[]
	 *
	 * @since 1.0.0
	 */
	public function getOrderProducts(): array;
}
