<?php
/**
 * @copyright  (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license    GNU General Public License version 2 or later
 */
namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\AbstractFormPreparer;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait\AddressFieldPreparerTrait;
use Joomla\Registry\Registry;
use stdClass;

/**
 * @since 1.0.0
 */
class OrderSiteFormPreparer extends AbstractFormPreparer
{
	use AddressFieldPreparerTrait;

	/**
	 * @var   stdClass  $shipping  Shipping
	 *
	 * @since 1.0.0
	 */
	protected stdClass $shipping {
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
	 * @var   stdClass[]  $products  Products
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
	 * @param   stdClass[]           $products               Products
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
		if (!$this->checkPlugin())
		{
			return;
		}

		$this->prepareAddressField();
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
