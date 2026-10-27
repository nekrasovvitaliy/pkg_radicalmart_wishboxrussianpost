<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer\Trait;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Calculator\Adapter\RadicalMartShippingCalculatorAdapter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use WishboxRussianPostLibrary\Service\Calculator\CalculatorService;

/**
 * @method Form getForm()
 * @method CalculatorService getCalculatorService()
 * @method DispatcherInterface getDispatcher()
 *
 * @since 1.0.0
 */
trait ErrorMessageFieldPreparerTrait
{
	/**
	 * @return void
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 *
	 * @since 1.0.0
	 */
	protected function prepareErrorMessageField(): void
	{
		try
		{
			$calculatorAdapterService = new RadicalMartShippingCalculatorAdapter(
				$this->shipping,
				$this->formData,
				$this->products,
				$this->getDispatcher()
			);

			$calculatorService = $this->getCalculatorService();

			$calculatorService->getShippingTariffs($calculatorAdapterService);
		}
		catch (Exception $e)
		{
			$this->setMessage(
				(string) $e
			);
		}
	}

	/**
	 * @param   string  $message  Message
	 * @param   string  $class    Class
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	private function setMessage(string $message, string $class = 'alert alert-warning'): void
	{
		if (!$this->getForm()->setFieldAttribute(
			'error_message',
			'description',
			$message,
			$this->shippingFieldAttributeGroup
		))
		{
			throw new Exception('Failed to set field attribute');
		}

		if (!$this->getForm()->setFieldAttribute(
			'error_message',
			'type',
			'note',
			$this->shippingFieldAttributeGroup
		))
		{
			throw new Exception('Failed to set field attribute');
		}

		if (!$this->getForm()->setFieldAttribute(
			'error_message',
			'class',
			$class,
			$this->shippingFieldAttributeGroup
		))
		{
			throw new Exception('Failed to set field attribute');
		}
	}
}
