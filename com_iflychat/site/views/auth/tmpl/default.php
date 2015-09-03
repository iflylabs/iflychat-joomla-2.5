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


error_reporting(2);
jimport('joomla.application.module.helper');
require(JPATH_ROOT .'/modules/mod_iflychat/helper.php');
$helpObj = new modIflychatHelper();
$comp = JComponentHelper::getParams('com_iflychat'); //getting component details
$module = JModuleHelper::getModule('mod_iflychat');
$variable_get = $comp->get('iflychat_ext_d_i', '');
define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
define('IFLYCHAT_EXTERNAL_PORT', '80');
define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
define('IFLYCHAT_EXTERNAL_A_PORT', '443');

//Assigning values to data array
$user = JFactory::getUser(); //getting user details
$api_key = $comp->get('iflychat_external_api_key');
$image_path = JURI::base().'modules/'.$module->module;

if($user->get('isRoot')) {
    $role = "admin";
}
else {
    if(!empty($user->groups)) {
        $role = $user->groups;
    }
    else {
        $role = "normal";
    }
}

if($comp->get('iflychat_theme', 1) == 1) {
    $iflychat_theme = 'light';
}
else {
    $iflychat_theme = 'dark';
}
//data array
$data = array(
    'uname' => ($user->id)?$user->name:iflychat_get_current_guest_name(),
    'uid' => ($user->id)?$user->id:'0-'.iflychat_get_current_guest_id(),
    'api_key' => $api_key,
    'image_path' => JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images',
    'isLog' => TRUE,
    'role' => $role,
    'whichTheme' => 'blue',
    'enableStatus' => TRUE,
    'validState' => array('available','offline','busy','idle')
);
//Send roles in data array if role is admin
if($role == 'admin'){
    $data['allRoles'] = $helpObj->roleArr();
}
//Get friend's id
if(file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {
    if($comp->get('iflychat_enable_friends', 1) == 2){
        require_once( JPATH_ROOT .'/components/com_community/libraries/core.php' );
        $data['rel'] = '1';
        $final_list = array();
        $final_list['1']['name'] = 'friend';
        $final_list['1']['plural'] = 'friends';
        $final_list['1']['valid_uids'] = CFactory::getUser($user->id)->getFriendIds();
        $data['valid_uids'] = $final_list;
    }
}
if($comp->get('iflychat_user_picture', '1') == 1) {
    $data['up'] = iflychat_get_user_pic_url();
}
$data['upl'] = iflychat_get_user_profile_url();
if(!($data['rel']==1 && $user->id==0)){
try {
//HTTP request
    jimport('joomla.http');
    if(file_exists(JPATH_ROOT .'/libraries/joomla/http/factory.php')) {
        $http = JHttpFactory::getHttp();
        $response = $http->post(IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/p/', $data);
    } else{
        $http = new JHttp();
        $response = $http->post(IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/p/', $data);
    }
    $resObj = json_decode($response->body);

    if(isset($resObj->_i) && ($resObj->_i!=$variable_get)) {

        $comp->set('iflychat_ext_d_i', $resObj->_i);
        // Get a new database query instance
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Build the query
        $query->update('#__extensions AS a');
        $query->set('a.params = ' . $db->quote((string)$comp));
        $query->where('a.element = "com_iflychat"');
// Execute the query
        $db->setQuery($query);
        $db->query();
    }
    $json = json_decode($response->body, TRUE);
    $json['name'] = ($user->id)?$user->name:iflychat_get_current_guest_name();
    $json['uid'] = ($user->id)?$user->id:'0-'.iflychat_get_current_guest_id();
    if($comp->get('iflychat_user_picture', '1') == 1) {
        $json['up'] = iflychat_get_user_pic_url();
    }
    $json['upl'] = iflychat_get_user_profile_url();
// Get the document object.
    $document =&JFactory::getDocument();
// Set the MIME type for JSON output.
    $document->setMimeEncoding('application/json');
    print json_encode($json);
}

catch(Exception $e)
{
    $var = array (
        'uname' => ($user->id)?$user->name:iflychat_get_current_guest_name(),
        'uid' =>($user->id)?$user->id:'0-'.iflychat_get_current_guest_id()
    );
    $document =& JFactory::getDocument();
    $document->setMimeEncoding('application/json');
    print_r(json_encode($var));
}
}
else {
    print_r('Access Denied');
}
function iflychat_get_random_name() {
    $module = JModuleHelper::getModule('mod_iflychat');
    $path = JURI::base().'modules/'.$module->module . "/guest_names/iflychat_guest_random_names.txt";
    $f_contents = file($path);
    $line = trim($f_contents[rand(0, count($f_contents) - 1)]);
    return $line;
}
function iflychat_get_current_guest_name() {

    $comp = JComponentHelper::getParams('com_iflychat');
    if(isset($_SESSION) && isset($_SESSION['iflychat_guest_name'])) {
        setrawcookie('iflychat_guest_name', rawurlencode($_SESSION['iflychat_guest_name']), time()+60*60*24*365);
    }
    else if(isset($_COOKIE) && isset($_COOKIE['iflychat_guest_name']) && isset($_COOKIE['iflychat_guest_session'])&& ($_COOKIE['iflychat_guest_session']==iflychat_compute_guest_session(iflychat_get_current_guest_id()))) {
        $_SESSION['iflychat_guest_name'] = check_plain($_COOKIE['iflychat_guest_name']);
    }
    else {
        if($comp->get('iflychat_anon_use_name', 1)==1) {
            $_SESSION['iflychat_guest_name'] = check_plain($comp->get('iflychat_anon_prefix', 'Guest') . ' ' . iflychat_get_random_name());
        }
        else {
            $_SESSION['iflychat_guest_name'] = check_plain($comp->get('iflychat_anon_prefix', 'Guest') . time());
        }
        setrawcookie('iflychat_guest_name', rawurlencode($_SESSION['iflychat_guest_name']), time()+60*60*24*365);
    }
    return $_SESSION['iflychat_guest_name'];
}

function iflychat_get_current_guest_id() {
    if(isset($_SESSION) && isset($_SESSION['iflychat_guest_id'])) {
        setrawcookie('iflychat_guest_id', rawurlencode($_SESSION['iflychat_guest_id']), time()+60*60*24*365);
        setrawcookie('iflychat_guest_session', rawurlencode($_SESSION['iflychat_guest_session']), time()+60*60*24*365);
    }
    else if(isset($_COOKIE) && isset($_COOKIE['iflychat_guest_id']) && isset($_COOKIE['iflychat_guest_session']) && ($_COOKIE['iflychat_guest_session']==iflychat_compute_guest_session($_COOKIE['iflychat_guest_id']))) {
        $_SESSION['iflychat_guest_id'] = check_plain($_COOKIE['iflychat_guest_id']);
        $_SESSION['iflychat_guest_session'] = check_plain($_COOKIE['iflychat_guest_session']);
    }
    else {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $iflychatId = time();
        for ($i = 0; $i < 5; $i++) {
            $iflychatId .= $characters[rand(0, strlen($characters) - 1)];
        }
        $_SESSION['iflychat_guest_id'] = $iflychatId;
        $_SESSION['iflychat_guest_session'] = iflychat_compute_guest_session($_SESSION['iflychat_guest_id']);
        setrawcookie('iflychat_guest_id', rawurlencode($_SESSION['iflychat_guest_id']), time()+60*60*24*365);
        setrawcookie('iflychat_guest_session', rawurlencode($_SESSION['iflychat_guest_session']), time()+60*60*24*365);
    }
    return $_SESSION['iflychat_guest_id'];
}

function iflychat_compute_guest_session($id) {
    $comp = JComponentHelper::getParams('com_iflychat');
    return md5(substr($comp->get('iflychat_external_api_key', NULL), 0, 5) . $id);
}

function check_plain($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function iflychat_get_user_pic_url() {
    $url = '';

    if(file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {
        require_once( JPATH_ROOT .'/components/com_community/libraries/core.php' );
        $user = JFactory::getUser()->id;
        $xml = simplexml_load_file(JPATH_SITE .'/administrator/components/com_community/community.xml');
        $version = (string)$xml->version;
		
         if ($version[0] == '3'){
	     $url =CFactory::getUser($user)->getAvatar();
	 return $url;
   }
         elseif($version[0] == '4'){
	      $url =JURI::base().CFactory::getUser($user)->getAvatar();
	  
          $var = explode("/", $url);
	      $result = sizeof($var)-2;
	      if($var[$result]== 'assets'){
	      $host = JURI::getInstance()->getHost();
	      $url = $var[0].'//'.$host.CFactory::getUser($user)->getAvatar();
	  return $url;
	} 
	      else {
	  return $url;		
   }
   }
   }
    else{
		  if( ( file_exists( JPATH_SITE . '/libraries/CBLib/CBLib/Core/CBLib.php' ))||(file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php'))){
	       require_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php');
		   $user = JFactory::getUser();
	       if(!$user->id == '0'){
	       $cbUser = & CBuser::getInstance( $user->id);
	       $cbUser->_getCbTabs(false);
		   //print_r($cbUser->getField( 'avatar', null, 'csv', 'none', 'list'));
		   return $cbUser->getField( 'avatar', null, 'csv', 'none', 'list');
		   
		 }
		 else {
		      $module = JModuleHelper::getModule('mod_iflychat');
              $comp = JComponentHelper::getParams('com_iflychat');
              if($comp->get('iflychat_theme', 1) == 1){
                 $iflychat_theme = 'light';
        }
              else {
                 $iflychat_theme = 'dark';
        }
		$url = JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images/default_avatar.png';
        $pos = strpos($url, ':');
        if($pos !== false) {
           $url = substr($url, $pos+1);
        }
		return $url;
 }  		  
}  
       else{
		$module = JModuleHelper::getModule('mod_iflychat');
        $comp = JComponentHelper::getParams('com_iflychat');
        if($comp->get('iflychat_theme', 1) == 1) {
            $iflychat_theme = 'light';
        }
        else {
            $iflychat_theme = 'dark';
        }
		$url = JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images/default_avatar.png';
        $pos = strpos($url, ':');
        if($pos !== false) {
            $url = substr($url, $pos+1);
        }
		//print_r($url);
	   return $url;
		
}
}  
}

function iflychat_get_user_profile_url() {
    if(file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {

        require_once( JPATH_ROOT .'/components/com_community/libraries/core.php' );
        $user = JFactory::getUser()->id;
        $host = JURI::getInstance()->getHost();
        $url = JURI::base();
        $var = explode(":", $url);
        $profileLink = CUrlHelper::userLink($user);
        $upl = $var[0].'://'.$host.$profileLink;
        return $upl;

    }
	
	else {
        if( ( file_exists( JPATH_SITE . '/libraries/CBLib/CBLib/Core/CBLib.php' ))||(file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php'))){		
        require_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php');
		 global $_CB_framework;
		 $user = JFactory::getUser()->id;
		 if($user !== '0'){
		 $id = $_CB_framework->displayedUser($user);
		 
		 $cbUser =& CBuser::getInstance( $user ); 
		 $profilLink = cbSef('index.php?option=com_comprofiler&amp;task=userProfile&amp;user='.$id . getCBprofileItemid(), true);
		 //print_r($profilLink);
		 return $profilLink;
		 }
        }
		 else{
         $upl = 'javascript:void()';
         return $upl;
}
}
}


