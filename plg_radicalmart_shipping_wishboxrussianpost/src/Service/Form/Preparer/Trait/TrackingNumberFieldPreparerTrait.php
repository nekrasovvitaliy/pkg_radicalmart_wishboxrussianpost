<?php
/**
 * @copyright (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license GNU General Public License version 2 or later
 */
namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;

/**
 * @method Form getForm()
 *
 * @since 1.0.0
 */
trait TrackingNumberFieldPreparerTrait
{
	/**
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	protected function prepareTrackingNumber(): void
	{
		$componentParams = ComponentHelper::getParams('com_wishboxradicalmartrussianpost');

		$trackingNumberReadonlyAttribute = (bool) $componentParams->get('tracking_number_readonly_attribute', '1');

		$form = $this->getForm();

		if (!$form->setFieldAttribute('tracking_number', 'readonly', $trackingNumberReadonlyAttribute, $this->shippingFieldAttributeGroup))
		{
			throw new Exception('failed to setFieldAttribute', 500);
		}
	}
}
