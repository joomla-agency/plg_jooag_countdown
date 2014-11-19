<?php
/**
 * @version		3.0 Alpha
 * @for			Joomla 3.3+
 * @package		JooAg Countdown
 * @author		Joomla Agentur - http://www.joomla-agentur.de
 * @copyright		Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
 * @license     	GNU General Public License version 2 or later;
 * @description		A small Plugin to Calculate the Days for a specific Date
 * @usage		Put somewhere in your Content or Module following Snippet - {countdown}02 December 2014 18:45:00{/countdown}
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgContentCountdown extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	public function onContentPrepare($context, &$article, &$params, $page=0 )
	{
		// Performance Check
		if ( JString::strpos( $article->text, 'countdown' ) === false ) {
			return true;
		}
	
		// Regular expression for the plugin
		$regex = "#{countdown}(.*?){/countdown}#s";

		// Replacement of {countdown}xxx{/countdown}
		$article->text = preg_replace_callback( $regex, array(&$this,'plgCountdownDTN_replacer'), $article->text );
		return true;
	}
	
	protected function plgCountdownDTN_replacer ( &$matches) 
	{
		$date = $matches[1];
		
		//Javascript Part
		JHtml::_('jquery.framework');
		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root().'plugins/content/countdown/countdown.js');
		$doc->addStyleDeclaration( "#countdown span {font-size: 20px;font-weight:600;color:red;}" );	
		
		$javascript = 'jQuery(document).ready(function () {';
		$javascript .= 'jQuery("#countdown").countdown({';
		$javascript .= 'date: "'.$date.'",';
		$javascript .= 'format: "on"';
		$javascript .= '});';
		$javascript .= '});';
		$doc->addScriptDeclaration($javascript);
		
		//Output
		$days = '<div id="countdown">
					<span class="days">00</span>
					<span class="timeRefDays">Tage</span> -
					<span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
				</div>';
		
		/* Backup
		$days = '<div id="countdown">
					<p class="days">00</p>
					<p class="timeRefDays">Tage</p>
					<p class="hours">00</p>
					<p class="timeRefHours">Stunden</p>
					<p class="minutes">00</p>
					<p class="timeRefMinutes">Minuten</p>
					<p class="seconds">00</p>
					<p class="timeRefSeconds">Sekunden</p>
				</div>';
		*/
		
		return $days;
	}
	

}
