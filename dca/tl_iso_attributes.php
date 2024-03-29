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
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_iso_attributes']['palettes']['imageselect']					= '{attribute_legend},name,field_name,type,legend,variant_option,customer_defined;{description_legend:hide},description;{options_legend},imageSource,imgSize,sortBy,options;{config_legend},mandatory,multiple;{search_filters_legend},fe_filter,fe_sorting,be_filter';
$GLOBALS['TL_DCA']['tl_iso_attributes']['palettes']['imageselectvariant_option']	= '{attribute_legend},name,field_name,type,legend,variant_option;{description_legend:hide},description;{options_legend},imageSource,imgSize,sortBy,options';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['imageSource'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['imageSource'],
	'inputType'				=> 'fileTree',
	'eval'					=> array('fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['imgSize'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['imgSize'],
	'inputType'				=> 'imageSize',
	'options'				=> array('crop', 'proportional', 'box'),
	'reference'				=> &$GLOBALS['TL_LANG']['MSC'],
	'eval'					=> array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['sortBy'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['sortBy'],
	'inputType'				=> 'select',
	'options'				=> array('name_asc', 'name_desc', 'date_asc', 'date_desc', 'meta'),
	'reference'				=> &$GLOBALS['TL_LANG']['tl_iso_attributes'],
	'eval'					=> array('tl_class'=>'w50')
);

