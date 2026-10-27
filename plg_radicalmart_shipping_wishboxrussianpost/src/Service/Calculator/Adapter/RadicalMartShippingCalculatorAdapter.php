<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Calculator\Adapter;

use InvalidArgumentException;
use Joomla\Event\DispatcherAwareInterface;
use Joomla\Event\DispatcherAwareTrait;
use Joomla\Event\DispatcherInterface;
use stdClass;
use WishboxRussianPost\Enum\MailCategory;
use WishboxRussianPost\Enum\MailType;
use WishboxRussianPostLibrary\Interface\CalculatorAdapterInterface;
use function ceil;
use function defined;
use function in_array;
use function trim;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Adapts RadicalMart shipping data for the Russian Post tariff calculator.
 *
 * @since 1.0.0
 */
final class RadicalMartShippingCalculatorAdapter implements CalculatorAdapterInterface, DispatcherAwareInterface
{
	use DispatcherAwareTrait;

	/**
	 * @var stdClass
	 *
	 * @since 1.0.0
	 */
	public stdClass $method {
		get {
			return $this->method;
		}
	}

	/**
	 * @var array $products Products
	 *
	 * @since 1.0.0
	 */
	public array $products {
		get {
			return $this->products;
		}
	}

	/**
	 * @var array $formData Form data
	 *
	 * @since 1.0.0
	 */
	public array $formData {
		get {
			return $this->formData;
		}
	}

	/**
	 * Class constructor.
	 *
	 * @param   stdClass             $method      Method
	 * @param   array                $formData    Form data
	 * @param   array                $products    Products
	 * @param   DispatcherInterface  $dispatcher  Dispatcher
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct(stdClass $method, array $formData, array $products, DispatcherInterface $dispatcher)
	{
		$this->method   = $method;
		$this->formData = $formData;
		$this->products = $products;
		$this->setDispatcher($dispatcher);
	}

	/**
	 * Return the RadicalMart shipping method identifier.
	 *
	 * @return int
	 *
	 * @since 1.0.0
	 */
	public function getShippingMethodId(): int
	{
		return (int) $this->method->id;
	}

	/**
	 * Return the configured Russian Post mail type.
	 *
	 * @return MailType
	 *
	 * @since 1.0.0
	 */
	public function getMailType(): MailType
	{
		$value = (string) $this->method->params->get('mail_type', MailType::PostalParcel->value);

		return MailType::from($value);
	}

	/**
	 * Return the configured Russian Post mail category.
	 *
	 * @return MailCategory
	 *
	 * @since 1.0.0
	 */
	public function getMailCategory(): MailCategory
	{
		$value = (string) $this->method->params->get('mail_category', MailCategory::Ordinary->value);

		return MailCategory::from($value);
	}

	/**
	 * Calculate the total shipment mass in grams.
	 *
	 * @return int
	 *
	 * @throws InvalidArgumentException When a product uses an unsupported weight unit.
	 *
	 * @since 1.0.0
	 */
	public function getMass(): int
	{
		$mass = 0.0;

		foreach ($this->products as $product)
		{
			$quantity = (float) ($product->order['quantity'] ?? 0);
			$mass     += $this->getProductMass($product) * $quantity;
		}

		return (int) ceil($mass);
	}

	/**
	 * Determine whether the completeness checking service is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasCompletenessChecking(): bool
	{
		return $this->getBooleanParameter('completeness_checking');
	}

	/**
	 * Determine whether the contents checking service is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasContentsChecking(): bool
	{
		return $this->getBooleanParameter('contents_checking');
	}

	/**
	 * Determine whether an inventory is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasInventory(): bool
	{
		return $this->getBooleanParameter('inventory');
	}

	/**
	 * Determine whether accompanying documents must be returned.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasVsd(): bool
	{
		return $this->getBooleanParameter('vsd');
	}

	/**
	 * Determine whether an electronic delivery notice is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasElectronicNotice(): bool
	{
		return $this->getBooleanParameter('with_electronic_notice');
	}

	/**
	 * Determine whether an order-of-delivery notice is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasOrderOfNotice(): bool
	{
		return $this->getBooleanParameter('with_order_of_notice');
	}

	/**
	 * Determine whether a simple delivery notice is required.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function hasSimpleNotice(): bool
	{
		return $this->getBooleanParameter('with_simple_notice');
	}

	/**
	 * Return the configured sender postal index.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getIndexFrom(): ?string
	{
		return $this->normalisePostalIndex($this->method->params->get('index_from'));
	}

	/**
	 * Return the recipient postal index from the checkout form.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	public function getIndexTo(): ?string
	{
		$value = $this->formData['shipping']['index_to']
			?? $this->formData['index_to']
			?? null;

		return $this->normalisePostalIndex($value);
	}

	/**
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function getCourier(): bool
	{
		return $this->getBooleanParameter('courier');
	}

	/**
	 * Return whether the shipment requires the fragile handling mark.
	 *
	 * @return bool
	 *
	 * @since        1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public function getFragile(): bool
	{
		return $this->getBooleanParameter('fragile');
	}

	/**
	 * Read a boolean shipping method parameter.
	 *
	 * @param   string  $name  Parameter name.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	private function getBooleanParameter(string $name): bool
	{
		$value = $this->method->params->get($name, 0);

		return in_array($value, [1, '1', true], true);
	}

	/**
	 * Calculate a product mass in grams.
	 *
	 * @param   stdClass  $product  RadicalMart product data.
	 *
	 * @return float
	 *
	 * @throws InvalidArgumentException When the weight unit is unsupported.
	 *
	 * @since 1.0.0
	 */
	private function getProductMass(stdClass $product): float
	{
		$weight = (float) $product->shipping->get('weight', 0);
		$unit   = (string) $product->shipping->get('weight_unit', '');

		return match ($unit)
		{
			'g' => $weight,
			'kg' => $weight * 1_000,
			't' => $weight * 1_000_000,
			default => throw new InvalidArgumentException('Unsupported product weight unit: ' . $unit),
		};
	}

	/**
	 * Convert a postal index value to its API representation.
	 *
	 * @param   mixed  $value  Raw postal index value.
	 *
	 * @return string|null
	 *
	 * @since 1.0.0
	 */
	private function normalisePostalIndex(mixed $value): ?string
	{
		$index = trim((string) $value);

		return $index === '' ? null : $index;
	}
}
