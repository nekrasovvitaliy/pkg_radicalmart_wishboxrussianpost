<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\RadicalMart\Administrator\Table\ShippingMethodTable;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 1.0.0
 */
abstract class AbstractFormPreparer
{
	/**
	 * @var Form $form Form
	 *
	 * @since 1.0.0
	 */
	protected Form $form {
		get {
			return $this->form;
		}
	}

	/**
	 * @var string|null
	 *
	 * @since 1.0.0
	 */
	protected ?string $shippingFieldAttributeGroup = 'shipping';

	/**
	 * @param   Form                 $form                        Form
	 * @param   MVCFactoryInterface  $radicalMartMvcFactory       RadicalMart MVC factory
	 * @param   Registry             $radicalMartRussianPostParams       RadicalMart Russian Post component params
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		Form $form,
		private readonly MVCFactoryInterface $radicalMartMvcFactory,
		private readonly Registry $radicalMartRussianPostParams
	)
	{
		$this->form = $form;
	}

	/**
	 * @return Form
	 *
	 * @since 1.0.0
	 */
	protected function getForm(): Form
	{
		return $this->form;
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

	}

	/**
	 * Apply a shipping method field setting to a checkout field.
	 *
	 * @param   string  $fieldName    Checkout field name.
	 * @param   string  $defaultState Default field state.
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function prepareConfiguredCheckoutField(string $fieldName, string $defaultState): void
	{
		$shipping = $this->getShipping();

		if (!is_object($shipping)
			|| !isset($shipping->params)
			|| !method_exists($shipping->params, 'get')
		)
		{
			throw new Exception('Shipping field settings are unavailable', 500);
		}

		$fieldState = (string) $shipping->params->get('field_' . $fieldName, $defaultState);

		if ($fieldState === 'hidden')
		{
			if (!$this->getForm()->removeField($fieldName, $this->shippingFieldAttributeGroup))
			{
				throw new Exception('Failed to remove ' . $fieldName . ' field', 500);
			}

			return;
		}

		$required = ($fieldState === 'required') ? 'true' : 'false';

		if (!$this->getForm()->setFieldAttribute(
			$fieldName,
			'required',
			$required,
			$this->shippingFieldAttributeGroup
		))
		{
			throw new Exception('Failed to prepare ' . $fieldName . ' field', 500);
		}
	}

	/**
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function checkPlugin(): bool
	{
		/** @var ShippingMethodTable $table */
		$table = $this->radicalMartMvcFactory->createTable('ShippingMethod', 'Administrator');

		$shippingId = $this->getShippingId();

		if (!$table->load($shippingId))
		{
			return false;
		}

		if ($table->plugin !== 'wishboxrussianpost')
		{
			return false;
		}

		return true;
	}

	/**
	 * @return integer
	 *
	 * @since 1.0.0
	 */
	abstract protected function getShippingId(): int;

	/**
	 *
	 * @return boolean|null
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function isTariffModeToPoint(): ?bool
	{
		if (method_exists($this, 'getTariffCode'))
		{
			$tariffCode = $this->getTariffCode();

			if ($tariffCode)
			{
				return false;
			}
			else
			{
				return null;
			}
		}

		$shipping = $this->getShipping();

		if (is_object($shipping)
			&& isset($shipping->params)
			&& method_exists($shipping->params, 'get')
		)
		{
			$tariffMode = (string) $shipping->params->get('tariff_mode', '');
			[, $to]    = array_pad(explode('-', $tariffMode, 2), 2, null);

			return $to === 'С';
		}

		return false;
	}

	/**
	 * @return object|array|null
	 *
	 * @since 1.0.0
	 */
	protected function getShipping(): object|array|null
	{
		return null;
	}
}
