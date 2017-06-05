<?php

/*
	This script is executed when the (inactive) plugin is deleted through the admin backend.
	
	It removes the plugin settings from the option table and all tag groups. It does not change the term_group field of the taxonomies.
	
	last change: version 0.19.2
*/

if( defined('WP_UNINSTALL_PLUGIN') ) {

    /*
     * Delete settings only if requested
     */
    
    $tag_group_reset_when_uninstall = get_option( 'tag_group_reset_when_uninstall', false );
    
    if ($tag_group_reset_when_uninstall) {
    
	delete_option( 'tag_group_taxonomy' );

	delete_option( 'tag_group_labels' );

	delete_option( 'tag_group_ids' );

	delete_option( 'tag_group_theme' );

	delete_option( 'max_tag_group_id' );

	delete_option( 'tag_group_mouseover' );

	delete_option( 'tag_group_collapsible' );

	delete_option( 'tag_group_enqueue_jquery' );

	delete_option( 'tag_group_shortcode_widget' );

	delete_option( 'tag_group_show_filter' );
        
        delete_option( 'tag_group_show_filter_tags' );
        
        delete_option( 'tag_group_html_description' );
        
        // Should be last:
        delete_option( 'tag_group_reset_when_uninstall' );
        
    }

}