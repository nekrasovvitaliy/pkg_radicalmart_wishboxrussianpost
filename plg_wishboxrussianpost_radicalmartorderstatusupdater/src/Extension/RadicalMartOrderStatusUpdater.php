<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later
 *
 * @noinspection PhpMultipleClassDeclarationsInspection
 */
namespace Joomla\Plugin\WishboxRussianPost\RadicalMartOrderStatusUpdater\Extension;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\RadicalMart\Administrator\Extension\RadicalMartComponent;
use Joomla\Component\RadicalMart\Administrator\Model\OrderModel;
use Joomla\Component\RadicalMart\Administrator\Table\OrderTable;
use Joomla\Component\WishboxRussianPost\Administrator\Extension\WishboxRussianPostComponent;
use Joomla\Component\WishboxRussianPost\Administrator\Table\StatusTable;
use Joomla\Component\WishboxRadicalMartRussianPost\Administrator\Helper\WishboxRadicalMartRussianPostHelper;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Event\SubscriberInterface;
use WishboxRussianPostLibrary\Event\Service\OrderStatusUpdater\GetRussianPostNumbersEvent;
use WishboxRussianPostLibrary\Event\Service\OrderStatusUpdater\UpdateOrderStatusEvent;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 1.0.0
 *
 * @noinspection PhpUnused
 */
