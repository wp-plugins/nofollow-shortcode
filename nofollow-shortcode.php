<?php
/*
Plugin Name: Nofollow Shortcode
Plugin URI: http://shinraholdings.com/plugins/nofollow-shortcode
Description: The simplest way to insert 'rel=nofollow' links into your posts or pages.
Version: 1.1
Author: bitacre
Author URI: http://shinraholdings.com

Basic Shortcode Format: [nofollow url="http://link-url.com"]link text[/nofollow]
Shorter Shortcodes: [nofol] & [nofo]
Also supports 'target=' & 'title=' attributes, but both are optional and may be omitted.
	
License: GPLv2 
	Copyright 2012 Shinra Web Holdings (plugins@shinraholdings.com)

*/

function set_plugin_meta_nofollow_shortcode($links, $file) { // define additional plugin meta links
	$plugin = plugin_basename(__FILE__); // '/nofollow-shortcode/nofollow-shortcode.php' by default
    if ($file == $plugin) { // if called for THIS plugin then:
		$newlinks=array('<a href="http://shinraholdings.com/plugins/nofollow-shortcode/help">Help Page</a>',); // array of links to add
		return array_merge( $links, $newlinks ); // merge new links into existing $links
	}
return $links; // return the $links (merged or otherwise)
}

function trim_url_nofollow($untrimmed) { // sanatize url inputs
	$trimmed = trim(str_replace("http://","",$untrimmed));
	return $trimmed;
}

function nofollow_link( $atts, $content=NULL ) {
	extract( shortcode_atts( array( 'url'=>NULL, 'href'=>NULL, 'title'=>NULL, 'target'=>'_blank' ), $atts ) );
	//error checking
	$errormsg = '<!--Nofollow shortcode insertion failed. The correct syntax is [nofollow url="http://link-url.com"]link text[/nofollow]. Reason for failure: ';
	$iserror = 0;
	if(is_null($url)) { $iserror=1; $errormsg = $errormsg . "No url specified. "; }
	if(is_null($content)) { $iserror=1; $errormsg = $errormsg . "No link text specified. ";}
	if($iserror) { 
		$errormsg = $errormsg . "-->"; 
		return $errormsg; 
	}
	
	// sanatize input
	$cleanurl = ( !empty($url) ? trim_url_nofollow($url) : trim_url_nofollow($href) );
		
	// create link code
	if(is_null($title)) $title_chunk = NULL;
	else $title_chunk = ' title="' . $title . '"';
	$link_code='<a href="http://' . $cleanurl . '" target="' . $target . '" rel="nofollow"' . $title_chunk . '>' . $content . '</a>';
	return $link_code;
}
add_filter( 'plugin_row_meta', 'set_plugin_meta_nofollow_shortcode', 10, 2 ); // add meta links to plugin's section on 'plugins' page (10=priority, 2=num of args)
add_shortcode( 'nofollow', 'nofollow_link' );
add_shortcode( 'nofol', 'nofollow_link' );
add_shortcode( 'nofo', 'nofollow_link' );
?>