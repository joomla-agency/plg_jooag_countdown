<?php
/**
Joomla Agency
A small Plugin to Calculate the Days for a specific Date
Usage:
Put somewhere in your Content or Module following Snippet - {countdown}02 December 2014 18:45:00{/countdown}
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgContentCountdown extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	public function onAfterDispatch() {
		$doc = JFactory::getDocument();	
		$doc->addScript(JURI::root().'plugins/content/countdown/countdown.js');
		$doc->addStyleDeclaration( "#countdown p {display: inline-block;  font-size: 20px;font-weight:600;}" );	
		return;
	}
	
	public function onContentPrepare($context, &$article, &$params, $page=0 )
	{
		// simple performance check to determine whether plugin should process further
		
		if ( JString::strpos( $article->text, 'countdown' ) === false ) {
			return true;
		}

		// define the regular expression for the plugin
		$regexDTN = "#{countdown}(.*?){/countdown}#s";

		// perform the replacement
		$article->text = preg_replace_callback( $regexDTN, array(&$this,'plgCountdownDTN_replacer'), $article->text );
		return true;
	}
	

	
	protected function plgCountdownDTN_replacer ( &$matches) 
	{
		$date = $matches[1];
		
		$doc = JFactory::getDocument();	
		$javascript = 'jQuery(document).ready(function () {';
		$javascript .= 'jQuery("#countdown").countdown({';
		$javascript .= 'date: "'.$date.'",';
		$javascript .= 'format: "on"';
		$javascript .= '});';
		$javascript .= '});';
		$doc->addScriptDeclaration($javascript);

		$days = '<div id="countdown">
					<p class="days">00</p>
					<p class="timeRefDays">Tage</p> -
					<p class="hours">00</p>:
					<p class="minutes">00</p>:
					<p class="seconds">00</p>
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