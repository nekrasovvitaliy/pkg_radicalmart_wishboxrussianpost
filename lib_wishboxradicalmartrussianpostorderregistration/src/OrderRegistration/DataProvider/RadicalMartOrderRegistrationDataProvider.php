<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration\DataProvider;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\AbstractEvent;
use Joomla\Component\WishboxRussianPost\Site\Entity\ProductEntity;
use Joomla\Component\WishboxRadicalMartCdek\Administrator\Exception\OrderServiceException;
use Joomla\Component\WishboxRadicalMartCdek\Administrator\Helper\WishboxRadicalMartCdekHelper;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMart\WishboxRussianPostOrderRegistration\Exception\EmptyProductCodeException;
use Joomla\Registry\Registry;
use stdClass;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Event\Service\OrderRegistrationDataProvider\GetOrdersPatchPackagesEvent;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Event\Service\OrderRegistrationDataProvider\GetOrdersPostPackagesEvent;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Interface\OrderRegistrationPackageDataProviderInterface;

/**
 * @since 1.0.0
 */
readonly class RadicalMartOrderRegistrationDataProvider implements OrderRegistrationPackageDataProviderInterface
{
	/**
	 * Class constructor.
	 *
	 * @param   DispatcherInterface  $dispatcher  Dispatcher
	 * @param   stdClass             $order       Order
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		private DispatcherInterface $dispatcher,
		private stdClass            $order
	)
	{
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getOrderComment(): string
	{
		return $this->order->formData['shipping']['note'] ?? '';
	}

	/**
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getSellerName(): string
	{
		$sellerName = (string) $this->getOrderShippingMethodParams()->get('seller_name');

		if (empty($sellerName))
		{
			throw new Exception('Seller name in shipping method params must not be empty', 500);
		}

		return $sellerName;
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getOrderNumber(): string
	{
		$componentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');
		$prefix          = $componentParams->get('wishboxradicalmartrussianpostorderregistration.order_number_prefix');

		return $prefix . $this->order->number;
	}

	/**
	 * @return integer
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getSellerOwnershipForm(): int
	{
		return (int) $this->getOrderShippingMethodParams()->get('seller_owner_ship_form_code');
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getRecipientName(): string
	{
		return $this->order->formData['contacts']['last_name'] . ' ' . $this->order->formData['contacts']['first_name'];
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getRecipientEmail(): string
	{
		return $this->order->formData['contacts']['email'];
	}

	/**
	 * Returns total weight in grams
	 *
	 * @return integer
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getTotalWeight(): int
	{
		$totalWeight = 0;

		foreach ($this->order->products as $product)
		{
			$productWeight = (int) WishboxRadicalMartCdekHelper::getProductWeight($product, 'g');

			$totalWeight += $productWeight * $product->order['quantity'];
		}

		return $totalWeight;
	}

	/**
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function shouldUseDefaultPackageWeight(): bool
	{
		return (bool) $this->getOrderShippingMethodParams()
			->get('wishboxrussianpostpackageone.use_default_weight');
	}

	/**
	 * @return integer
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getDefaultPackageWeight(): int
	{
		return (int) $this->getOrderShippingMethodParams()
			->get('wishboxrussianpostpackageone.default_weight');
	}

	/**
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function getOrderPackageDimensions(): array
	{
		$registry   = new Registry($this->order->formData['shipping'] ?? []);
		$dimensions = $registry->get('wishboxrussianpostpackageone.dimensions', []);

		return is_array($dimensions) ? $dimensions : [];
	}

	/**
	 * @return array
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getDefaultPackageDimensions(): array
	{
		return (array) $this->getOrderShippingMethodParams()
			->get('wishboxrussianpostpackageone.default_dimensions');
	}

	/**
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function shouldUseDefaultProductCost(): bool
	{
		return (bool) $this->getOrderShippingMethodParams()
			->get('wishboxrussianpostpackageone.use_default_product_cost', 0);
	}

	/**
	 * @return float
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getDefaultProductCost(): float
	{
		return (float) $this->getOrderShippingMethodParams()
			->get('wishboxrussianpostpackageone.default_product_cost', 0);
	}

	/**
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function shouldUseProductPayment(): bool
	{
		return (bool) $this->getOrderShippingMethodParams()
			->get('product_payment.use_product_payment', 0);
	}

	/**
	 * @return integer
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getProductPaymentVatRate(): int
	{
		return (int) $this->getOrderShippingMethodParams()
			->get('product_payment.product_payment_vat_rate');
	}

	/**
	 * @return ProductEntity[]
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getProducts(): array
	{
		$products = [];

		$russianPostProductCodeSource = $this->getRussianPostProductCodeSource();

		foreach ($this->order->products as $product)
		{
			if (empty($product->$russianPostProductCodeSource))
			{
				throw new EmptyProductCodeException($product);
			}

			$productCost    = (float) $product->price['base'];
			$productPayment = 0;

			$productEntity = new ProductEntity(
				$product->title,
				$product->$russianPostProductCodeSource,
				(float) $product->price['base'],
				$productPayment,
				$productCost,
				WishboxRadicalMartCdekHelper::getProductWeight($product, 'g'),
				(int) $product->order['quantity']
			);

			$products[] = $productEntity;
		}

		return $products;
	}

	/**
	 * @return stdClass[]
	 *
	 * @since 1.0.0
	 */
	public function getOrderProducts(): array
	{
		return $this->order->products;
	}

	/**
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getShipmentPoint(): string
	{
		$senderOfficeCode = $this->getOrderShippingMethodParams()->get('sender_office_code', '');

		if ($senderOfficeCode == '-1')
		{
			throw new Exception('SenderOfficeCode is not set in shipping method params.', 500);
		}

		return $senderOfficeCode;
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getRecipientCompany(): string
	{
		return $this->order->formData['contacts']['last_name'] . ' ' . $this->order->formData['contacts']['first_name'];
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getCdekRegistrationErrorMessage(): string
	{
		return $this->getShippingDataValue('cdek_registration_error');
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getCdekRegistrationEntityUuid(): string
	{
		return $this->getShippingDataValue('cdek_registration_entity_uuid');
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getCdekRegistrationRequestUuid(): string
	{
		return $this->getShippingDataValue('cdek_registration_request_uuid');
	}

	/**
	 * @return string[]
	 *
	 * @since 1.0.0
	 */
	public function getCdekRegistrationErrorMessages(): array
	{
		$errorMessage = $this->getShippingDataValue('cdek_registration_error_message');

		if (!$errorMessage)
		{
			$errorMessage = $this->getCdekRegistrationErrorMessage();
		}

		$messages = preg_split('/\R+/', $errorMessage) ?: [];
		$messages = array_map('trim', $messages);

		return array_values(
			array_filter(
				$messages,
				static fn($message) => $message !== '' && $message !== 'Last request errors:'
			)
		);
	}

	/**
	 * @return string
	 *
	 * @throws OrderServiceException
	 *
	 * @since 1.0.0
	 */
	public function getDeliveryAddress(): string
	{
		if (!isset($this->order->formData['shipping']['address']))
		{
			throw new OrderServiceException(
				'Order with has empty address',
				500,
				$this->order->id
			);
		}

		return $this->order->formData['shipping']['address'];
	}

	/**
	 * @return string
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private function getCdekProductCodeSource(): string
	{
		return $this->getOrderShippingMethodParams()->get('cdek_product_code_source', 'id');
	}

	/**
	 * @param   string  $name     Data name
	 * @param   string  $default  Default value
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	private function getShippingDataValue(string $name, string $default = ''): string
	{
		$registry = new Registry($this->order->formData ?? null);

		return (string) $registry->get('shipping.' . $name, $default);
	}

	/**
	 * @return Registry
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private function getOrderShippingMethodParams(): Registry
	{
		$shippingId = $this->order->formData['shipping']['id'] ?? null;

		if ($shippingId === null)
		{
			throw new Exception('Order ' . $this->order->number . ' has no shipping method id.', 500);
		}

		$params = $this->order->shippingMethods[$shippingId]->params ?? null;

		if (!$params instanceof Registry)
		{
			throw new Exception('Order ' . $this->order->number . ' has no shipping method params.', 500);
		}

		return $params;
	}

	/**
	 * @return PackageRequestDto[]
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getOrdersPostPackages(): array
	{
		/** @var GetOrdersPostPackagesEvent $event */
		$event = AbstractEvent::create(
			'onWishboxRadicalMartCdekOrderRegistrationDataProviderGetOrdersPostPackages',
			[
				'subject'    => $this,
				'eventClass' => GetOrdersPostPackagesEvent::class
			]
		);

		/** @var GetOrdersPostPackagesEvent $eventResult */
		$eventResult = $this->dispatcher->dispatch($event->getName(), $event);

		$packageRequests = $eventResult->getPackageRequests();

		if (!count($packageRequests))
		{
			throw new Exception('Order registration POST packages provider is not configured.', 500);
		}

		return $packageRequests;
	}

	/**
	 * @return PackageRequestDto[]
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getOrdersPatchPackages(): array
	{
		/** @var GetOrdersPatchPackagesEvent $event */
		$event = AbstractEvent::create(
			'onWishboxRadicalMartCdekOrderRegistrationDataProviderGetOrdersPatchPackages',
			[
				'subject'    => $this,
				'eventClass' => GetOrdersPatchPackagesEvent::class
			]
		);

		/** @var GetOrdersPatchPackagesEvent $eventResult */
		$eventResult = $this->dispatcher->dispatch($event->getName(), $event);

		$packageRequests = $eventResult->getPackageRequests();

		if (!count($packageRequests))
		{
			throw new Exception('Order registration PATCH packages provider is not configured.', 500);
		}

		return $packageRequests;
	}
}
