<?php


/**
 * @package iFlyChat
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
defined('_JEXEC') or die;


// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_iflychat')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// import Joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('iflychat');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();