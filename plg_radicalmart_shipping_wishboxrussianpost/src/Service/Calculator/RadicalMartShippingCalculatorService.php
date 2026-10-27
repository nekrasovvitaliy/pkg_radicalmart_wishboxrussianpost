<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Calculator;

use Exception;
use Joomla\Event\DispatcherAwareInterface;
use Joomla\Event\DispatcherAwareTrait;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Calculator\Adapter\RadicalMartShippingCalculatorAdapter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Wishbox\ShippingService\ShippingTariff;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorService;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorServiceAwareInterface;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorServiceAwareTrait;
use function defined;
use function json_encode;
use function md5;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Calculates RadicalMart shipping tariffs through the Wishbox Russian Post calculator service.
 *
 * @since 1.0.0
 */
class RadicalMartShippingCalculatorService implements CalculatorServiceAwareInterface, DispatcherAwareInterface
{
	use CalculatorServiceAwareTrait;
	use DispatcherAwareTrait;

	/**
	 * @var ShippingTariff[][]
	 *
	 * @since 1.0.0
	 */
	private static array $shippingTariffs = [];

	/**
	 * @var Exception[]
	 *
	 * @since 1.0.0
	 */
	private static array $shippingTariffExceptions = [];

	/**
	 * Class constructor.
	 *
	 * @param   CalculatorService    $calculatorService  Calculator service
	 * @param   DispatcherInterface  $dispatcher         Dispatcher
	 *
	 * @since 1.0.0
	 */
	public function __construct(CalculatorService $calculatorService, DispatcherInterface $dispatcher)
	{
		$this->setCalculatorService($calculatorService);
		$this->setDispatcher($dispatcher);
	}

	/**
	 * @param   object  $method    Method data
	 * @param   array   $formData  Order form data
	 * @param   array   $products  Order products data
	 *
	 * @return ShippingTariff|null
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 *
	 * @since 1.0.0
	 */
	public function getShippingTariff(object $method, array $formData, array $products): ?ShippingTariff
	{
		return $this->getMinShippingTariff($method, $formData, $products);
	}

	/**
	 * @param   object  $method    Method data
	 * @param   array   $formData  Order form data
	 * @param   array   $products  Order products data
	 *
	 * @return ShippingTariff|null
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 *
	 * @since 1.0.0
	 */
	private function getMinShippingTariff(object $method, array $formData, array $products): ?ShippingTariff
	{
		$shippingTariffs = $this->getShippingTariffs($method, $formData, $products);

		if (!count($shippingTariffs))
		{
			throw new Exception('Array of tariffs must not be empty', 500);
		}

		$minTariff = $shippingTariffs[0];

		foreach ($shippingTariffs as $tariff)
		{
			if ($tariff->getShipping() < $minTariff->getShipping())
			{
				$minTariff = $tariff;
			}
		}

		return $minTariff;
	}

	/**
	 * @param   object  $method    Method data
	 * @param   array   $formData  Order form data
	 * @param   array   $products  Order products data
	 *
	 * @return ShippingTariff[]
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private function getShippingTariffs(object $method, array $formData, array $products): array
	{
		$hash = md5(
			json_encode($method->id)
			. json_encode($products)
		);

		if (!isset(self::$shippingTariffs[$hash]) && !isset(self::$shippingTariffExceptions[$hash]))
		{
			try
			{
				$calculatorAdapterService = new RadicalMartShippingCalculatorAdapter(
					$method,
					$formData,
					$products,
					$this->getDispatcher()
				);

				$calculatorService = $this->getCalculatorService();

				self::$shippingTariffs[$hash] = $calculatorService->getShippingTariffs($calculatorAdapterService);
			}
			catch (Exception $e)
			{
				self::$shippingTariffExceptions[$hash] = $e;

				throw $e;
			}
		}
		elseif (isset(self::$shippingTariffExceptions[$hash]))
		{
			throw self::$shippingTariffExceptions[$hash];
		}
		elseif (isset(self::$shippingTariffs[$hash]))
		{
			return self::$shippingTariffs[$hash];
		}

		return self::$shippingTariffs[$hash];
	}
}
