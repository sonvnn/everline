<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<label>
	<?php echo JText::_($label);?>
</label>
<i class="fa fa-question-circle label__help-icon pull-right" 
	data-ed-provide="popover"
	data-title="<?php echo JText::_($label);?>" 
	data-content="<?php echo JText::_($desc);?>"
	data-placement="top"
	data-html="true"
	>
</i>