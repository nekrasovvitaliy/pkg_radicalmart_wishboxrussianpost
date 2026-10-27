<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration;

use Exception;
use Joomla\Event\DispatcherAwareInterface;
use Joomla\Event\DispatcherAwareTrait;
use Joomla\Event\DispatcherInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;
use WishboxRadicalMartLibrary\Service\Order\OrderService;
use WishboxRussianPostLibrary\Service\ContainerAwareInterface;
use WishboxRussianPostLibrary\Service\ContainerAwareTrait;
use WishboxRussianPostLibrary\Service\Registration\OrderRegistrationServiceAwareInterface;
use WishboxRussianPostLibrary\Service\Registration\OrderRegistrationServiceAwareTrait;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration\DataProvider\RadicalMartOrderRegistrationDataProvider;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Orchestrates RadicalMart order registration in Russian Post.
 *
 * @since 1.0.0
 */
final class RadicalMartOrderRegistrationService implements
	ContainerAwareInterface,
	DispatcherAwareInterface,
	OrderRegistrationServiceAwareInterface
{
	/**
	 * Shipping data key containing Russian Post registration errors.
	 *
	 * @since 1.0.0
	 */
	private const string SHIPPING_DATA_REGISTRATION_ERROR_MESSAGE = 'russianpost_registration_error_message';

	use ContainerAwareTrait;
	use DispatcherAwareTrait;
	use OrderRegistrationServiceAwareTrait;

	/**
	 * Class constructor.
	 *
	 * @param   DispatcherInterface                 $dispatcher               Event dispatcher.
	 * @param   RadicalMartOrderRegistrationPolicy  $orderRegistrationPolicy  Order registration policy.
	 * @param   OrderService                        $orderService              RadicalMart order service.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		DispatcherInterface $dispatcher,
		private readonly RadicalMartOrderRegistrationPolicy $orderRegistrationPolicy,
		private readonly OrderService $orderService
	)
	{
		$this->setDispatcher($dispatcher);
	}

	/**
	 * Register a RadicalMart order in Russian Post when the policy permits it.
	 *
	 * @param   stdClass  $order  RadicalMart order.
	 *
	 * @return void
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function register(stdClass $order): void
	{
		if (!$this->orderRegistrationPolicy->canRegister($order))
		{
			return;
		}

		$dataProvider = new RadicalMartOrderRegistrationDataProvider(
			$this->getDispatcher(),
			$order
		);

		try
		{
			$this->getOrderRegistrationService()
				->register($dataProvider);
		}
		catch (Exception $e)
		{
			$this->orderService->setShippingDataValue(
				$order->id,
				self::SHIPPING_DATA_REGISTRATION_ERROR_MESSAGE,
				$e->getMessage()
			);
		}
	}
}
