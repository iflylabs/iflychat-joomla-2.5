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


class IflychatController extends JControllerLegacy
{


    public function auth()
    {
        // Set view
        JRequest::setVar('view', 'auth');
        parent::display();
    }
    public function mobileAuth() {

        // Set view
        JRequest::setVar('view', 'mobileauth');
        parent::display();

    }
}