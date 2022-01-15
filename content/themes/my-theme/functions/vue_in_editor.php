<?php
  
/**
 * Vue in WordPress Editor
 *
 * Add support for using Vue components and directives in the WordPress TinyMCE Editor
 */

function override_mce_options($initArray) {
	$opts = '*[*]';
	$initArray['valid_elements'] = $opts;
	$initArray['extended_valid_elements'] = $opts;
	return $initArray;
}
add_filter('tiny_mce_before_init', 'override_mce_options');