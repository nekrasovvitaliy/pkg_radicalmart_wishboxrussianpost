<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\AbstractFormPreparer;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\HouseToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\IndexToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\PlaceToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\RecipientNameFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\RegionToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\RoomToFieldPreparerTrait;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\StreetToFieldPreparerTrait;
use Joomla\Registry\Registry;

/**
 * @since 1.0.0
 */
class PersonalShippingMethodFormPreparer extends AbstractFormPreparer
{
	use RecipientNameFieldPreparerTrait;
	use IndexToFieldPreparerTrait;
	use RegionToFieldPreparerTrait;
	use PlaceToFieldPreparerTrait;
	use StreetToFieldPreparerTrait;
	use HouseToFieldPreparerTrait;
	use RoomToFieldPreparerTrait;

	/**
	 * @var object|array
	 *
	 * @since 1.0.0
	 */
	protected object|array $data;

	/**
	 * Shipping
	 *
	 * @var object|array
	 *
	 * @since 1.0.0
	 */
	protected object|array $shipping {
		get {
			return $this->shipping;
		}
	}

	/**
	 * @param   Form                 $form                          Form
	 * @param   MVCFactoryInterface  $radicalMartMvcFactory         RadicalMart MVC factory
	 * @param   Registry             $radicalMartRussianPostParams  RadicalMart Russian Post component params
	 * @param   object|array         $data                          Data
	 * @param   object|array         $shipping                      Tmp data
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		Form                $form,
		MVCFactoryInterface $radicalMartMvcFactory,
		Registry            $radicalMartRussianPostParams,
		object|array        $data,
		object|array        $shipping
	)
	{
		parent::__construct($form, $radicalMartMvcFactory, $radicalMartRussianPostParams);

		$this->data                        = $data;
		$this->shipping                    = $shipping;
		$this->shippingFieldAttributeGroup = null;
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
	 * @return object|array
	 *
	 * @since 1.0.0
	 */
	protected function getShipping(): object|array
	{
		return $this->shipping;
	}

	/**
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function isTariffToPoint(): bool
	{
		$tariffMode = $this->shipping->params->get('tariff_mode', '');

		return in_array($tariffMode, ['С-С', 'Д-С']);
	}
}
