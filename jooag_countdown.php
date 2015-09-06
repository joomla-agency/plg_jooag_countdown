<?php
/**
 * @package 	JooAg Countdown
 * @version 	3.x.0 Beta
 * @for 	Joomla 3.3+ 
 * @author 	Joomla Agentur - http://www.joomla-agentur.de
 * @copyright 	Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
 * @license 	GNU General Public License version 2 or later;
 * @description A small Plugin to Calculate the Days for a specific Date
 * @thanksto 	Thanks to Guido De Gobbis from http://joomtools.de for his great contributions!
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PlgContentJooag_countdown extends JPlugin
{
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Performance Check
		if ( JString::strpos( $article->text, '{countdown}' ) === false ) {
			return true;
		}
		
		// Regular expression
		$regex = "#{countdown}(.*?){/countdown}#s";
		
		// Replacement of {countdown}xxx{/countdown}
		$article->text = preg_replace_callback($regex, array(&$this,'plgCountdownOutput'), $article->text);
		
		if(!empty($article->introtext)){
			$article->introtext = preg_replace_callback($regex, array(&$this,'plgCountdownOutput'), $article->introtext);
		}	
	}
	
	protected function plgCountdownOutput (&$matches)
	{
		$date = $matches[1];
		$htmlOutput = '<div id="getting-started"></div>';
		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework');
		$doc->addScript(JURI::root().'plugins/content/jooag_countdown/jquery.countdown.js');
		// http://hilios.github.io/jQuery.countdown/documentation.html
		$doc->addScriptDeclaration('jQuery(document).ready(function() {jQuery("#getting-started").countdown("'.$date.'", function(event) {jQuery(this).text(event.strftime("%D Tag%!D:e; %H:%M:%S"));});});');

		return $htmlOutput;
	}
}
