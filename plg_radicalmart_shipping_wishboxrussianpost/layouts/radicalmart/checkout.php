<?php
/**
 * @copyright   (c) 2013-2026 Nekrasov Vitaliy <nekrasov_vitaliy@list.ru>
 * @license         GNU General Public License version 2 or later;
 */

use Joomla\CMS\Form\Form;

defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  Form   $form     Form object.
 * @var  object $item     Checkout object.
 * @var  object $shipping Checkout shipping method object.
 *
 */

if (empty($shipping))
{
    return false;
}
?>
<div class="row g-2">
    <?php if ($shipping->params->get('field_recipient_name', 'required') !== 'hidden'): ?>
        <div class="col-12">
            <?php echo $form->renderField('recipient_name', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <?php if ($shipping->params->get('field_index_to', 'required') !== 'hidden'): ?>
        <div class="col-12">
            <?php echo $form->renderField('index_to', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <?php if ($shipping->params->get('field_region_to', 'required') !== 'hidden'): ?>
        <div class="col-12">
            <?php echo $form->renderField('region_to', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <?php if ($shipping->params->get('field_place_to', 'required') !== 'hidden'): ?>
        <div class="col-12">
            <?php echo $form->renderField('place_to', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <?php if ($shipping->params->get('field_street_to', 'required') !== 'hidden'): ?>
        <div class="col-12 col-sm-6">
            <?php echo $form->renderField('street_to', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <?php if ($shipping->params->get('field_house_to', 'required') !== 'hidden'): ?>
        <div class="col-12 col-sm-3">
            <?php echo $form->renderField('house_to', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <?php if ($shipping->params->get('field_room_to', 'not_required') !== 'hidden'): ?>
        <div class="col-12 col-sm-3">
            <?php echo $form->renderField('room_to', 'shipping'); ?>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <?php echo $form->renderField('mail_type', 'shipping'); ?>
    </div>

    <div class="col-12">
        <?php echo $form->renderField('error_message', 'shipping'); ?>
    </div>
</div>
