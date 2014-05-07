<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
//Add helper file
require_once dirname(__FILE__).DS.'helper.php';
//Adding custom field for running script in backend
JFormHelper::addFieldPath(JURI::base().'modules/mod_iflychat/models/fields');

$items = modIflychatHelper::get_html_code();


$document =  JFactory::getDocument();
require(JModuleHelper::getLayoutPath('mod_iflychat'));

