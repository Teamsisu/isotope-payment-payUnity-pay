<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Isotope eCommerce Workgroup 2009-2012
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Table tl_iso_payment_modules
 */

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['palettes']['__selector__'][] = 'payunity_sandbox';
$GLOBALS['TL_DCA']['tl_iso_payment_modules']['palettes']['payunitypay'] = '{type_legend},name,label,type;{note_legend:hide},note;{config_legend},new_order_status,minimum_total,maximum_total,countries,shipping_modules,product_types;{live_gateway_legend},payunity_live_user_id,payunity_live_user_pwd,payunity_live_sender_id,payunity_live_channel_id;{test_gateway_legend:hide},payunity_sandbox;{price_legend:hide},price,tax_class;{redirect_legend},payunity_redirect_success,payunity_redirect_checkout;{expert_legend:hide},guests,protected;{enabled_legend},enabled';

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['subpalettes']['payunity_sandbox'] = 'payunity_test_user_id,payunity_test_user_pwd,payunity_test_sender_id,payunity_test_channel_id';


$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_sandbox'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_sandbox'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange' => true)
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_live_sender_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_sender_id'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_live_channel_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_channel_id'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_live_user_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_user_id'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_live_user_pwd'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_user_pwd'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_test_sender_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_sender_id'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_test_channel_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_channel_id'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_test_user_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_user_id'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_test_user_pwd'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_user_pwd'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'alnum', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_redirect_success'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_redirect_success'],
    'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr', 'mandatory' => true),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['payunity_redirect_checkout'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payunity_redirect_checkout'],
    'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr', 'mandatory' => true),
);