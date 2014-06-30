<?php
/**
 * @package iFlyChat
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

// no direct access
defined('_JEXEC') or die;

$comp = JComponentHelper::getParams('com_iflychat'); //getting component details
$variable_get = $comp->get('iflychat_ext_d_i', '');

define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
define('IFLYCHAT_EXTERNAL_PORT', '80');
define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
define('IFLYCHAT_EXTERNAL_A_PORT', '443');
$u =& JFactory::getURI();

$data = array('settings' => array());
$data['settings']['authUrl'] = JURI::base().'index.php?option=com_iflychat&view=auth&format=raw';
$data['settings']['host'] = (($u->isSSL())?(IFLYCHAT_EXTERNAL_A_HOST):(IFLYCHAT_EXTERNAL_HOST));
$data['settings']['port'] = (($u->isSSL())?(IFLYCHAT_EXTERNAL_A_PORT):(IFLYCHAT_EXTERNAL_PORT));


//HTTP request
    jimport('joomla.http');

    $http = JHttpFactory::getHttp();
    $response = $http->post(IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/m/v1/app/', $data);
if(($response->code == 200)) {
    $o = $response->body;
}
else {
    print $response->code;
}
print_r($o);
exit;
