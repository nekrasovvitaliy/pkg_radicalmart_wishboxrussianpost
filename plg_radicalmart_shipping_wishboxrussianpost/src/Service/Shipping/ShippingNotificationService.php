<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later
 */

namespace Joomla\Plugin\RadicalMartShipping\WishboxRussianPost\Service\Shipping;

use Exception;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\WishboxRussianPost\Administrator\Table\CityTable;
use Joomla\Component\WishboxRussianPost\Administrator\Table\OfficeTable;
use Joomla\Component\WishboxRussianPost\Administrator\Table\TariffTable;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 1.0.0
 */
readonly class ShippingNotificationService
{
	/**
	 * Class constructor.
	 *
	 * @param   MVCFactoryInterface  $mvcFactory  MVC factory
	 *
	 * @since 1.0.0
	 */
	public function __construct(private MVCFactoryInterface $mvcFactory)
	{
	}

	/**
	 * @param   array  $data  Shipping form data
	 *
	 * @return array
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function prepare(array $data): array
	{
		$result = [];

		if (empty($data))
		{
			return $result;
		}

		$addressString = $this->addressToString($data);

		if (!empty($addressString))
		{
			$result['PLG_RADICALMART_SHIPPING_WISHBOXRUSSIANPOST_SHIPPING_ADDRESS'] = $addressString;
		}

		if (!empty($data['comment']))
		{
			$result['PLG_RADICALMART_SHIPPING_WISHBOXRUSSIANPOST_SHIPPING_COMMENT'] = $data['comment'];
		}

		if (!empty($data['date']))
		{
			$result['PLG_RADICALMART_SHIPPING_WISHBOXRUSSIANPOST_SHIPPING_DATE'] = new Date($data['date'])->format(
				Text::_('DATE_FORMAT_LC4')
			);
		}

		if (!empty($data['note']))
		{
			$result['PLG_RADICALMART_SHIPPING_WISHBOXRUSSIANPOST_SHIPPING_NOTE'] = $data['note'];
		}

		return $result;
	}

	/**
	 * @param   array  $data  Address data
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	private function addressToString(array $data = []): string
	{
		$address = [];

		if (!empty($data['zip']))
		{
			$address[] = $data['zip'];
		}

		if (!empty($data['country']))
		{
			$address[] = $data['country'];
		}

		if (!empty($data['region']))
		{
			$address[] = $data['region'];
		}

		if (!empty($data['city']))
		{
			$address[] = $data['city'];
		}

		$mb = function_exists('mb_strtolower');

		foreach (['street', 'house', 'building', 'entrance', 'floor', 'apartment'] as $key)
		{
			if (!empty($data[$key]))
			{
				$title     = Text::_('PLG_RADICALMART_SHIPPING_WISHBOXRUSSIANPOST_FIELD_' . $key);
				$title     = ($mb) ? mb_strtolower($title) : strtolower($title);
				$address[] = $title . ' ' . $data[$key];
			}
		}

		return (!empty($address)) ? implode(', ', $address) : '';
	}
}
