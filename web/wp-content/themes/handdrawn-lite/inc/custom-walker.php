<?php

class Handdrawn_Off_Canvas_Menu extends Walker_Nav_Menu
{   
	/*
	 * Off canvas left menu
	 */
	 
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"menu vertical  nested\">\n";
	}
}

class Handdrawn_Dropdown_Menu extends Walker_Nav_Menu
{   
	/*
	 * Classic dropdown menu
	 */
	 
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"menu submenu  bd-ter vertical\" data-submenu>\n";
	}
}?>
