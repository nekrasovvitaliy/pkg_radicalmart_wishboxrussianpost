<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\Preparer;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Form\AbstractFormPreparer;
use Joomla\Registry\Registry;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 1.0.0
 */
class ShippingMethodFormPreparer extends AbstractFormPreparer
{
	/**
	 * @var object|array Data
	 *
	 * @since 1.0.0
	 */
	protected object|array $data;

	/**
	 * @param   Form                 $form                           Form
	 * @param   MVCFactoryInterface  $radicalMartMvcFactory          RadicalMart MVC factory
	 * @param   Registry             $radicalMartRussianPostParams  RadicalMart Russian Post component params
	 * @param   array|object         $data                           Data
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct(
		Form $form,
		MVCFactoryInterface $radicalMartMvcFactory,
		Registry $radicalMartRussianPostParams,
		array|object $data
	)
	{
		parent::__construct($form, $radicalMartMvcFactory, $radicalMartRussianPostParams);

		$this->data = $data;
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
	}

	/**
	 * @return integer
	 *
	 * @since 1.0.0
	 */
	protected function getShippingId(): int
	{
		return (int) (
			is_array($this->data)
				? ($this->data['id'] ?? 0)
				: ($this->data->id ?? 0)
		);
	}
}
