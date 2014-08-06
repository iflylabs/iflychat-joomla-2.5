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

class modIflychatHelper
{



    //my setting array function
    public function iflychat_initial_go() {

        //$variable_get = '4';
        $compParams = JComponentHelper::getParams('com_iflychat');
        $module = JModuleHelper::getModule('mod_iflychat');
        $variable_get = $compParams->get('iflychat_ext_d_i', '3');

        define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_PORT', '80');
        define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_A_PORT', '443');



        $lang = JFactory::getLanguage();
        $lang->load('com_iflychat');



        if($compParams->get('iflychat_theme', 1) == 1) {
            $iflychat_theme = 'light';
        }
        else {
            $iflychat_theme = 'dark';
        }
        $my_settings = array(

            //  'username' => ($user->id)?$user->name:'default', //$a_name
            // 'uid' =>  $user->id,    //($user->id)?$user->id:'0-'._drupalchat_get_sid(),
            'current_timestamp' => time(),
            'polling_method' => '',
            'pollUrl' => '',
            'sendUrl' => '',
            'statusUrl' => '',
            'status' => '',
            'goOnline' => JText::_('MOD_GO_ONLINE'),
            'goIdle' => JText::_('MOD_GO_IDLE'),
            'newMessage' => JText::_('MOD_NEW_CHAT_MESSAGE'),
            'images' => JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images/',
            'sound' => JURI::base().'modules/'.$module->module . '/swf/sound.swf',
            'soundFile' => JURI::base().'modules/'.$module->module . '/wav/notification.mp3',
            'noUsers' => '',
            'smileyURL' => JURI::base().'modules/'.$module->module . '/smileys/very_emotional_emoticons-png/png-32x32/',
            'addUrl' => '',
            'notificationSound' => $compParams->get('iflychat_notification_sound', 1),
            'exurl' => JURI::base().'index.php?option=com_iflychat&view=auth&format=raw',
            'mobileWebUrl' => JURI::base().'index.php?option=com_iflychat&view=mobileauth&format=raw',
            'soffurl' => '',
            'chat_type' => $compParams->get('iflychat_show_admin_list', 2),
            'guestPrefix' => $compParams->get('iflychat_anon_prefix', 'Guest') . " ",
            'changeurl' => '',
            'allowSmileys' => $compParams->get('iflychat_enable_smiley', 1),
            'admin' => $this->iflychat_check_chat_admin()?'1':'0'

        );
        if($this->iflychat_check_chat_admin()) {
            $my_settings['arole'] = $this->roleArr();
        }

        $my_settings['iup'] = $compParams->get('iflychat_user_picture', 1);
        if($compParams->get('iflychat_user_picture', 1) == 1) {
            //$my_settings['up'] = drupalchat_return_pic_url();
            $my_settings['default_up'] = JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images/default_avatar.png';
            $my_settings['default_cr'] = JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images/default_room.png';
            $my_settings['default_team'] = JURI::base().'modules/'.$module->module . '/themes/' . $iflychat_theme . '/images/default_team.png';
        }
        $u = JFactory::getURI();
        if($u->isSSL()) {
            $my_settings['external_host'] = IFLYCHAT_EXTERNAL_A_HOST;
            $my_settings['external_port'] = IFLYCHAT_EXTERNAL_A_PORT;
            $my_settings['external_a_host'] = IFLYCHAT_EXTERNAL_A_HOST;
            $my_settings['external_a_port'] = IFLYCHAT_EXTERNAL_A_PORT;
        }
        else {
            $my_settings['external_host'] = IFLYCHAT_EXTERNAL_HOST;
            $my_settings['external_port'] = IFLYCHAT_EXTERNAL_PORT;
            $my_settings['external_a_host'] = IFLYCHAT_EXTERNAL_HOST;
            $my_settings['external_a_port'] = IFLYCHAT_EXTERNAL_PORT;
        }



        $my_settings['text_currently_offline'] = JText::_('MOD_USER_CURRENTLY_OFFLINE');
        $my_settings['text_is_typing'] = JText::_('MOD_USER_IS_TYPING');
        $my_settings['text_close'] = JText::_('MOD_CLOSE');
        $my_settings['text_minimize'] = JText::_('MOD_MINIMIZE');
        $my_settings['text_mute'] = JText::_('MOD_CLICK_TO_MUTE');
        $my_settings['text_unmute'] = JText::_('MOD_CLICK_TO_UNMUTE');
        $my_settings['text_available'] = JText::_('MOD_AVAILABLE');
        $my_settings['text_idle'] = JText::_('MOD_IDLE');
        $my_settings['text_busy'] = JText::_('MOD_BUSY');
        $my_settings['text_offline'] = JText::_('MOD_OFFLINE');
        $my_settings['text_lmm'] = JText::_('MOD_LOAD_MORE_MESSAGES');
        $my_settings['text_nmm'] = JText::_('MOD_NO_MORE_MESSAGES');
        $my_settings['text_clear_room'] = JText::_('MOD_CLEAR_ALL_MESSAGES');
        $my_settings['msg_p'] = JText::_('MOD_TYPE_AND_PRESS_ENTER');

        if(self::iflychat_check_chat_admin()) {
            $my_settings['text_ban'] = JText::_('MOD_BAN');
            $my_settings['text_ban_ip'] = JText::_('MOD_BAN_IP');
            $my_settings['text_kick'] = JText::_('MOD_KICK');
            $my_settings['text_ban_window_title'] = JText::_('MOD_BANNED_USERS');
            $my_settings['text_ban_window_default'] = JText::_('MOD_NO_BAN');
            $my_settings['text_ban_window_loading'] = JText::_('MOD_LOADING');
            $my_settings['text_manage_rooms'] = JText::_('MOD_MANAGE_ROOMS');
            $my_settings['text_unban'] = JText::_('MOD_UNBAN');
            $my_settings['text_unban_ip'] = JText::_('MOD_UNBAN_IP');
        }

        if($compParams->get('drupalchat_show_admin_list', 2) == 1) {
            $my_settings['text_support_chat_init_label'] = $compParams->get('iflychat_support_chat_init_label', 'Chat with us');
            $my_settings['text_support_chat_box_header'] = $compParams->get('iflychat_support_chat_box_header', 'Support');
            $my_settings['text_support_chat_box_company_name'] = $compParams->get('iflychat_support_chat_box_company_name', 'Support Team');
            $my_settings['text_support_chat_box_company_tagline'] = $compParams->get('iflychat_support_chat_box_company_tagline', 'Ask us anything...');
            $my_settings['text_support_chat_auto_greet_enable'] = $compParams->get('iflychat_support_chat_auto_greet_enable', 1);
            $my_settings['text_support_chat_auto_greet_message'] = $compParams->get('iflychat_support_chat_auto_greet_message', 'Hi there! Welcome to our website. Let us know if you have any query!');
            $my_settings['text_support_chat_auto_greet_time'] = $compParams->get('iflychat_support_chat_auto_greet_time', 1);
            $my_settings['text_support_chat_offline_message_label'] = $compParams->get('iflychat_support_chat_offline_message_label', 'Message');
            $my_settings['text_support_chat_offline_message_contact'] = $compParams->get('iflychat_support_chat_offline_message_contact', 'Contact Details');
            $my_settings['text_support_chat_offline_message_send_button'] = $compParams->get('iflychat_support_chat_offline_message_send_button', 'Send Message');
            $my_settings['text_support_chat_offline_message_desc'] = $compParams->get('iflychat_support_chat_offline_message_desc', 'Hello there. We are currently offline. Please leave us a message. Thanks.');
            $my_settings['text_support_chat_init_label_off'] = $compParams->get('iflychat_support_chat_init_label_off', 'Leave Message');
        }
        $my_settings['open_chatlist_default'] = ($compParams->get('iflychat_minimize_chat_user_list', 2)==2)?'1':'2';


        $my_settings['useStopWordList'] = $compParams->get('iflychat_use_stop_word_list', '1');
        $my_settings['blockHL'] = $compParams->get('iflychat_stop_links', '1');
        $my_settings['allowAnonHL'] = $compParams->get('iflychat_allow_anon_links', '1');
        $my_settings['renderImageInline'] = ($compParams->get('iflychat_allow_render_images', '1')=='1')?'1':'2';
        $my_settings['searchBar'] = ($compParams->get('iflychat_enable_search_bar', '1')=='1')?'1':'2';
        $my_settings['text_search_bar'] = JText::_('MOD_TYPE_HERE_TO_SEARCH');
        return $my_settings;
    }
    // Run script in body
    public function get_html_code(){

        $r = 'Drupal={};Drupal.settings={};Drupal.settings.drupalchat=' . json_encode($this->iflychat_initial_go())  . ';</script>';

        return $r;

    }
    public function iflychat_check_chat_admin(){

        if(JFactory::getUser()->get('isRoot')){
            return TRUE;
        }else
            return FALSE;
    }

    public function roleArr(){

        $db = JFactory::getDBO();
        $db->setQuery($db->getQuery(true)
                ->select(array('id','title'))
                ->from("#__usergroups")
        );
        $groups=$db->loadObjectList();
        $roleArr = array();
        for($i=0;$i<sizeof($groups);$i++){
            $roleArr +=  array($groups[$i]->id => $groups[$i]->title);
        }
        return $roleArr;
    }
}