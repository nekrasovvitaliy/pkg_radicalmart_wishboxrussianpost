<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait;

use Exception;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Prepares the Russian Post destination region field.
 *
 * @method void prepareConfiguredCheckoutField(string $fieldName, string $defaultState)
 *
 * @since 1.0.0
 */
trait RegionToFieldPreparerTrait
{
	/**
	 * Prepare the destination region field.
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function prepareRegionToField(): void
	{
		$this->prepareConfiguredCheckoutField('region_to', 'required');
	}
}
