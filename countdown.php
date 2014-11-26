<?php
/**
* @version 3.0 Alpha 	+Joomla Agency
* @for Joomla 3.3+ 	+A small Plugin to Calculate the Days for a specific Date
* @package JooAg Countdown 	+Usage:
* @author Joomla Agentur - http://www.joomla-agentur.de 	+Put somewhere in your Content or Module following Snippet - {countdown}02 December 2014 18:45:00{/countdown}
* @copyright Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
* @license GNU General Public License version 2 or later; 	
* @description A small Plugin to Calculate the Days for a specific Date 	
* @usage Put somewhere in your Content or Module following Snippet - {countdown}02 December 2014 18:45:00{/countdown} 	
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class PlgContentCountdown extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Performance Check
		if ( JString::strpos( $article->text, '{countdown}' ) === false ) {
			return true;
		}

		// Regular expression
		$regex = "#{countdown}(.*?){/countdown}#s";

		// Replacement of {countdown}xxx{/countdown}
		$article->text = preg_replace_callback( $regex, array(&$this,'plgCountdownDTN_replacer'), $article->text );
		$article->introtext = preg_replace_callback( $regex, array(&$this,'plgCountdownDTN_replacer'), $article->text );
	}
	
	protected function plgCountdownDTN_replacer (&$matches) 
	{
		
		$date = $matches[1];
		$doc = JFactory::getDocument();	
		JHtml::_('jquery.framework');
		$doc->addScript(JURI::root().'plugins/content/countdown/countdown.js');
		$doc->addStyleDeclaration($this->params->def('countdowncss'));	
		$randomId = rand();
		
		$javascript = 'jQuery(document).ready(function () {';
		$javascript .= 'jQuery("#countdown'.$randomId.'").countdown({';
		$javascript .= 'date: "'.$date.'",';
		$javascript .= 'format: "on"';
		$javascript .= '});';
		$javascript .= '});';
		$doc->addScriptDeclaration($javascript);
		
		$htmlOutput = '
		<span class="days">00</span> <span class="timeRefDays">days</span> 
		<span class="hours">00</span> <span class="timeRefHours">hours</span> 
		<span class="minutes">00</span> <span class="timeRefMinutes">minutes</span> 
		<span class="seconds">00</span> <span class="timeRefSeconds">seconds</span>';
		
		if (!empty ($this->params->def('countdownhtml'))){$htmlOutput = $this->params->def('countdownhtml');}
		$countdown = '<div class="countdown" id="countdown'.$randomId.'">'.$htmlOutput.'</div>';
		
		return $countdown;
	}
}