final class RadicalMartOrderStatusUpdater extends CMSPlugin implements DatabaseAwareInterface, SubscriberInterface
{
	use DatabaseAwareTrait;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var boolean
	 *
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onWishboxRussianPostOrderStatusUpdaterGetRussianPostNumbers'     => 'onWishboxRussianPostOrderStatusUpdaterGetRussianPostNumbers',
			'onWishboxRussianPostOrderStatusUpdaterUpdateOrderStatus'  => 'onWishboxRussianPostOrderStatusUpdaterUpdateOrderStatus'
		];
	}

	/**
	 * @param   GetRussianPostNumbersEvent  $event  Event
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function onWishboxRussianPostOrderStatusUpdaterGetRussianPostNumbers(GetRussianPostNumbersEvent $event): void
	{
		$component = $event->getComponent();

		if (!in_array($component, ['', 'radicalmart'], true))
		{
			return;
		}

		$orderIds = $event->getOrderIds();

		$db = $this->getDatabase();

		$subQuery = $db->createQuery()
			->select($db->qn('id'))
			->from($db->qn('#__radicalmart_shipping_methods'))
			->where($db->qn('plugin') . ' = ' . $db->q('wishboxrussianpost'));
		$db->setQuery($subQuery);

		$shippingMethodIds = $db->loadColumn();
		$oldOrderStatusIds = $this->getOldOrderStatusIds();

		if (is_array($shippingMethodIds) && count($shippingMethodIds)
			&& count($oldOrderStatusIds))
		{
			$trackingNumberExpression = 'JSON_UNQUOTE(JSON_EXTRACT('
				. $db->qn('shipping') . ', ' . $db->q('$.data.tracking_number') . '))';

			$query = $db->createQuery()
				->select($trackingNumberExpression . ' AS ' . $db->qn('trackingNumber'))
				->from($db->qn('#__radicalmart_orders'))
				->where($db->qn('state') . ' = 1')
				->whereIn($db->qn('status'), $oldOrderStatusIds)
				->whereIn('JSON_EXTRACT(shipping, ' . $db->q('$.id') . ')', $shippingMethodIds)
				->where($trackingNumberExpression . ' IS NOT NULL')
				->where($trackingNumberExpression . ' <> ' . $db->q(''));

			if (count($orderIds))
			{
				$query->whereIn($db->qn('id'), $orderIds);
			}

			$db->setQuery($query);

			$russianpostNumbers = $db->loadColumn();

			if (is_array($russianpostNumbers) && count($russianpostNumbers))
			{
				foreach ($russianpostNumbers as $russianpostNumber)
				{
					$event->addResult($russianpostNumber);
				}
			}
		}
	}

	/**
	 * @param   UpdateOrderStatusEvent  $event  Event
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function onWishboxRussianPostOrderStatusUpdaterUpdateOrderStatus(UpdateOrderStatusEvent $event): void
	{
		$component = $event->getComponent();

		if (!in_array($component, ['', 'radicalmart'], true))
		{
			return;
		}

		$russianpostNumber = $event->getRussianPostNumber();
		$orderRussianPostStatuses = $event->getOrderRussianPostStatuses();

		$app = $this->getApplication();

		$lastOrderRussianPostStatus = $orderRussianPostStatuses[array_key_last($orderRussianPostStatuses)];
		$lastOrderRussianPostStatusCode = (string) $lastOrderRussianPostStatus->code;

		if ($lastOrderRussianPostStatusCode === '')
		{
			return;
		}

		$this->updateOrderStatus($russianpostNumber, $lastOrderRussianPostStatusCode);

		/** @var WishboxRussianPostComponent $wishboxRussianPostComponent */
		$wishboxRussianPostComponent = $app->bootComponent('com_wishboxrussianpost');

		foreach ($orderRussianPostStatuses as $statusResponse)
		{
			if (!$statusResponse->code)
			{
				continue;
			}

			/** @var StatusTable $statusTable */
			$statusTable = $wishboxRussianPostComponent->getMVCFactory()
				->createTable('Status');

			if (!$statusTable->load(['code' => $statusResponse->code]))
			{
				$statusTable->code = $statusResponse->code;
				$statusTable->name = (string) $statusResponse->name;

				if (!$statusTable->store())
				{
					throw new Exception($statusTable->getError(), 500);
				}
			}
		}
	}

	/**
	 * @param   string  $russianpostNumber               RussianPost number
	 * @param   string  $lastOrderRussianPostStatusCode  Code of the latest order status in RussianPost
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function updateOrderStatus(string $russianpostNumber, string $lastOrderRussianPostStatusCode): void
	{
		$app = $this->getApplication();

		/** @var RadicalMartComponent $radicalMartComponent */
		$radicalMartComponent = $app->bootComponent('com_radicalmart');

		/** @var OrderTable $orderTable */
		$orderTable = $radicalMartComponent->getMVCFactory()
			->createTable('Order', 'Administrator');

		$orderId = WishboxRadicalMartRussianPostHelper::getOrderIdByRussianPostNumber($russianpostNumber);

		if (!$orderId || !$orderTable->load($orderId))
		{
			throw new Exception('RadicalMart order with Russian Post number "' . $russianpostNumber . '" was not found.', 404);
		}

		$oldOrderStatusId = (int) $orderTable->status;
		$newOrderStatusId = $this->getNewOrderStatusId($oldOrderStatusId, $lastOrderRussianPostStatusCode);

		if ($newOrderStatusId && $newOrderStatusId !== $oldOrderStatusId)
		{
			/** @var OrderModel $orderModel */
			$orderModel = $radicalMartComponent->getMVCFactory()
				->createModel('order', 'Administrator', ['ignore_request' => true]);

			$orderModel->updateStatus(
				$orderTable->id,
				$newOrderStatusId,
				false,
				null,
				'Automatically changed in accordance with the status in RussianPost'
			);
		}
	}

	/**
	 * @return integer[]
	 *
	 * @since 1.0.0
	 */
	private function getOldOrderStatusIds(): array
	{
		$wishboxRadicalMartRussianPostComponentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');
		$statuses = (array) $wishboxRadicalMartRussianPostComponentParams->get('wishboxradicalmartrussianpostorderstatusupdater.statuses', []);

		$oldOrderStatusIds = [];

		foreach ($statuses as $status)
		{
			$oldOrderStatusIds[] = $status->old_status_id; // phpcs:ignore;
		}

		return array_unique($oldOrderStatusIds);
	}

	/**
	 * @param   integer  $oldStatusId          Old status id
	 * @param   string   $orderRussianPostStatusCode  Order RussianPost status code
	 *
	 * @return integer|null
	 *
	 * @since 1.0.0
	 */
	private function getNewOrderStatusId(int $oldStatusId, string $orderRussianPostStatusCode): ?int
	{
		$wishboxRadicalMartRussianPostComponentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');
		$statuses = (array) $wishboxRadicalMartRussianPostComponentParams->get('wishboxradicalmartrussianpostorderstatusupdater.statuses', []);

		foreach ($statuses as $status)
		{
			if ($oldStatusId == $status->old_status_id && $orderRussianPostStatusCode == $status->wishboxrussianpost_status_code) // phpcs:ignore
			{
				return $status->new_status_id; // phpcs:ignore
			}
		}

		return null;
	}
}
