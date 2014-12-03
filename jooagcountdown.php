<?php
/**
 * @version 	3.x.0 Beta +Joomla Agency
 * @for 	Joomla 3.3+ 
 * @usage 	A small Plugin to Calculate the Days for a specific Date
 * @package 	JooAg Countdown +Usage:
 * @author 	Joomla Agentur - http://www.joomla-agentur.de +Put somewhere in your Content or Module following Snippet - {countdown}02 December 2014 18:45:00{/countdown}
 * @copyright 	Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
 * @license 	GNU General Public License version 2 or later;
 * @description A small Plugin to Calculate the Days for a specific Date
 * @usage 	Put somewhere in your Content or Module following Snippet - {countdown}02 December 2014 18:45:00{/countdown}
 * @thanksto 	Thanks to Guido De Gobbis from http://joomtools.de for his great contributions!
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PlgContentJooagcountdown extends JPlugin
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
		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework');
		$doc->addScript(JURI::root().'plugins/content/countdown/countdown.js');
		if($this->params->get('countdowncss')){
			$doc->addStyleDeclaration($this->params->get('countdowncss'));
		}
		
		// Regular expression
		$regex = "#{countdown}(.*?){/countdown}#s";
		
		// Replacement of {countdown}xxx{/countdown}
		$article->text = preg_replace_callback( $regex, array(&$this,'plgCountdownDTN_replacer'), $article->text );
		
		if(!empty($article->introtext)){
			$article->introtext = preg_replace_callback( $regex, array(&$this,'plgCountdownDTN_replacer'), $article->introtext );
		}	
	}
	
	protected function plgCountdownDTN_replacer (&$matches)
	{
		$date = $matches[1];
		$_htmlOutput = '
		<span class="days">00</span> <span class="timeRefDays">days</span>
		<span class="hours">00</span> <span class="timeRefHours">hours</span>
		<span class="minutes">00</span> <span class="timeRefMinutes">minutes</span>
		<span class="seconds">00</span> <span class="timeRefSeconds">seconds</span>';
		$htmlOutput = $this->params->get('countdownhtml', $_htmlOutput);
		$countdown = '<div class="countdown" data-countdown="'.$date.'">'.$htmlOutput.'</div>';
		return $countdown;
	}
}
