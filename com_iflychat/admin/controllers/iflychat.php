<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

defined('_JEXEC') or die;
$componentParams = JComponentHelper::getParams('com_iflychat');
$variable_get= $componentParams->get('iflychat_ext_d_i', '');
define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
define('IFLYCHAT_EXTERNAL_PORT', '80');
define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
define('IFLYCHAT_EXTERNAL_A_PORT', '443');
class IflychatControllerIflychat extends JControllerLegacy {




    function __construct($config = array())
    {
        parent::__construct($config);

        // Map the apply task to the save method.
        $this->registerTask('apply', 'save');
    }


    function save()
    {


        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Set FTP credentials, if given.
        JClientHelper::setCredentialsFromRequest('ftp');

        // Initialise variables.
        $app	= JFactory::getApplication();
        $model	= $this->getModel('iflychat');
        $form	= $model->getForm();
        $data	= JRequest::getVar('jform', array(), 'post', 'array');
        $id		= JRequest::getInt('id');
        $option	= 'com_iflychat';

        //post request
        $pdata = array(
            'api_key' => $data['iflychat_external_api_key'],
            'enable_chatroom' => $data['iflychat_enable_chatroom'],
            'theme' => ($data['iflychat_theme'] == 1)?'light':'dark',
            'notify_sound' => $data['iflychat_notification_sound'],
            'smileys' => $data['iflychat_enable_smileys'],
            'log_chat' => $data['iflychat_log_messages'],
            'chat_topbar_color' => $data['iflychat_chat_topbar_color'],
            'chat_topbar_text_color' => $data['iflychat_chat_topbar_text_color'],
            'font_color' => $data['iflychat_font_color'],
            'chat_list_header' => $data['iflychat_chat_list_header'],
            'public_chatroom_header' => $data['iflychat_public_chatroom_header'],
            'rel' => $data['iflychat_rel'],
            'version' => 'Joomla-2.5-1.0.0',
            'show_admin_list' => $data['iflychat_show_admin_list'],
            'clear' => $data['iflychat_allow_single_message_delete'],
            'delmessage' => $data['iflychat_allow_clear_room_history'],
            'ufc' => $data['iflychat_allow_user_font_color'],
            'guest_prefix' => ($data['iflychat_anon_prefix'] . " "),
            'enable_guest_change_name' => $data['iflychat_anon_change_name'],
            'use_stop_word_list' => $data['iflychat_use_stop_word_list'],
            'stop_word_list' => $data['iflychat_stop_word_list'],
        );
        
        
        jimport('joomla.http');
        $http = JHttpFactory::getHttp();
        $response = $http->post(IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/z/', $pdata);




        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.admin', $option))
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        // Validate the posted data.
        $return = $model->validate($form, $data);

        // Check for validation errors.
        if ($return === false) {
            // Get the validation messages.
            $errors	= $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_iflychat.config.global.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=com_iflychat&view=iflychat&layout=edit', false));
            return false;
        }

        // Attempt to save the configuration.
        $data	= array(
            'params'	=> $return,
            'id'		=> $id,
            'option'	=> $option
        );

        $return = $model->save($data);

        // Check the return value.
        if ($return === false)
        {
            // Save the data in the session.
            $app->setUserState('com_config.config.global.data', $data);

            // Save failed, go back to the screen and display a notice.
            $message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_iflychat&view=component&component='.$option.'&tmpl=component', $message, 'error');
            return false;
        }

        // Set the redirect based on the task.
        switch ($this->getTask())
        {
            case 'apply':
                $message = JText::_('COM_IFLYCHAT_SAVE_SUCCESS');

                $this->setRedirect('index.php?option=com_iflychat&view=iflychat&layout=edit', $message);
                break;

            case 'save':
            default:
                $this->setRedirect('index.php');
                break;
        }

        return true;
    }


    function cancel()
    {
        $this->setRedirect('index.php');
    }



}
