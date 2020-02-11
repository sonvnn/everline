<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
    <width>400</width>
    <height>150</height>
    <selectors type="json">
    {
        "{closeButton}"     : "[data-close-button]",
        "{submitButton}"    : "[data-submit-button]"
    }
    </selectors>
    <bindings type="javascript">
    {
        "{closeButton} click": function()
        {
            this.parent.close();
        }
    }
    </bindings>
    <title><?php echo JText::_('COM_EASYSOCIAL_EVENTS_DIALOG_DELETE_RECURRING_EVENT_TITLE'); ?></title>
    <content>
        <p><?php echo JText::_('COM_EASYSOCIAL_EVENTS_DIALOG_DELETE_RECURRING_EVENT_CONTENT');?></p>
    </content>
    <loading><?php echo JText::_('COM_EASYSOCIAL_EVENTS_DIALOG_DELETE_RECURRING_EVENT_DELETING'); ?></loading>
    <buttons>
        <button data-close-button type="button" class="btn btn-es-default btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CLOSE_BUTTON'); ?></button>
        <button data-submit-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_('COM_EASYSOCIAL_DELETE_RECURRING_EVENT_BUTTON'); ?></button>
    </buttons>
</dialog>

