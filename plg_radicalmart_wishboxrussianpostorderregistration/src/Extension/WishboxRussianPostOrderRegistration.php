<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

namespace Joomla\Plugin\RadicalMart\WishboxRussianPostOrderRegistration\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;
use WishboxRussianPostLibrary\Service\ContainerAwareInterface;
use WishboxRussianPostLibrary\Service\ContainerAwareTrait;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration\RadicalMartOrderRegistrationServiceAwareInterface;
use WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\OrderRegistration\RadicalMartOrderRegistrationServiceAwareTrait;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since        1.0.0
 *
 * @noinspection PhpUnused
 */
final class WishboxRussianPostOrderRegistration extends CMSPlugin implements
	ContainerAwareInterface,
	RadicalMartOrderRegistrationServiceAwareInterface,
	SubscriberInterface
{
	use ContainerAwareTrait;
	use RadicalMartOrderRegistrationServiceAwareTrait;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var boolean
	 *
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Enable on RadicalMart
	 *
	 * @var  boolean
	 *
	 * @since  1.0.0
	 */
	public bool $radicalmart = true;

	/**
	 * Enable on RadicalMartExpress
	 *
	 * @var  boolean
	 *
	 * @since        1.0.0
	 *
	 * @noinspection PhpUnused
	 */
	public bool $radicalmart_express = true; // phpcs:ignore

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onRadicalMartAfterOrderSave' => 'onAfterOrderSave',
		];
	}

	/**
	 * @param   string    $context   Context
	 * @param   array     $formData  Form data
	 * @param   array     $data      Data
	 * @param   stdClass  $order     Order
	 * @param   boolean   $isNew     Is new
	 *
	 * @return void
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 *
	 * @since        1.0.0
	 *
	 * @noinspection PhpUnused
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function onAfterOrderSave(
		string   $context,
		array    $formData,
		array    $data,
		stdClass $order,
		bool     $isNew
	): void
	{
		$this->getRadicalMartOrderRegistrationService()
			->register($order);
	}
}
