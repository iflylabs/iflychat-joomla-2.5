<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class IflyChatViewIflyChat extends JView
{

    function display($tpl = null)
    {

        {
            $form		= $this->get('Form');
            $component	= $this->get('Component');

            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode("\n", $errors));
                return false;
            }

            // Bind the form to the data.
            if ($form && $component->params) {
                $form->bind($component->params);
            }
$document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-generic {background-image: url(../media/com_iflychat/images/iflychat-48x48.png);}');

            $this->assignRef('form',		$form);
            $this->assignRef('component',	$component);
        $this->addToolbar();
            $this->document->setTitle(JText::_('JGLOBAL_EDIT_PREFERENCES'));

            parent::display($tpl);
            JRequest::setVar('hidemainmenu', true);
        }

    }
    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('COM_IFLYCHAT_MANAGER'));
        JToolBarHelper::apply('iflychat.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('iflychat.save', 'JTOOLBAR_SAVE');

        JToolBarHelper::cancel('iflychat.cancel', 'JTOOLBAR_CANCEL');

    }

}