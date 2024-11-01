<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

set_transient( 'chilla_detected_action_hooks', '', -1 );

$options_to_remove = array(
	''
);
foreach ($options_to_remove as $option) {
	if ( get_option($option) ) {
        delete_option($option);
    }
}