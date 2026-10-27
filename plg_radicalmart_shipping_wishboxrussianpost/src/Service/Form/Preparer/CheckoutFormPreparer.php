<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Event\DispatcherAwareInterface;
use Joomla\Event\DispatcherAwareTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\AbstractFormPreparer;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\ErrorMessageFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\HouseToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\IndexToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\PlaceToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\RecipientNameFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\RegionToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\RoomToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\StreetToFieldPreparerTrait;
use Joomla\Registry\Registry;
use stdClass;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorServiceAwareInterface;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorServiceAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 1.0.0
 */
class CheckoutFormPreparer extends AbstractFormPreparer implements CalculatorServiceAwareInterface, DispatcherAwareInterface
{
	use CalculatorServiceAwareTrait;
	use DispatcherAwareTrait;
	use RecipientNameFieldPreparerTrait;
	use IndexToFieldPreparerTrait;
	use RegionToFieldPreparerTrait;
	use PlaceToFieldPreparerTrait;
	use StreetToFieldPreparerTrait;
	use HouseToFieldPreparerTrait;
	use RoomToFieldPreparerTrait;
	use ErrorMessageFieldPreparerTrait;

	/**
	 * @var stdClass  $shipping  Shipping
	 *
	 * @since 1.0.0
	 */
	public stdClass $shipping {
		get {
			return $this->shipping;
		}
	}

	/**
	 * @var   array  $formData  Form data
	 *
	 * @since 1.0.0
	 */
	protected array $formData {
		get {
			return $this->formData;
		}
	}

	/**
	 * @var   array  $producs Products
	 *
	 * @since 1.0.0
	 */
	protected array $products {
		get {
			return $this->products;
		}
	}

	/**
	 * @param   Form                 $form                   Form
	 * @param   MVCFactoryInterface  $radicalMartMvcFactory  RadicalMart MVC factory
	 * @param   Registry             $radicalMartRussianPostParams  RadicalMart Russian Post component params
	 * @param   stdClass             $shipping               Shipping
	 * @param   array                $formData               Form data
	 * @param   array                $products               Products
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		Form $form,
		MVCFactoryInterface $radicalMartMvcFactory,
		Registry $radicalMartRussianPostParams,
		stdClass $shipping,
		array $formData,
		array $products
	)
	{
		parent::__construct($form, $radicalMartMvcFactory, $radicalMartRussianPostParams);

		$this->shipping = $shipping;
		$this->formData = $formData;
		$this->products = $products;
	}

	/**
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function prepare(): void
	{
		parent::prepare();

		if (!$this->checkPlugin())
		{
			return;
		}

		$this->prepareRecipientNameField();
		$this->prepareIndexToField();
		$this->prepareRegionToField();
		$this->preparePlaceToField();
		$this->prepareStreetToField();
		$this->prepareHouseToField();
		$this->prepareRoomToField();
		$this->prepareErrorMessageField();
	}

	/**
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function prepareAddressField(): void
	{
			if (!$this->form->removeField('address', 'shipping'))
			{
				throw new Exception('failed to removeField', 500);
			}
	}

	/**
	 * @return integer
	 *
	 * @since 1.0.0
	 */
	protected function getShippingId(): int
	{
		return $this->shipping->id;
	}

	/**
	 * @return stdClass
	 *
	 * @since 1.0.0
	 */
	protected function getShipping(): stdClass
	{
		return $this->shipping;
	}
}
