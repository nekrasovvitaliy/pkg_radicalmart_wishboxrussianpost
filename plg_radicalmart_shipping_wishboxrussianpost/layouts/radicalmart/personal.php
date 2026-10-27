<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license     GNU General Public License version 2 or later;
 */

use Joomla\CMS\Form\Form;

defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  Form       $form       Form object.
 * @var  object     $item       Customer object.
 * @var  object     $shipping   Checkout shipping method object.
 * @var  array      $fieldsets  Checkout shipping method object.
 * @var  string     $group      Fields group target.
 *
 */

if (empty($shipping))
{
	return false;
}
?>
<div id="personal_shipping_method_<?php echo $shipping->id; ?>" class="options-form form-horizontal">
	<div class="row g-2">
		<?php if ($shipping->params->get('field_recipient_name', 'required') !== 'hidden'): ?>
			<div class="col-12"><?php echo $form->renderField('recipient_name', $group); ?></div>
		<?php endif; ?>
		<?php if ($shipping->params->get('field_index_to', 'required') !== 'hidden'): ?>
			<div class="col-12"><?php echo $form->renderField('index_to', $group); ?></div>
		<?php endif; ?>
		<?php if ($shipping->params->get('field_region_to', 'required') !== 'hidden'): ?>
			<div class="col-12"><?php echo $form->renderField('region_to', $group); ?></div>
		<?php endif; ?>
		<?php if ($shipping->params->get('field_place_to', 'required') !== 'hidden'): ?>
			<div class="col-12"><?php echo $form->renderField('place_to', $group); ?></div>
		<?php endif; ?>
		<?php if ($shipping->params->get('field_street_to', 'required') !== 'hidden'): ?>
			<div class="col-12 col-sm-8"><?php echo $form->renderField('street_to', $group); ?></div>
		<?php endif; ?>
		<?php if ($shipping->params->get('field_house_to', 'required') !== 'hidden'): ?>
			<div class="col-12 col-sm-2"><?php echo $form->renderField('house_to', $group); ?></div>
		<?php endif; ?>
		<?php if ($shipping->params->get('field_room_to', 'not_required') !== 'hidden'): ?>
			<div class="col-12 col-sm-2"><?php echo $form->renderField('room_to', $group); ?></div>
		<?php endif; ?>
	</div>
</div>
