<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Calculator;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Defines the trait for a RadicalMartShippingCalculatorService aware class.
 *
 * @since 1.0.0
 */
trait RadicalMartShippingCalculatorServiceAwareTrait
{
	/**
	 * @var RadicalMartShippingCalculatorService|null
	 *
	 * @since 1.0.0
	 */
	private ?RadicalMartShippingCalculatorService $radicalMartShippingCalculatorService = null;

	/**
	 * Get the RadicalMart shipping calculator service.
	 *
	 * @return RadicalMartShippingCalculatorService
	 *
	 * @throws ContainerExceptionInterface When the container cannot create the service.
	 * @throws NotFoundExceptionInterface When the service is not registered.
	 *
	 * @since 1.0.0
	 */
	protected function getRadicalMartShippingCalculatorService(): RadicalMartShippingCalculatorService
	{
		if ($this->radicalMartShippingCalculatorService !== null)
		{
			return $this->radicalMartShippingCalculatorService;
		}

		$this->radicalMartShippingCalculatorService = $this->getContainer()->get(
			RadicalMartShippingCalculatorService::class
		);

		return $this->radicalMartShippingCalculatorService;
	}

	/**
	 * Set the RadicalMart shipping calculator service.
	 *
	 * @param   RadicalMartShippingCalculatorService  $radicalMartShippingCalculatorService  RadicalMart shipping calculator service
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function setRadicalMartShippingCalculatorService(
		RadicalMartShippingCalculatorService $radicalMartShippingCalculatorService
	): void
	{
		$this->radicalMartShippingCalculatorService = $radicalMartShippingCalculatorService;
	}
}
