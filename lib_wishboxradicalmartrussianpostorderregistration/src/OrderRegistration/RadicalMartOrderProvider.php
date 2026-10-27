<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later
 */

namespace WishboxRadicalMartCdekOrderRegistrationLibrary\Service\OrderRegistration;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Component\RadicalMart\Administrator\Extension\RadicalMartComponent;
use Joomla\Component\RadicalMart\Administrator\Model\OrderModel as RadicalMartOrderModel;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use stdClass;
use WishboxRussianPostLibrary\Enum\OrderRegistrationState;

/**
 * @since 1.0.0
 */
class RadicalMartOrderProvider
{
	use DatabaseAwareTrait;

	/**
	 * @param   DatabaseInterface  $database  Database
	 *
	 * @since 1.0.0
	 */
	public function __construct(DatabaseInterface $database)
	{
		$this->setDatabase($database);
	}

	/**
	 * @param   integer  $orderId  Order id
	 *
	 * @return stdClass
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getById(int $orderId): stdClass
	{
		return $this->getOrderModel()
			->getItem($orderId);
	}

	/**
	 * @param   list<OrderRegistrationState>  $states  Array of states
	 *
	 * @return integer[]
	 *
	 * @since 1.0.0
	 */
	public function getIdsByRegistrationStates(array $states): array
	{
		$componentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');
		$statusIds       = [];

		foreach ($states as $state)
		{
			$statusIds[] = (int) $componentParams->get(
				'wishboxradicalmartrussianpostorderregistration.' . $state->value . '_status_id',
				0
			);
		}

		$db = $this->getDatabase();

		$query = $db->createQuery()
			->select('id')
			->from('#__radicalmart_orders')
			->whereIn('status', $statusIds)
			->where('state=1');
		$db->setQuery($query);

		return $db->loadColumn();
	}

	/**
	 * @return RadicalMartOrderModel
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private function getOrderModel(): RadicalMartOrderModel
	{
		$app = Factory::getApplication();

		/** @var RadicalMartComponent $radicalMartComponent */
		$radicalMartComponent = $app->bootComponent('com_radicalmart');

		/** @var RadicalMartOrderModel $orderModel */
		$orderModel = $radicalMartComponent->getMVCFactory()
			->createModel(
				'order',
				'Administrator',
				[
					'ignore_request' => true
				]
			);

		return $orderModel;
	}
}
