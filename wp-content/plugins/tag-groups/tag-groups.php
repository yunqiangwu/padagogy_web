<?php
/*
  Plugin Name: Tag Groups
  Plugin URI: http://www.christoph-amthor.de/software/tag-groups/
  Description: Assign tags to groups and display them in a tabbed tag cloud
  Author: Christoph Amthor
  Version: 0.22
  Author URI: http://www.christoph-amthor.de
  License: GNU GENERAL PUBLIC LICENSE, Version 3
  Text Domain: tag-groups
 */

define( "TAG_GROUPS_VERSION", "0.22" );

define( "TAG_GROUPS_BUILT_IN_THEMES", "ui-gray,ui-lightness,ui-darkness" );

define( "TAG_GROUPS_STANDARD_THEME", "ui-gray" );

add_action( 'init', 'tg_widget_hook' );

add_action( 'admin_init', 'tg_admin_init' );

add_action( 'admin_menu', 'tg_register_tag_label_page' );

add_shortcode( 'tag_groups_cloud', 'tag_groups_cloud' );

add_shortcode( 'tag_groups_accordion', 'tag_groups_accordion' );

add_action( 'wp_enqueue_scripts', 'tg_add_js_css' );

add_action( 'admin_enqueue_scripts', 'tg_add_admin_js_css' );

add_action( 'plugins_loaded', 'tg_plugin_init' );


/**
 * Loading text domain for internationalization
 */
function tg_plugin_init()
{

    $plugin_dir = basename( dirname( __FILE__ ) );

    load_plugin_textdomain( 'tag-groups', false, $plugin_dir . '/languages/' );

}


/**
 * Hooks for the frontend
 */
function tg_widget_hook()
{

    $tag_group_shortcode_widget = get_option( 'tag_group_shortcode_widget', 0 );

    if ( $tag_group_shortcode_widget ) {
        add_filter( 'widget_text', 'do_shortcode' );
    }

}


/**
 * Initial settings after calling the plugin
 * Effective only for admin backend
 */
function tg_admin_init()
{

    $show_filter_tags = get_option( 'tag_group_show_filter_tags', true );

    if ( $show_filter_tags && !session_id() ) {

        session_start();
    }

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( !is_array( $tag_group_taxonomy ) ) {

        $tag_group_taxonomy = array($tag_group_taxonomy);

        update_option( 'tag_group_taxonomy', $tag_group_taxonomy );
    }

    foreach ( $tag_group_taxonomy as $taxonomy ) {

        add_action( "{$taxonomy}_edit_form_fields", 'tg_tag_input_metabox' );

        add_action( "{$taxonomy}_add_form_fields", 'tg_create_new_tag' );

        add_filter( "manage_edit-{$taxonomy}_columns", 'tg_add_taxonomy_columns' );

        add_filter( "manage_{$taxonomy}_custom_column", 'tg_add_taxonomy_column_content', 10, 3 );
    }

    add_action( 'admin_notices', 'tg_bulk_admin_notices' );

    add_action( 'quick_edit_custom_box', 'tg_quick_edit_tag', 10, 3 );

    add_action( 'create_term', 'tg_update_edit_term_group' );

    add_action( 'edit_term', 'tg_update_edit_term_group' );

    add_action( 'load-edit-tags.php', 'tg_bulk_action' );

    $plugin = plugin_basename( __FILE__ );

    add_filter( "plugin_action_links_$plugin", 'tg_plugin_settings_link' );

    add_action( 'admin_footer-edit-tags.php', 'tg_quick_edit_javascript' );

    add_action( 'admin_footer-edit-tags.php', 'tg_bulk_admin_footer' );

    add_filter( 'tag_row_actions', 'tg_expand_quick_edit_link', 10, 2 );

    add_action( 'restrict_manage_posts', 'tg_add_filter' );

    add_filter( 'parse_query', 'tg_apply_filter' );

    tg_init();

}


/**
 * 
 * Modifies the query to retrieve tags for filtering in the backend.
 * 
 * @param array $pieces
 * @param array $taxonomies
 * @param array $args
 * @return array
 */
function tg_terms_clauses( $pieces, $taxonomies, $args )
{

    $show_filter_tags = get_option( 'tag_group_show_filter_tags', true );

    if ( $show_filter_tags ) {

        $group_id = $_SESSION['term-filter'];

        if ( $group_id > -1 ) {

            if ( !empty( $pieces['where'] ) ) {

                $pieces['where'] .= sprintf( " AND t.term_group = %d AND t.term_id > 1", $group_id );
            } else {

                $pieces['where'] = sprintf( "t.term_group = %d AND t.term_id > 1", $group_id );
            }
        }
    }

    return $pieces;

}


/**
 * Initialize values for first use
 */
function tg_init()
{
    /*
     * If it doesn't exist: create the default group with ID 0 that will only show up on tag pages as "unassigned".
     */

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;

    if ( (!isset( $tag_group_labels )) || (!isset( $tag_group_labels[0] )) || ($tag_group_labels[0] == '') ) {

        $tag_group_labels[0] = 'not assigned';

        $tag_group_ids[0] = 0;

        $number_of_tag_groups = 0;

        $max_tag_group_id = 0;

        $tag_group_taxonomy = array('post_tag');

        update_option( 'tag_group_labels', $tag_group_labels );

        update_option( 'tag_group_ids', $tag_group_ids );

        update_option( 'max_tag_group_id', $max_tag_group_id );

        update_option( 'tag_group_taxonomy', $tag_group_taxonomy );

        $tag_group_theme = get_option( 'tag_group_theme', TAG_GROUPS_STANDARD_THEME );

        if ( $tag_group_theme == '' ) {
            $tag_group_theme = TAG_GROUPS_STANDARD_THEME;
        }
    }

}


/**
 * Adds a bulk action menu to a term list page
 * credits http://www.foxrunsoftware.net
 * @return void
 */
function tg_bulk_admin_footer()
{

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $screen = get_current_screen();

    if ( is_object( $screen ) && (!in_array( $screen->taxonomy, $tag_group_taxonomy ) ) ) {
        return;
    }

    $show_filter_tags = get_option( 'tag_group_show_filter_tags', true );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $tag_group_labels = get_option( 'tag_group_labels', array() );


    /*
     * 	constructing the action menu
     */
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('<option>').val('assign').text('<?php _e( 'Assign to' ) ?>').appendTo("select[name='action']");
            jQuery('<option>').val('assign').text('<?php _e( 'Assign to' ) ?>').appendTo("select[name='action2']");

            var sel_top = jQuery("<select name='term-group-top'>").insertAfter("select[name='action']");
            var sel_bottom = jQuery("<select name='term-group-bottom'>").insertAfter("select[name='action2']");

    <?php for ( $i = 0; $i < count( $tag_group_labels ); $i++ ) : ?>
                sel_top.append(jQuery("<option>").attr("value", "<?php echo $tag_group_ids[$i] ?>").text("<?php echo $tag_group_labels[$i] ?>"));
                sel_bottom.append(jQuery("<option>").attr("value", "<?php echo $tag_group_ids[$i] ?>").text("<?php echo $tag_group_labels[$i] ?>"));
    <?php endfor; ?>

    <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'term_group' ) : ?>
                jQuery('th#term_group').addClass('sorted');
    <?php else: ?>
                jQuery('th#term_group').addClass('sortable');
    <?php endif; ?>
    <?php if ( isset( $_GET['order'] ) && $_GET['order'] == 'asc' ) : ?>
                jQuery('th#term_group').addClass('asc');
    <?php else: ?>
                jQuery('th#term_group').addClass('desc');
    <?php endif; ?>

            jQuery('[name="term-group-top"]').change(function () {
                jQuery('[name="action"]').val('assign');
                jQuery('[name="action2"]').val('assign');
                var selected = jQuery(this).val();
                jQuery('[name="term-group-bottom"]').val(selected);
            });

            jQuery('[name="term-group-bottom"]').change(function () {
                jQuery('[name="action"]').val('assign');
                jQuery('[name="action2"]').val('assign');
                var selected = jQuery(this).val();
                jQuery('[name="term-group-top"]').val(selected);
            });

    <?php
    /*
     * 	constructing the filter menu
     */
    if ( $show_filter_tags ) :

        if ( isset( $_SESSION['term-filter'] ) ) {
            $tag_filter = $_SESSION['term-filter'];
        } else {
            $tag_filter = -1;
        }
        ?>
                var sel_filter = jQuery("<select id='tag_filter' name='term-filter' style='margin-left: 20px;'>").insertAfter("select[name='term-group-top']");

                sel_filter.append(jQuery("<option>").attr("value", "-1").text("<?php _e( 'Filter off', 'tag-groups' ) ?>"));
        <?php for ( $i = 0; $i < count( $tag_group_labels ); $i++ ) : ?>
                    sel_filter.append(jQuery("<option>").attr("value", "<?php echo $tag_group_ids[$i] ?>").text("<?php echo $tag_group_labels[$i] ?>"));
        <?php endfor; ?>

                jQuery("#tag_filter option[value=<?php echo $tag_filter ?>]").prop('selected', true);

            });
        </script>
        <?php
    endif;

}


/**
 *
 * processing actions defined in tg_bulk_admin_footer()
 * credits http://www.foxrunsoftware.net
 * @global int $tg_update_edit_term_group_called
 * @return void
 */
function tg_bulk_action()
{


    global $tg_update_edit_term_group_called;


    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $screen = get_current_screen();

    $taxonomy = $screen->taxonomy;

    if ( is_object( $screen ) && (!in_array( $taxonomy, $tag_group_taxonomy ) ) ) {
        return;
    }

    $show_filter_tags = get_option( 'tag_group_show_filter_tags', true );

    if ( $show_filter_tags ) {

        /*
         * Processing the filter
         */
        if ( isset( $_REQUEST['term-filter'] ) ) {

            if ( $_REQUEST['term-filter'] == '-1' ) {
                unset( $_SESSION['term-filter'] );
            } else {
                $_SESSION['term-filter'] = (int) $_REQUEST['term-filter'];
            }
        }

        /*
         * If filter is set, make sure to mofify the query
         */
        if ( isset( $_SESSION['term-filter'] ) ) {
            add_action( 'terms_clauses', 'tg_terms_clauses', 10, 3 );
        }
    }

    $wp_list_table = _get_list_table( 'WP_Terms_List_Table' );

    $action = $wp_list_table->current_action();

    $allowed_actions = array("assign");

    if ( !in_array( $action, $allowed_actions ) ) {
        return;
    }

//	check_admin_referer('_wpnonce');



    if ( isset( $_REQUEST['delete_tags'] ) ) {
        $term_ids = $_REQUEST['delete_tags'];
    }
    if ( isset( $_REQUEST['term-group-top'] ) ) {
        $term_group = (int) $_REQUEST['term-group-top'];
    } else {
        return;
    }

    // this is based on wp-admin/edit.php
    $sendback = remove_query_arg( array('assigned', 'deleted'), wp_get_referer() );

    if ( !$sendback ) {
        $sendback = admin_url( "edit-tags.php?taxonomy=$taxonomy" );
    }

    if ( empty( $term_ids ) ) {

        $sendback = add_query_arg( array('number_assigned' => 0, 'group_id' => $term_group), $sendback );

        $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view'), $sendback );

        // escaping $sendback
        $sendback_escaped = esc_url_raw( $sendback );

        wp_redirect( $sendback_escaped );

        exit();
    }

    $pagenum = $wp_list_table->get_pagenum();

    $sendback = add_query_arg( 'paged', $pagenum, $sendback );

    $tg_update_edit_term_group_called = 1; // skip tg_update_edit_term_group()

    switch ( $action ) {
        case 'assign':

            $assigned = 0;

            foreach ( $term_ids as $term_id ) {

                wp_update_term( (int) $term_id, $taxonomy, array('term_group' => $term_group) );

                $assigned++;
            }

            $sendback = add_query_arg( array('number_assigned' => $assigned, 'group_id' => $term_group), $sendback );

            break;

        default:

            $sendback = add_query_arg( array('number_assigned' => 0, 'group_id' => $term_group), $sendback );

            break;
    }

    $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view'), $sendback );

    $sendback_escaped = esc_url_raw( $sendback );

    wp_redirect( $sendback_escaped );

    exit();

}


/**
 * Notifications about the results of bulk actions
 * @return void
 */
function tg_bulk_admin_notices()
{

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $screen = get_current_screen();

    $taxonomy = $screen->taxonomy;

    if ( is_object( $screen ) && (!in_array( $taxonomy, $tag_group_taxonomy ) ) ) {
        return;
    }

    if ( isset( $_REQUEST['number_assigned'] ) && (int) $_REQUEST['number_assigned'] && isset( $_REQUEST['group_id'] ) ) {

        if ( $_REQUEST['group_id'] == 0 ) {

            $message = _n( 'The term has been removed from all groups.', sprintf( '%d terms have been removed from all groups.', number_format_i18n( (int) $_REQUEST['number_assigned'] ) ), (int) $_REQUEST['number_assigned'] );
        } else {

            $i = array_search( $_REQUEST['group_id'], $tag_group_ids );

            $group_name = $tag_group_labels[$i];

            $message = _n( sprintf( 'The term has been assigned to the group %s.', '<i>' . $group_name . '</i>' ), sprintf( '%d terms have been assigned to the group %s.', number_format_i18n( (int) $_REQUEST['number_assigned'] ), '<i>' . $group_name . '</i>' ), (int) $_REQUEST['number_assigned'] );
        }

        echo "<div class=\"updated\"><p>{$message}</p></div>";
    }

}


/**
 * Adds Settings link to plugin list
 * 
 * @param array $links
 * @return array
 */
function tg_plugin_settings_link( $links )
{

    $settings_link = '<a href="options-general.php?page=tag-groups-settings">Settings</a>';

    array_unshift( $links, $settings_link );

    return $links;

}


/**
 * Adds css to backend
 */
function tg_add_admin_js_css()
{

    wp_register_style( 'tag-groups-css-backend', plugins_url( 'css/style.css', __FILE__ ) );

    wp_enqueue_style( 'tag-groups-css-backend' );

}


/**
 * adds js and css to frontend
 * 
 * @return void
 */
function tg_add_js_css()
{

    $theme = get_option( 'tag_group_theme', TAG_GROUPS_STANDARD_THEME );

    $default_themes = explode( ',', TAG_GROUPS_BUILT_IN_THEMES );

    $tag_group_enqueue_jquery = get_option( 'tag_group_enqueue_jquery', true );


    if ( $tag_group_enqueue_jquery ) {

        wp_enqueue_script( 'jquery' );

        wp_enqueue_script( 'jquery-ui-core' );

        wp_enqueue_script( 'jquery-ui-tabs' );

        wp_enqueue_script( 'jquery-ui-accordion' );
    }

    if ( $theme == '' ) {
        return;
    }

    wp_register_style( 'tag-groups-css-frontend-structure', plugins_url( 'css/jquery-ui.structure.min.css', __FILE__ ) );


    if ( in_array( $theme, $default_themes ) ) {

        wp_register_style( 'tag-groups-css-frontend-theme', plugins_url( 'css/' . $theme . '/jquery-ui.theme.min.css', __FILE__ ) );
    } else {

        /*
         * Load minimized css if available
         */
        if ( file_exists( WP_CONTENT_DIR . '/uploads/' . $theme . '/jquery-ui.theme.min.css' ) ) {

            wp_register_style( 'tag-groups-css-frontend-theme', get_bloginfo( 'wpurl' ) . '/wp-content/uploads/' . $theme . '/jquery-ui.theme.min.css' );
        } else if ( file_exists( WP_CONTENT_DIR . '/uploads/' . $theme . '/jquery-ui.theme.css' ) ) {

            wp_register_style( 'tag-groups-css-frontend-theme', get_bloginfo( 'wpurl' ) . '/wp-content/uploads/' . $theme . '/jquery-ui.theme.css' );
        } else {
            /*
             * Fallback: Is this a custom theme of an old version?
             */



            try {
                $dh = opendir( WP_CONTENT_DIR . '/uploads/' . $theme );
            } catch ( ErrorException $e ) {
                
            }

            if ( $dh ) {

                while ( false !== ($filename = readdir( $dh )) ) {

                    if ( preg_match( "/jquery-ui-\d+\.\d+\.\d+\.custom\.(min\.)?css/i", $filename ) ) {

                        wp_register_style( 'tag-groups-css-frontend-theme', get_bloginfo( 'wpurl' ) . '/wp-content/uploads/' . $theme . '/' . $filename );

                        break;
                    }
                }
            }
        }
    }

    wp_enqueue_style( 'tag-groups-css-frontend-structure' );

    wp_enqueue_style( 'tag-groups-css-frontend-theme' );

}


/**
 * Adds the submenus to the admin backend
 */
function tg_register_tag_label_page()
{

    add_submenu_page( 'edit.php', 'Tag Groups', 'Tag Groups', 'edit_pages', 'tag-groups', 'tg_group_administration' );

    add_options_page( 'Tag Groups', 'Tag Groups', 'manage_options', 'tag-groups-settings', 'tg_settings_page' );

}


/**
 * adds a custom column to the table of tags/terms
 * thanks to http://coderrr.com/add-columns-to-a-taxonomy-terms-table/
 * @global object $wp
 * @param array $columns
 * @return string
 */
function tg_add_taxonomy_columns( $columns )
{

    global $wp;

    $new_order = (isset( $_GET['order'] ) && $_GET['order'] == 'asc' && isset( $_GET['orderby'] ) && $_GET['orderby'] == 'term_group') ? 'desc' : 'asc';

    $screen = get_current_screen();
    $taxonomy = $screen->taxonomy;

    $link = add_query_arg( array('orderby' => 'term_group', 'order' => $new_order, 'taxonomy' => $taxonomy), admin_url( "edit-tags.php" . $wp->request ) );

    $link_escaped = esc_url( $link );

    $columns['term_group'] = '<a href="' . $link_escaped . '"><span>' . __( 'Tag Group', 'tag-groups' ) . '</span><span class="sorting-indicator"></span></a>';

    return $columns;

}


/**
 * adds data into custom column of the table for each row
 * thanks to http://coderrr.com/add-columns-to-a-taxonomy-terms-table/
 * @param type $a
 * @param type $b
 * @param type $term_id
 * @return string
 */
function tg_add_taxonomy_column_content( $a = '', $b = '', $term_id = 0 )
{

    if ( $b != 'term_group' ) {
        return $a;
    } // credits to Navarro (http://navarradas.com)

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    if ( isset( $_REQUEST['taxonomy'] ) ) {
        $taxonomy = sanitize_title( $_REQUEST['taxonomy'] );
    }

    $tag = get_term( $term_id, $taxonomy );

    if ( isset( $tag ) ) {
        $i = array_search( $tag->term_group, $tag_group_ids );

        return $tag_group_labels[$i];
    } else {
        return '';
    }

}


/**
 * Get the $_POSTed value after saving a tag/term and save it in the table
 * 
 * @global int $tg_update_edit_term_group_called
 * @param type $term_id
 * @return type
 */
function tg_update_edit_term_group( $term_id )
{

    // next lines to prevent infinite loops when the hook edit_term is called again from the function wp_update_term

    global $tg_update_edit_term_group_called;

    if ( $tg_update_edit_term_group_called > 0 ) {
        return;
    }

    $screen = get_current_screen();

    if ( !isset( $_POST['term-group'] ) && !isset( $_POST['term-group-option'] ) ) {
        return;
    }

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( is_object( $screen ) && (!in_array( $screen->taxonomy, $tag_group_taxonomy ) ) && (!isset( $_POST['new-tag-created'] )) ) {
        return;
    }

    $tg_update_edit_term_group_called++;

    if ( current_user_can( 'edit_posts' ) ) {

        $term_id = (int) $term_id;

        $term = array();


        if ( isset( $_POST['term-group-option'] ) ) {

            if ( !isset( $_POST['tag-groups-option-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-option-nonce'], 'tag-groups-option' ) ) {
                die( "Security check" );
            }

            $term['term_group'] = (int) $_POST['term-group-option'];
        } elseif ( isset( $_POST['term-group'] ) ) {

            if ( !isset( $_POST['assigned'] ) && (!isset( $_POST['tag-groups-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-nonce'], 'tag-groups' )) ) {
                die( "Security check" );
            }

            $term['term_group'] = (int) $_POST['term-group'];
        }

        if ( isset( $_POST['name'] ) && ($_POST['name'] != '') ) {
            $term['name'] = stripslashes( sanitize_text_field( $_POST['name'] ) );
        }

        if ( isset( $_POST['slug'] ) && ($_POST['slug'] != '') ) {
            $term['slug'] = sanitize_title( $_POST['slug'] );
        }

        if ( isset( $_POST['description'] ) && ($_POST['description'] != '') ) {

            if ( get_option( 'tag_group_html_description', false ) ) {
                $term['description'] = $_POST['description'];
            } else {
                $term['description'] = stripslashes( sanitize_text_field( $_POST['description'] ) );
            }
        }

        if ( isset( $_POST['tag-groups-taxonomy'] ) ) {

            $category = stripslashes( sanitize_title( $_POST['tag-groups-taxonomy'] ) );

            wp_update_term( $term_id, $category, $term );
        }
    } else {
        die( "Security check" );
    }

}


/**
 * adds JS function that sets the saved tag group for a given element when it's opened in quick edit
 * thanks to http://shibashake.com/wordpress-theme/expand-the-wordpress-quick-edit-menu
 * @return void
 */
function tg_quick_edit_javascript()
{

    $screen = get_current_screen();

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( !in_array( $screen->taxonomy, $tag_group_taxonomy ) ) {
        return;
    }
    ?>
    <script type="text/javascript">
        <!--
    function set_inline_tag_group_selected(tag_group_Selected, nonce) {
            inlineEditTax.revert();
            var tag_group_Input = document.getElementById('term-group-option');
            var nonceInput = document.getElementById('tag-groups-option-nonce');
            nonceInput.value = nonce;
            for (i = 0; i < tag_group_Input.options.length; i++) {
                if (tag_group_Input.options[i].value == tag_group_Selected) {
                    tag_group_Input.options[i].setAttribute("selected", "selected");
                } else {
                    tag_group_Input.options[i].removeAttribute("selected");
                }
            }
        }

        //-->
    </script>
    <?php

}


/**
 * modifies Quick Edit link to call JS when clicked
 * thanks to http://shibashake.com/wordpress-theme/expand-the-wordpress-quick-edit-menu
 * @param array $actions
 * @param object $tag
 * @return array
 */
function tg_expand_quick_edit_link( $actions, $tag )
{

    $screen = get_current_screen();

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( is_object( $screen ) && (!in_array( $screen->taxonomy, $tag_group_taxonomy ) ) ) {
        return $actions;
    }

//    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $tag_group_id = $tag->term_group;

    $nonce = wp_create_nonce( 'tag-groups-option' );

    $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';

    $actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline', 'tag-groups' ) ) . '" ';

    $actions['inline hide-if-no-js'] .= " onclick=\"set_inline_tag_group_selected('{$tag_group_id}', '{$nonce}')\">";

    $actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit', 'tag-groups' );

    $actions['inline hide-if-no-js'] .= '</a>';

    return $actions;

}


/**
 * Create the html to assign tags to tag groups directly in tag table ('quick edit')
 * @return type
 */
function tg_quick_edit_tag()
{

    global $tg_tg_quick_edit_tag_called;

    if ( $tg_tg_quick_edit_tag_called ) {
        return;
    }

    $tg_tg_quick_edit_tag_called = true;

    $screen = get_current_screen();

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( !in_array( $screen->taxonomy, $tag_group_taxonomy ) ) {
        return;
    }

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;
    ?>

    <fieldset><div class="inline-edit-col">

            <label><span class="title"><?php _e( 'Group', 'tag-groups' ) ?></span><span class="input-text-wrap">

                    <select id="term-group-option" name="term-group-option" class="ptitle">

                        <option value="0" ><?php _e( 'not assigned', 'tag-groups' ) ?></option>

                        <?php for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) : ?>

                            <option value="<?php echo $tag_group_ids[$i]; ?>" ><?php echo $tag_group_labels[$i]; ?></option>

                        <?php endfor; ?>

                    </select>

                    <input type="hidden" name="tag-groups-option-nonce" id="tag-groups-option-nonce" value="" />

                    <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo $screen->taxonomy; ?>" />

                </span></label>

        </div></fieldset>
    <?php

}


/**
 * Create the html to assign tags to tag groups upon new tag creation (left of the table)
 * @param type $tag
 */
function tg_create_new_tag( $tag )
{

    $screen = get_current_screen();

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;
    ?>

    <div class="form-field"><label for="term-group"><?php _e( 'Tag Group', 'tag-groups' ) ?></label>

        <select id="term-group" name="term-group">
            <option value="0" selected ><?php _e( 'not assigned', 'tag-groups' ) ?></option>

            <?php for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) : ?>

                <option value="<?php echo $tag_group_ids[$i]; ?>"><?php echo $tag_group_labels[$i]; ?></option>

            <?php endfor; ?>

        </select>		
        <input type="hidden" name="tag-groups-nonce" id="tag-groups-nonce" value="<?php echo wp_create_nonce( 'tag-groups' ) ?>" />
        <input type="hidden" name="new-tag-created" id="new-tag-created" value="1" />
        <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo $screen->taxonomy; ?>" />
    </div>

    <?php

}


/**
 * Create the html to add tags to tag groups on single tag view (after clicking tag for editing)
 * @param type $tag
 */
function tg_tag_input_metabox( $tag )
{
    $screen = get_current_screen();

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_widget"><?php _e( 'Tag group', 'tag-groups' ) ?></label></th>
        <td>
            <select id="term-group" name="term-group">
                <option value="0" <?php
                if ( $tag->term_group == 0 ) {
                    echo 'selected';
                }
                ?> ><?php _e( 'not assigned', 'tag-groups' ) ?></option>

                <?php for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) : ?>

                    <option value="<?php echo $tag_group_ids[$i]; ?>"

                            <?php
                            if ( $tag->term_group == $tag_group_ids[$i] ) {
                                echo 'selected';
                            }
                            ?> ><?php echo $tag_group_labels[$i]; ?></option>

                <?php endfor; ?>

            </select>
            <input type="hidden" name="tag-groups-nonce" id="tag-groups-nonce" value="<?php echo wp_create_nonce( 'tag-groups' ) ?>" />
            <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo $screen->taxonomy; ?>" />
            <p><a href="edit.php?page=tag-groups"><?php _e( 'Edit tag groups', 'tag-groups' ) ?></a>. (<?php _e( 'Clicking will leave this page without saving.', 'tag-groups' ) ?>)</p>
        </td>
    </tr>

    <?php

}


/**
 * Outputs a table on a submenu page where you can add, delete, change tag groups, their labels and their order.
 */
function tg_group_administration()
{
    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $max_tag_group_id = get_option( 'max_tag_group_id', 0 );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;

    if ( $max_tag_group_id < 0 ) {
        $max_tag_group_id = 0;
    }

    if ( isset( $_REQUEST['action'] ) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = '';
    }

    if ( isset( $_GET['id'] ) ) {
        $tag_groups_id = (int) $_GET['id'];
    } else {
        $tag_groups_id = 0;
    }

    if ( isset( $_POST['ok'] ) ) {
        $ok = $_POST['ok'];
    } else {
        $ok = '';
    }
    ?>

    <div class='wrap'>
        <h2>Tag Groups</h2>

        <?php
        /*
         *  save a new label
         */
        if ( isset( $_POST['label'] ) ) {

            $label = stripslashes( sanitize_text_field( $_POST['label'] ) );

            if ( $label == '' ) :
                ?>

                <div class="updated fade"><p>
                        <?php _e( 'The label cannot be empty. Please correct it or go back.', 'tag-groups' ) ?>
                    </p></div><br clear="all" /><?php elseif ( (is_array( $tag_group_labels )) && (in_array( $label, $tag_group_labels )) ) :
                        ?>

                <div class="updated fade"><p>
                        <?php printf( __( 'A tag group with the label \'%s\' already exists, or the label has not changed. Please choose another one or go back.', 'tag-groups' ), $label ) ?>
                    </p></div><br clear="all" /> <?php
            else:

                if ( !isset( $_POST['tag-groups-settings-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-settings-nonce'], 'tag-groups-settings' ) ) {
                    die( "Security check" );
                }


                if ( isset( $tag_groups_id ) && $tag_groups_id != '0' && $tag_groups_id != '' ) {

                    /*
                     *  update
                     */

                    tg_unregister_string_wpml( $tag_group_labels[$tag_groups_id] );

                    $tag_group_labels[$tag_groups_id] = $label;

                    tg_register_string_wpml( 'Group Label ID ' . $tag_groups_id, $tag_group_labels[$tag_groups_id] );
                } else {
                    /*
                     * new
                     */

                    $max_tag_group_id++;

                    $number_of_tag_groups++;

                    $tag_group_labels[$number_of_tag_groups] = $label;

                    tg_register_string_wpml( 'Group Label ID ' . $number_of_tag_groups, $label );

                    $tag_group_ids[$number_of_tag_groups] = $max_tag_group_id;
                }

                update_option( 'tag_group_labels', $tag_group_labels );

                update_option( 'tag_group_ids', $tag_group_ids );

                update_option( 'max_tag_group_id', $max_tag_group_id );
                ?>

                <div class="updated fade"><p>
                        <?php printf( __( 'The tag group with the label \'%s\' has been saved!', 'tag-groups' ), $label ) ?>
                    </p></div><br clear="all" />

                <?php
                $action = '';

                $tag_group_labels = get_option( 'tag_group_labels', array() );

                $tag_group_ids = get_option( 'tag_group_ids', array() );

                $number_of_tag_groups = count( $tag_group_labels ) - 1;

            endif;
        } else {

            $label = '';
        }

        /*
         *  change order - move up
         */
        if ( ($action == 'up') && ($tag_groups_id > 1) ) {

            tg_swap( $tag_group_labels, $tag_groups_id - 1, $tag_groups_id );

            tg_swap( $tag_group_ids, $tag_groups_id - 1, $tag_groups_id );

            update_option( 'tag_group_labels', $tag_group_labels );

            update_option( 'tag_group_ids', $tag_group_ids );

            $action = "";
        }

        /*
         *  change order - move down
         */
        if ( ($action == 'down') && ($tag_groups_id < $number_of_tag_groups) ) {

            tg_swap( $tag_group_labels, $tag_groups_id, $tag_groups_id + 1 );

            tg_swap( $tag_group_ids, $tag_groups_id, $tag_groups_id + 1 );

            update_option( 'tag_group_labels', $tag_group_labels );

            update_option( 'tag_group_ids', $tag_group_ids );

            $action = "";
        }

        switch ( $action ) {
            case 'new':
                ?>

                <h3><?php _e( 'Create a new tag group', 'tag-groups' ) ?></h3>
                <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                    <input type="hidden" name="tag-groups-settings-nonce" id="tag-groups-settings-nonce" value="<?php echo wp_create_nonce( 'tag-groups-settings' ) ?>" />
                    <ul>
                        <li><label for="label"><?php _e( 'Label', 'tag-groups' ) ?>: </label>
                            <input id="label" maxlength="100" size="70" name="label" value="<?php echo $label ?>" /></li>   
                    </ul>
                    <input class='button-primary' type='submit' name='Save' value='<?php _e( 'Create Group', 'tag-groups' ); ?>' id='submitbutton' />
                    <input class='button-primary' type='button' name='Cancel' value='<?php _e( 'Cancel', 'tag-groups' ); ?>' id='cancel' onclick="location.href = 'edit.php?page=tag-groups'"/>
                </form>
                <?php
                break;

            case 'edit':
                ?>

                <h3><?php _e( 'Edit the label of an existing tag group', 'tag-groups' ) ?></h3>
                <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                    <input type="hidden" name="tag-groups-settings-nonce" id="tag-groups-settings-nonce" value="<?php echo wp_create_nonce( 'tag-groups-settings' ) ?>" />
                    <ul>
                        <li><label for="label"><?php _e( 'Label', 'tag-groups' ) ?>: </label>
                            <input id="label" maxlength="100" size="70" name="label" value="<?php echo $tag_group_labels[$tag_groups_id] ?>" /></li>   
                    </ul>
                    <input class='button-primary' type='submit' name='Save' value='<?php _e( 'Save Group', 'tag-groups' ); ?>' id='submitbutton' />
                    <input class='button-primary' type='button' name='Cancel' value='<?php _e( 'Cancel', 'tag-groups' ); ?>' id='cancel' onclick="location.href = 'edit.php?page=tag-groups'"/>
                </form>

                <?php
                break;

            case 'delete':

                if ( ($tag_groups_id < 1) || ($tag_groups_id > $max_tag_group_id) ) {
                    break;
                }

                $label = $tag_group_labels[$tag_groups_id];

                $id = $tag_group_ids[$tag_groups_id];

                if ( !isset( $_GET['tag-groups-delete-nonce'] ) || !wp_verify_nonce( $_GET['tag-groups-delete-nonce'], 'tag-groups-delete-' . $tag_groups_id ) ) {
                    die( "Security check" );
                }

                array_splice( $tag_group_labels, $tag_groups_id, 1 );

                array_splice( $tag_group_ids, $tag_groups_id, 1 );

                tg_unregister_string_wpml( 'Group Label ID ' . $id );

                $max = 0;

                foreach ( $tag_group_ids as $check_id ) {

                    if ( $check_id > $max ) {
                        $max = $check_id;
                    }
                }

                $max_tag_group_id = $max;

                tg_unassign( $tag_groups_id );

                update_option( 'tag_group_labels', $tag_group_labels );

                update_option( 'tag_group_ids', $tag_group_ids );

                update_option( 'max_tag_group_id', $max_tag_group_id );
                ?>



                <div class="updated fade"><p>
                        <?php printf( __( 'A tag group with the id %s and the label \'%s\' has been deleted.', 'tag-groups' ), $id, $label ); ?>
                    </p></div><br clear="all" />

                <?php
                break;

            default:
        }


        $tag_group_labels = get_option( 'tag_group_labels', array() );

        $tag_group_ids = get_option( 'tag_group_ids', array() );

        $max_tag_group_id = get_option( 'max_tag_group_id', 0 );

        $number_of_tag_groups = count( $tag_group_labels ) - 1;
        ?>

        <p><?PHP _e( 'On this page you can define tag groups. Tags (or terms) can be assigned to these groups on the page where you edit the tags (terms).', 'tag-groups' ) ?></p>
        <h3><?php _e( 'List', 'tag-groups' ) ?></h3>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e( 'ID', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Label displayed on the frontend', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Number of assigned tags', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Action', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Change sort order', 'tag-groups' ) ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e( 'ID', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Label displayed on the frontend', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Number of assigned tags', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Action', 'tag-groups' ) ?></th>
                    <th><?php _e( 'Change sort order', 'tag-groups' ) ?></th>
                </tr>
            </tfoot>
            <tbody>

                <?php for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) : ?>

                    <tr>
                        <td><?php echo $tag_group_ids[$i]; ?></td>
                        <td><?php echo $tag_group_labels[$i] ?></td>
                        <td><?php echo tg_number_assigned( $tag_group_ids[$i] ) ?></td>
                        <td><a href="edit.php?page=tag-groups&action=edit&id=<?php echo $i; ?>"><?php _e( 'Edit', 'tag-groups' ) ?></a>, <a href="#" onclick="var answer = confirm('<?PHP _e( 'Do you really want to delete the tag group', 'tag-groups' ) ?> \'<?php echo esc_js( $tag_group_labels[$i] ) ?>\'?');
                                if (answer) {
                                    window.location = 'edit.php?page=tag-groups&action=delete&id=<?php echo $i ?>&tag-groups-delete-nonce=<?php echo wp_create_nonce( 'tag-groups-delete-' . $i ) ?>'
                                }"><?php _e( 'Delete', 'tag-groups' ) ?></a></td>
                        <td>
                            <div style="overflow:hidden; position:relative;height:15px;width:27px;clear:both;">
                                <?php if ( $i > 1 ) : ?>
                                    <a href="edit.php?page=tag-groups&action=up&id=<?php echo $i ?>">
                                        <div class="tag-groups-up"></div>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div style="overflow:hidden; position:relative;height:15px;width:27px;clear:both;">
                                <?php if ( $i < $number_of_tag_groups ) : ?>
                                    <a href="edit.php?page=tag-groups&action=down&id=<?php echo $i ?>">
                                        <div class="tag-groups-down"></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                <?php endfor; ?>

                <tr>
                    <td><?php _e( 'new', 'tag-groups' ) ?></td>
                    <td></td>
                    <td></td>
                    <td><a href="edit.php?page=tag-groups&action=new"><?php _e( 'Create', 'tag-groups' ) ?></a></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if ( current_user_can( 'manage_options' ) ) : ?>
        <p><a href="options-general.php?page=tag-groups-settings"><?php _e( 'Go to the settings.', 'tag-groups' ) ?></a></p>
    <?php endif; ?>


    <?php

}

/*
 * Outputs the general settings page and handles the main actions: select taxonomy, theming options, WPML integration, reset all
 */


function tg_settings_page()
{


    $active_tab = 0;
    ?>

    <div class='wrap'>
        <h2>Tag Groups Settings</h2>

        <?php
        /*
         *  performing actions
         */

        if ( isset( $_REQUEST['action'] ) ) {
            $action = $_REQUEST['action'];
        } else {
            $action = '';
        }

        if ( isset( $_GET['id'] ) ) {
            $tag_groups_id = (int) $_GET['id'];
        } else {
            $tag_groups_id = 0;
        }

        if ( isset( $_POST['theme-name'] ) ) {
            $theme_name = stripslashes( sanitize_text_field( $_POST['theme-name'] ) );
        } else {
            $theme_name = '';
        }

        if ( isset( $_POST['theme'] ) ) {
            $theme = stripslashes( sanitize_text_field( $_POST['theme'] ) );
        } else {
            $theme = '';
        }

        if ( isset( $_POST['taxonomies'] ) ) {
            $taxonomy = $_POST['taxonomies'];
        } else {
            $taxonomy = array();
        }

        if ( isset( $_POST['ok'] ) ) {
            $ok = $_POST['ok'];
        } else {
            $ok = '';
        }

        if ( isset( $_GET['active-tab'] ) ) {
            $active_tab = (int) $_GET['active-tab'];
        }

        if ( $active_tab < 0 || $active_tab > 7 ) {
            $active_tab = 0;
        }


        switch ( $action ) {

            case 'widget':

                if ( !isset( $_POST['tag-groups-widget-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-widget-nonce'], 'tag-groups-widget' ) ) {
                    die( "Security check" );
                }

                if ( isset( $_POST['widget'] ) && ($_POST['widget'] == '1') ) {

                    update_option( 'tag_group_shortcode_widget', 1 );
                } else {

                    update_option( 'tag_group_shortcode_widget', 0 );
                }
                ?>
                <div class="updated fade"><p>
                        <?php _e( 'Settings saved.', 'tag-groups' ); ?>
                    </p></div><br clear="all" />

                <?php
                break;

            case 'reset':

                if ( !isset( $_POST['tag-groups-reset-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-reset-nonce'], 'tag-groups-reset' ) ) {
                    die( "Security check" );
                }


                if ( $ok == 'yes' ) {
                    $tag_group_labels = array();

                    $tag_group_ids = array();

                    $max_tag_group_id = 0;

                    update_option( 'tag_group_labels', $tag_group_labels );

                    update_option( 'tag_group_ids', $tag_group_ids );

                    update_option( 'max_tag_group_id', $max_tag_group_id );

                    tg_unassign( 0 );
                    ?>
                    <div class="updated fade"><p>
                            <?php _e( 'All groups are deleted and assignments reset.', 'tag-groups' ); ?>
                        </p></div><br clear="all" />

                    <?php
                }

                break;

            case 'uninstall':

                if ( !isset( $_POST['tag-groups-uninstall-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-uninstall-nonce'], 'tag-groups-uninstall' ) ) {
                    die( "Security check" );
                }


                if ( $ok == 'yes' ) {

                    update_option( 'tag_group_reset_when_uninstall', true );
                } else {

                    update_option( 'tag_group_reset_when_uninstall', false );
                }
                ?>
                <div class="updated fade"><p>
                        <?php _e( 'Your settings have been saved.', 'tag-groups' ); ?>
                    </p></div><br clear="all" />

                <?php
                break;

            case 'wpml':

                for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) {

                    tg_register_string_wpml( 'Group Label ID ' . $i, $tag_group_labels[$i] );
                }
                ?>

                <div class="updated fade"><p>
                        <?php _e( 'All labels were registered.', 'tag-groups' ); ?>
                    </p></div><br clear="all" />


                <?php
                break;

            case 'theme':

                if ( $theme == 'own' ) {
                    $theme = $theme_name;
                }

                if ( !isset( $_POST['tag-groups-settings-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-settings-nonce'], 'tag-groups-settings' ) ) {
                    die( "Security check" );
                }

                update_option( 'tag_group_theme', $theme );

                $mouseover = (isset( $_POST['mouseover'] ) && $_POST['mouseover'] == '1') ? true : false;

                $collapsible = (isset( $_POST['collapsible'] ) && $_POST['collapsible'] == '1') ? true : false;

                $html_description = (isset( $_POST['html_description'] ) && $_POST['html_description'] == '1') ? true : false;

                update_option( 'tag_group_mouseover', $mouseover );

                update_option( 'tag_group_collapsible', $collapsible );

                update_option( 'tag_group_html_description', $html_description );

                $tag_group_enqueue_jquery = (isset( $_POST['enqueue-jquery'] ) && $_POST['enqueue-jquery'] == '1') ? true : false;

                update_option( 'tag_group_enqueue_jquery', $tag_group_enqueue_jquery );

                tg_clear_cache();
                ?> <div class="updated fade"><p>
                <?php _e( 'Your tag cloud theme settings have been saved.', 'tag-groups' ); ?>
                    </p></div><br clear="all" />

                <?php
                break;

            case 'taxonomy':

                if ( !isset( $_POST['tag-groups-taxonomy-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-taxonomy-nonce'], 'tag-groups-taxonomy' ) ) {
                    die( "Security check" );
                }

                $args = array(
                    'public' => true
                );

                $taxonomies = get_taxonomies( $args, 'names' );

                foreach ( $taxonomy as $taxonomy_item ) {

                    $taxonomy_item = stripslashes( sanitize_text_field( $taxonomy_item ) );

                    if ( !in_array( $taxonomy_item, $taxonomies ) ) {
                        die( "Security check: taxonomies" );
                    }
                }

                update_option( 'tag_group_taxonomy', $taxonomy );

                tg_clear_cache();
                ?> <div class="updated fade"><p>
                <?php _e( 'Your tag taxonomy settings have been saved.', 'tag-groups' ); ?>
                    </p></div><br clear="all" />

                <?php
                break;

            case 'backend':

                if ( !isset( $_POST['tag-groups-backend-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-backend-nonce'], 'tag-groups-backend' ) ) {
                    die( "Security check" );
                }

                $show_filter_posts = isset( $_POST['filter_posts'] ) ? 1 : 0;

                update_option( 'tag_group_show_filter', $show_filter_posts );

                $show_filter_tags = isset( $_POST['filter_tags'] ) ? 1 : 0;

                update_option( 'tag_group_show_filter_tags', $show_filter_tags );
                ?> <div class="updated fade"><p>
                <?php _e( 'Your back end settings have been saved.', 'tag-groups' ); ?>
                    </p></div><br clear="all" />

                <?php
                break;

            case 'export':

                if ( !isset( $_POST['tag-groups-export-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-export-nonce'], 'tag-groups-export' ) ) {
                    die( "Security check" );
                }

                $options = array(
                    'name' => 'tag_groups_options',
                    'version' => TAG_GROUPS_VERSION,
                    'tag_group_taxonomy' => get_option( 'tag_group_taxonomy' ),
                    'tag_group_labels' => get_option( 'tag_group_labels' ),
                    'tag_group_ids' => get_option( 'tag_group_ids' ),
                    'tag_group_theme' => get_option( 'tag_group_theme' ),
                    'max_tag_group_id' => get_option( 'max_tag_group_id' ),
                    'tag_group_mouseover' => get_option( 'tag_group_mouseover' ),
                    'tag_group_collapsible' => get_option( 'tag_group_collapsible' ),
                    'tag_group_enqueue_jquery' => get_option( 'tag_group_enqueue_jquery' ),
                    'tag_group_shortcode_widget' => get_option( 'tag_group_shortcode_widget' ),
                    'tag_group_show_filter' => get_option( 'tag_group_show_filter' ),
                    'tag_group_show_filter_tags' => get_option( 'tag_group_show_filter_tags' ),
                    'tag_group_html_description' => get_option( 'tag_group_html_description' ),
                    'tag_group_reset_when_uninstall' => get_option( 'tag_group_reset_when_uninstall' )
                );

                /*
                 * Writing file
                 */
                try {
                    /*
                     * The file can be saved in a publically accessible location, since no critical information is included.
                     * Extension is .txt so that upload will be allowed.
                     */
                    $fp = fopen( WP_CONTENT_DIR . '/uploads/tag_groups_settings.txt', 'w' );
                    fwrite( $fp, json_encode( $options ) );
                    fclose( $fp );
                    ?>
                    <div class="updated fade"><p>
                            <?php _e( 'Your settings and groups have been exported. Please download the resulting file with right-click or ctrl-click:', 'tag-groups' ); ?>
                        </p>
                        <p>
                            <a href="<?php echo get_bloginfo( 'wpurl' ) . '/wp-content/uploads/tag_groups_settings.txt' ?>" target="_blank">tag_groups_settings.txt</a>
                        </p>
                    </div><br clear="all" />
                    <?php
                } catch ( Exception $e ) {
                    ?> <div class="error fade"><p>
                    <?php _e( 'Writing of the exported settings failed.', 'tag-groups' ); ?>
                        </p>
                    </div><br clear="all" />
                    <?php
                }
                break;

            case 'import':

                if ( !isset( $_POST['tag-groups-import-nonce'] ) || !wp_verify_nonce( $_POST['tag-groups-import-nonce'], 'tag-groups-import' ) ) {
                    die( "Security check" );
                }

                // Make very sure that only administrators can upload stuff
                if ( !current_user_can( 'manage_options' ) ) {
                    die( "Capability check failed" );
                }

                if ( !isset($_FILES['settings_file']) ) {
                    die( "File missing" );
                }

                if ( !function_exists( 'wp_handle_upload' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
                    
                $settings_file = $_FILES['settings_file'];

                /*
                 * The file can be saved in a publically accessible location, since no critical information is included.
                 */
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload( $settings_file, $upload_overrides );

                if ( $movefile && !isset( $movefile['error'] ) && $_FILES['settings_file']['name'] == 'tag_groups_settings.txt' ) {

                    $options = json_decode( file_get_contents( $movefile['file'] ), true );

                    if ( empty( $options ) || !is_array( $options ) || count( $options ) < 15 || $options['name'] != 'tag_groups_options' ) {
                        ?> <div class="error fade"><p>
                        <?php _e( 'Error reading the file.', 'tag-groups' ); ?>
                            </p></div><br clear="all" />
                        <?php
                    } else {
                        update_option( 'tag_group_taxonomy', $options['tag_group_taxonomy'] );
                        update_option( 'tag_group_labels', $options['tag_group_labels'] );
                        update_option( 'tag_group_ids', $options['tag_group_ids'] );
                        update_option( 'tag_group_theme', $options['tag_group_theme'] );
                        update_option( 'max_tag_group_id', $options['max_tag_group_id'] );
                        update_option( 'tag_group_mouseover', $options['tag_group_mouseover'] );
                        update_option( 'tag_group_collapsible', $options['tag_group_collapsible'] );
                        update_option( 'tag_group_enqueue_jquery', $options['tag_group_enqueue_jquery'] );
                        update_option( 'tag_group_shortcode_widget', $options['tag_group_shortcode_widget'] );
                        update_option( 'tag_group_show_filter', $options['tag_group_show_filter'] );
                        update_option( 'tag_group_show_filter_tags', $options['tag_group_show_filter_tags'] );
                        update_option( 'tag_group_html_description', $options['tag_group_html_description'] );
                        update_option( 'tag_group_reset_when_uninstall', $options['tag_group_reset_when_uninstall'] );
                        // add further options in future versions after checking if isset or checking $options['version']
                        ?> <div class="updated fade"><p>
                            <?php _e( 'Your settings and groups have been imported (created with plugin version ' . $options['version'] . ').', 'tag-groups' ); ?>
                            </p></div><br clear="all" />
                        <?php
                    }
                } else {
                    ?> <div class="error fade"><p>
                        <?php _e( 'Error uploading the file. ' . $movefile['error'], 'tag-groups' ); ?>
                        </p></div><br clear="all" />
                    <?php
                }

                break;

            default:
                break;
        }



        $tag_group_labels = get_option( 'tag_group_labels', array() );

        $tag_group_ids = get_option( 'tag_group_ids', array() );

        $max_tag_group_id = get_option( 'max_tag_group_id', 0 );

        $tag_group_theme = get_option( 'tag_group_theme', TAG_GROUPS_STANDARD_THEME );

        $tag_group_mouseover = get_option( 'tag_group_mouseover', '' );

        $tag_group_collapsible = get_option( 'tag_group_collapsible', '' );

        $tag_group_enqueue_jquery = get_option( 'tag_group_enqueue_jquery', true );

        $tag_group_html_description = get_option( 'tag_group_html_description', false );

        $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

        $tag_group_shortcode_widget = get_option( 'tag_group_shortcode_widget' );

        $number_of_tag_groups = count( $tag_group_labels ) - 1;

        $show_filter_posts = get_option( 'tag_group_show_filter', true );

        $show_filter_tags = get_option( 'tag_group_show_filter_tags', true );

        $tag_group_reset_when_uninstall = get_option( 'tag_group_reset_when_uninstall', false );



        if ( $max_tag_group_id < 0 ) {
            $max_tag_group_id = 0;
        }

        $default_themes = explode( ',', TAG_GROUPS_BUILT_IN_THEMES );


        /*
         * Render the Settings page
         */
        ?>

        <h2 class="nav-tab-wrapper">
            <a href="options-general.php?page=tag-groups-settings&active-tab=0" class="nav-tab <?php if ( $active_tab == 0 ) echo 'nav-tab-active' ?>"><?php _e( 'Basics', 'tag-groups' ) ?></a>
            <a href="options-general.php?page=tag-groups-settings&active-tab=1" class="nav-tab <?php if ( $active_tab == 1 ) echo 'nav-tab-active' ?>"><?php _e( 'Theme', 'tag-groups' ) ?></a>
            <?php if ( function_exists( 'icl_register_string' ) ) : ?>
                <a href="options-general.php?page=tag-groups-settings&active-tab=2" class="nav-tab <?php if ( $active_tab == 2 ) echo 'nav-tab-active' ?>"><?php _e( 'WPML', 'tag-groups' ) ?></a>
            <?php endif; ?>
            <a href="options-general.php?page=tag-groups-settings&active-tab=3" class="nav-tab <?php if ( $active_tab == 3 ) echo 'nav-tab-active' ?>"><?php _e( 'Tag Cloud', 'tag-groups' ) ?></a>
            <a href="options-general.php?page=tag-groups-settings&active-tab=6" class="nav-tab <?php if ( $active_tab == 6 ) echo 'nav-tab-active' ?>"><?php _e( 'Accordion', 'tag-groups' ) ?></a>
            <a href="options-general.php?page=tag-groups-settings&active-tab=7" class="nav-tab <?php if ( $active_tab == 7 ) echo 'nav-tab-active' ?>"><?php _e( 'Export/Import', 'tag-groups' ) ?></a>
            <a href="options-general.php?page=tag-groups-settings&active-tab=4" class="nav-tab <?php if ( $active_tab == 4 ) echo 'nav-tab-active' ?>"><?php _e( 'Reset', 'tag-groups' ) ?></a>
            <a href="options-general.php?page=tag-groups-settings&active-tab=5" class="nav-tab <?php if ( $active_tab == 5 ) echo 'nav-tab-active' ?>"><?php _e( 'About', 'tag-groups' ) ?></a>
        </h2>
        <p>&nbsp;</p>

        <?php if ( $active_tab == 0 ): ?>
            <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                <input type="hidden" name="tag-groups-taxonomy-nonce" id="tag-groups-taxonomy-nonce" value="<?php echo wp_create_nonce( 'tag-groups-taxonomy' ) ?>" />
                <h3><?php _e( 'Taxonomies', 'tag-groups' ) ?></h3>
                <p><?php _e( 'Choose the taxonomies for which you want to use tag groups. Default is <b>post_tag</b>. Please note that the tag cloud might not work with all taxonomies and that some taxonomies listed here may not be accessible in the admin backend. If you don\'t understand what is going on here, just leave the default.', 'tag-groups' ) ?></p>
                <?php
                $args = array(
                    'public' => true
                );

                $taxonomies = get_taxonomies( $args, 'names' );
                ?>

                <ul>

                    <?php foreach ( $taxonomies as $taxonomy ) : ?>

                        <li><input type="checkbox" name="taxonomies[]" id="<?php echo $taxonomy ?>" value="<?php echo $taxonomy ?>" <?php if ( in_array( $taxonomy, $tag_group_taxonomy ) ) echo 'checked'; ?> />&nbsp;<label for="<?php echo $taxonomy ?>"><?php echo $taxonomy ?></label></li>

                    <?php endforeach; ?>

                </ul>

                <input type="hidden" name="action" value="taxonomy">
                <input class='button-primary' type='submit' name='Save' value='<?php _e( 'Save Taxonomy', 'tag-groups' ); ?>' id='submitbutton' />
            </form>
            <p>&nbsp;</p>

            <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                <input type="hidden" name="tag-groups-backend-nonce" id="tag-groups-backend-nonce" value="<?php echo wp_create_nonce( 'tag-groups-backend' ) ?>" />

                <h3><?php _e( 'Back End Settings', 'tag-groups' ) ?></h3>
                <p><?php _e( 'You can add a pull-down menu to the filters above the list of posts. If you filter posts by tag groups, then only items will be shown that have tags (terms) in that particular group. This feature can be turned off so that the menu won\'t obstruct your screen if you use a high number of groups. May not work with all custom taxonomies. Doesn\'t work with more than <b>one</b> taxonomy or with <b>category</b> as taxonomy.', 'tag-groups' ) ?></p>
                <ul>
                    <li><input type="checkbox" id="tg_filter_posts" name="filter_posts" value="1" <?php if ( $show_filter_posts ) echo 'checked'; ?> />&nbsp;<label for="tg_filter_posts"><?php _e( 'Display filter on post admin', 'tag-groups' ) ?></label></li>
                </ul>		
                <p><?php _e( 'Here you can deactivate the filter on the list of tags if it conflicts with other plugins or themes.', 'tag-groups' ) ?></p>
                <ul>
                    <li><input type="checkbox" id="tg_filter_tags" name="filter_tags" value="1" <?php if ( $show_filter_tags ) echo 'checked'; ?> />&nbsp;<label for="tg_filter_tags"><?php _e( 'Display filter on tag admin', 'tag-groups' ) ?></label></li>
                </ul>
                <input type="hidden" name="action" value="backend">
                <input class='button-primary' type='submit' name='Save' value='<?php _e( 'Save Back End Settings', 'tag-groups' ); ?>' id='submitbutton' />
            </form>
            <script>
                jQuery(document).ready(function () {

                    tgTaxonomyToFilter();
                    tgCategoryKillsFilter();

                    jQuery("input[name='taxonomies[]']:checkbox").click(function () {
                        tgTaxonomyToFilter();
                        tgCategoryKillsFilter();
                    });

                    function tgTaxonomyToFilter() {
                        var n = jQuery("input[name='taxonomies[]']:checkbox:checked").length;
                        if (n > 1) {
                            jQuery('#tg_filter_posts').attr('disabled', 'disabled');
                        } else {
                            jQuery('#tg_filter_posts').removeAttr('disabled');
                        }
                    }

                    function tgCategoryKillsFilter() {
                        if (jQuery('#category:checkbox').is(':checked')) {
                            jQuery('#tg_filter_posts').attr('disabled', 'disabled');
                        } else {
                            jQuery('#tg_filter_posts').removeAttr('disabled');
                        }
                    }

                });
            </script>

        <?php endif; ?>

        <?php if ( $active_tab == 1 ): ?>
            <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                <input type="hidden" name="tag-groups-settings-nonce" id="tag-groups-settings-nonce" value="<?php echo wp_create_nonce( 'tag-groups-settings' ) ?>" />
                <p><?php _e( 'Here you can choose a theme for the tag cloud. The path to own themes is relative to the <i>uploads</i> folder of your Wordpress installation. Leave empty if you don\'t use any.</p><p>New themes can be created with the <a href="http://jqueryui.com/themeroller/" target="_blank">jQuery UI ThemeRoller</a>:
			<ol>
			 <li>On the page "Theme Roller" you can customize all features or pick one set from the gallery. Finish with the "download" button.</li>
			 <li>On the next page ("Download Builder") you will need to select the version 1.11.x and the components "Core", "Widget", "Accordion" and "Tabs". Make sure that before downloading you enter at the bottom as "CSS Scope" <b>.tag-groups-cloud-tabs</b> (including the dot).</li>
			 <li>Then you unpack the downloaded zip file. You will need the "images" folder and the "jquery-ui.theme.min.css" file.</li>
			 <li>Create a new folder inside your <i>wp-content/uploads</i> folder (for example "my-theme") and copy there these two items.</li>
                         <li>Enter the name of this new folder (for example "my-theme") below.</li>
			</ol>', 'tag-groups' ) ?></p>

                <table>
                    <tr>
                        <td style="width:400px; padding-right:50px;">
                            <ul>

                                <?php foreach ( $default_themes as $theme ) : ?>

                                    <li><input type="radio" name="theme" id="tg_<?php echo $theme ?>" value="<?php echo $theme ?>" <?php if ( $tag_group_theme == $theme ) echo 'checked'; ?> />&nbsp;<label for="tg_<?php echo $theme ?>"><?php echo $theme ?></label></li>

                                <?php endforeach; ?>

                                <li><input type="radio" name="theme" value="own" id="tg_own" <?php if ( !in_array( $tag_group_theme, $default_themes ) ) echo 'checked' ?> />&nbsp;<label for="tg_own">own: /wp-content/uploads/</label><input type="text" id="theme-name" name="theme-name" value="<?php if ( !in_array( $tag_group_theme, $default_themes ) ) echo $tag_group_theme ?>" /></li>
                                <li><input type="checkbox" name="enqueue-jquery" id="tg_enqueue-jquery" value="1" <?php if ( $tag_group_enqueue_jquery ) echo 'checked' ?> />&nbsp;<label for="tg_enqueue-jquery"><?php _e( 'Use jQuery.  (Default is on. Other plugins might override this setting.)', 'tag-groups' ) ?></label></li>
                            </ul>
                        </td>

                        <td>
                            <h4><?php _e( 'Further options', 'tag-groups' ) ?></h4>
                            <ul>
                                <li><input type="checkbox" name="mouseover" id="mouseover" value="1" <?php if ( $tag_group_mouseover ) echo 'checked'; ?> >&nbsp;<label for="mouseover"><?php _e( 'Tabs triggered by hovering mouse pointer (without clicking).', 'tag-groups' ) ?></label></li>
                                <li><input type="checkbox" name="collapsible" id="collapsible" value="1" <?php if ( $tag_group_collapsible ) echo 'checked'; ?> >&nbsp;<label for="collapsible"><?php _e( 'Collapsible tabs (toggle open/close).', 'tag-groups' ) ?></label></li>
                                <li><input type="checkbox" name="html_description" id="html_description" value="1" <?php if ( $tag_group_html_description ) echo 'checked'; ?> >&nbsp;<label for="html_description"><?php _e( 'Allow HTML in tag description.', 'tag-groups' ) ?></label></li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <input type="hidden" id="action" name="action" value="theme">
                <input class='button-primary' type='submit' name='save' value='<?php _e( 'Save Theme Options', 'tag-groups' ); ?>' id='submitbutton' />
            </form>
        <?php endif; ?>

        <?php if ( $active_tab == 2 ): ?>
            <?php if ( function_exists( 'icl_register_string' ) ) : ?>
                <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                    <h3><?php _e( 'Register group labels with WPML', 'tag-groups' ) ?></h3>
                    <p><?php _e( 'Use this button to register all existing group labels with WPML for string translation. This is only necessary if labels have existed before you installed WPML.', 'tag-groups' ) ?></p>
                    <input type="hidden" id="action" name="action" value="wpml">
                    <input class='button-primary' type='submit' name='register' value='<?php _e( 'Register Labels', 'tag-groups' ); ?>' id='submitbutton' />
                </form>
            <?php endif; ?>
        <?php endif; ?>


        <?php if ( $active_tab == 3 ): ?>
            <p><?php _e( 'You can use a shortcode to embed the tag cloud directly in a post, page or widget or you call the function in the PHP code of your theme.', 'tag-groups' ) ?></p>
            <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                <input type="hidden" name="tag-groups-widget-nonce" id="tag-groups-widget-nonce" value="<?php echo wp_create_nonce( 'tag-groups-widget' ) ?>" />
                <ul>
                    <li><input type="checkbox" name="widget" id="tg_widget" value="1" <?php if ( $tag_group_shortcode_widget ) echo 'checked'; ?> >&nbsp;<label for="tg_widget"><?php _e( 'Enable shortcode in sidebar widgets (if not visible anyway).', 'tag-groups' ) ?></label></li>
                </ul>
                <input type="hidden" id="action" name="action" value="widget">
                <input class='button-primary' type='submit' name='save' value='<?php _e( 'Save', 'tag-groups' ); ?>' id='submitbutton' />
            </form>

            <p>&nbsp;</p>
            <h3><?php _e( 'Further Instructions', 'tag-groups' ) ?></h3>
            <h4>a) <?php _e( 'Shortcode', 'tag-groups' ) ?></h4>
            <p>[tag_groups_cloud]</p>
            <p><b><?php _e( 'Parameters', 'tag-groups' ) ?></b><br /><?php _e( 'example', 'tag-groups' ) ?>: [tag_groups_cloud smallest=9 largest=30 include=1,2,10]
            <ul>
                <li>&nbsp;</li>
                <li><?php _e( '<b>Tags or Terms:</b>', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>smallest=x</b> Font-size in pt of the smallest tags. Default: 12', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>largest=x</b> Font-size in pt of the largest tags. Default: 22', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>orderby=abc</b> Which field to use for sorting, e.g. count. Default: name', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>order=ASC or =DESC</b> Whether to sort the tags in ascending or descending order. Default: ASC', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>amount=x</b> Maximum amount of tags in one cloud (per group). Default: 40', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>hide_empty=1 or =0</b> Whether to hide or show also tags that are not assigned to any post. Default: 1 (hide empty)', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>tags_post_id=x</b> Display only tags that are assigned to the post (or page) with the ID x. If set to 0, it will try to retrieve the current post ID. Default: -1 (all tags displayed)', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>assigned_class="something"</b> A modification of the tags_post_id parameter: Rather than hiding tags that are not assigned to the post (or page), they can be styled differently. Tags will receive this class name with appended _1 or _0. (If you output the tags as an array, a new element with the key "assigned" will be true or false.)', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>separator="•"</b> A separator between the tags. Default: empty', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>separator_size=12</b> The size of the separator. Default: 12', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>adjust_separator_size=1 or =0</b> Whether to adjust the separator\'s size to the size of the following tag. Default: 0', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>prepend="#"</b> Prepend to each tag label. Default: empty', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>append="something"</b> Append to each tag label. Default: empty', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>taxonomy="x,y,..."</b> Restrict the tags only to these taxonomies. Default: empty (= no restriction)', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>link_target="_blank"</b> Set the "target" attribute for the links of the tags. Possible values: _blank, _self, _parent, _top, or the name of a frame. Default: empty (= opens in the same window, same as using _self)', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>show_tag_count=1 or =0</b> Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag. Default: 1 (show)', 'tag-groups' ) ?></li>
                <li>&nbsp;</li>
                <li><?php _e( '<b>Groups and Tabs:</b>', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>include="x,y,..."</b> IDs of tag groups (left column in list of groups) that will be considered in the tag cloud. Empty or not used means that all tag groups will be used. Default: empty', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>groups_post_id=x</b> Display only groups of which at least one assigned tag is also assigned to the post (or page) with the ID x. If set to 0, it will try to retrieve the current post ID. Default: -1 (all groups displayed). Matching groups will be added to the list specified by the parameter <b>include</b>.', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>show_tabs=1 or =0</b> Whether to show the tabs. Default: 1', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>hide_empty_tabs=1 or =0</b> Whether to hide tabs without tags. Default: 0 (Not implemented for PHP function with second parameter set to \'true\'. Not effective with <b>groups_post_id</b>.)', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>show_all_groups=1 or =0</b> Whether to force showing all groups. Useful with the parameters <b>tags_post_id</b> and <b>assigned_class</b>. Default: 0', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>collapsible=1 or =0</b> Whether tabs are collapsible (toggle open/close). Default: general settings in the back end', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>mouseover=1 or =0</b> Whether tabs can be selected by hovering over with the mouse pointer (without clicking). Default: general settings in the back end', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>active=1 or =0</b> Whether tabs are initially expanded or collapsed. Useful in connection with the parameter <b>collapsible</b>. Default: 1', 'tag-groups' ) ?></li>

                <li>&nbsp;</li>
                <li><?php _e( '<b>Advanced Styling:</b>', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>div_id=abc</b> Define an id for the enclosing &lt;div&gt;. You need to define different values if you use more than one cloud on one page. Make sure this id has not yet been used - including the active theme and other plugins. Recommended are non-standard values to avoid collisions of names, replace spaces by underscores or hyphens, or use "camelCase". Default: tag-groups-cloud-tabs', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>div_class=abc</b> Define a class for the enclosing &lt;div&gt;. Default: tag-groups-cloud-tabs', 'tag-groups' ) ?></li>
                <li><?php _e( '<b>ul_class=abc</b> Define a class for the &lt;div&gt; that generates the tabs with the group labels. Default: empty', 'tag-groups' ) ?></li>
            </ul>
        </p>
        <p>&nbsp;</p>
        <h4>b) PHP</h4>
        <p><?php _e( 'By default the function <b>tag_groups_cloud</b> returns the html for a tabbed tag cloud.', 'tag-groups' ) ?></p>
        <p><?php
        _e( 'Example:', 'tag-groups' );
        echo ' ' . htmlentities( "<?php if ( function_exists( 'tag_groups_cloud' ) ) echo tag_groups_cloud( array( 'include' => '1,2,5,6' ) ); ?>" )
            ?></p>
        <p><?php _e( 'If the optional second parameter is set to \'true\', the function returns a multidimensional array containing tag groups and tags.', 'tag-groups' ); ?></p>
        <p><?php
        _e( 'Example:', 'tag-groups' );
        echo ' ' . htmlentities( "<?php if ( function_exists( 'tag_groups_cloud' ) ) print_r( tag_groups_cloud( array( 'orderby' => 'count', 'order' => 'DESC' ), true ) ); ?>" )
            ?></p>
        <?php endif; ?>


    <?php if ( $active_tab == 6 ): ?>
        <p><?php _e( 'You can also use a shortcode to use an accordion instead of tabs.', 'tag-groups' ) ?></p>


        <p>&nbsp;</p>
        <h3><?php _e( 'Further Instructions', 'tag-groups' ) ?></h3>
        <h4>a) <?php _e( 'Shortcode', 'tag-groups' ) ?></h4>
        <p>[tag_groups_accordion]</p>
        <p><b><?php _e( 'Parameters', 'tag-groups' ) ?></b><br /><?php _e( 'example', 'tag-groups' ) ?>: [tag_groups_accordion smallest=9 largest=30 include=1,2,10]
        <ul>
            <li>&nbsp;</li>
            <li><?php _e( '<b>Tags or Terms:</b>', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>smallest=x</b> Font-size in pt of the smallest tags. Default: 12', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>largest=x</b> Font-size in pt of the largest tags. Default: 22', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>orderby=abc</b> Which field to use for sorting, e.g. count. Default: name', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>order=ASC or =DESC</b> Whether to sort the tags in ascending or descending order. Default: ASC', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>amount=x</b> Maximum amount of tags in one cloud (per group). Default: 40', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>hide_empty=1 or =0</b> Whether to hide or show also tags that are not assigned to any post. Default: 1 (hide empty)', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>tags_post_id=x</b> Display only tags that are assigned to the post (or page) with the ID x. If set to 0, it will try to retrieve the current post ID. Default: -1 (all tags displayed)', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>assigned_class="something"</b> A modification of the tags_post_id parameter: Rather than hiding tags that are not assigned to the post (or page), they can be styled differently. Tags will receive this class name with appended _1 or _0. (If you output the tags as an array, a new element with the key "assigned" will be true or false.)', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>separator="•"</b> A separator between the tags. Default: empty', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>separator_size=12</b> The size of the separator. Default: 12', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>adjust_separator_size=1 or =0</b> Whether to adjust the separator\'s size to the size of the following tag. Default: 0', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>prepend="#"</b> Prepend to each tag label. Default: empty', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>append="something"</b> Append to each tag label. Default: empty', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>taxonomy="x,y,..."</b> Restrict the tags only to these taxonomies. Default: empty (= no restriction)', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>link_target="_blank"</b> Set the "target" attribute for the links of the tags. Possible values: _blank, _self, _parent, _top, or the name of a frame. Default: empty (= opens in the same window, same as using _self)', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>show_tag_count=1 or =0</b> Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag. Default: 1 (show)', 'tag-groups' ) ?></li>
            <li>&nbsp;</li>
            <li><?php _e( '<b>Groups and Accordions:</b>', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>include="x,y,..."</b> IDs of tag groups (left column in list of groups) that will be considered in the tag cloud. Empty or not used means that all tag groups will be used. Default: empty', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>groups_post_id=x</b> Display only groups of which at least one assigned tag is also assigned to the post (or page) with the ID x. If set to 0, it will try to retrieve the current post ID. Default: -1 (all groups displayed). Matching groups will be added to the list specified by the parameter <b>include</b>.', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>show_accordion=1 or =0</b> Whether to show the accordion. Default: 1', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>hide_empty_content=1 or =0</b> Whether to hide content without tags. Default: 0 Not effective with <b>groups_post_id</b>.)', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>show_all_groups=1 or =0</b> Whether to force showing all groups. Useful with the parameters <b>tags_post_id</b> and <b>assigned_class</b>. Default: 0', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>collapsible=1 or =0</b> Whether accordion content is collapsible (toggle open/close). Default: general settings in the back end', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>mouseover=1 or =0</b> Whether accordion headers can be selected by hovering over with the mouse pointer (without clicking). Default: general settings in the back end', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>active=1 or =0</b> Whether the accordion is initially expanded or collapsed. Useful in connection with the parameter <b>collapsible</b>. Default: 1', 'tag-groups' ) ?></li>
            <li>&nbsp;</li>
            <li><?php _e( '<b>Advanced Styling:</b>', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>div_id=abc</b> Define an id for the enclosing &lt;div&gt;. You need to define different values if you use more than one accordion on one page. Make sure this id has not yet been used - including the active theme and other plugins. Recommended are non-standard values to avoid collisions of names, replace spaces by underscores or hyphens, or use "camelCase". Default: tag-groups-cloud-accordion', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>div_class=abc</b> Define a class for the enclosing &lt;div&gt;. Default: tag-groups-cloud-accordion', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>header_class=abc</b> Define a class for the &lt;h3&gt; that contains the headers. Default: empty', 'tag-groups' ) ?></li>
            <li><?php _e( '<b>inner_div_class=abc</b> Define a class for the &lt;div&gt; that contains the tags. Default: empty', 'tag-groups' ) ?></li>
        </ul>
        </p>
    <?php endif; ?>

    <?php if ( $active_tab == 7 ): ?>

        <p>
        <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
            <h3><?php _e( 'Export', 'tag-groups' ) ?></h3>
            <input type="hidden" name="tag-groups-export-nonce" id="tag-groups-export-nonce" value="<?php echo wp_create_nonce( 'tag-groups-export' ) ?>" />
            <p><?php _e( 'Use this button to export all Tag Groups settings and groups into a file.', 'tag-groups' ) ?></p>
            <p><?php _e( "Note: The assignment of tags (term) to the groups won't be included. This information is part of the tags (terms).", 'tag-groups' ) ?></p>
            <input type="hidden" id="action" name="action" value="export">
            <p><input class='button-primary' type='submit' name='export' value='<?php _e( 'Export Settings', 'tag-groups' ); ?>' id='submitbutton' /></p>
        </form>
        </p>
        <p>&nbsp;</p>
        <p>
        <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" enctype="multipart/form-data">
            <h3><?php _e( 'Import', 'tag-groups' ) ?></h3>
            <input type="hidden" name="tag-groups-import-nonce" id="tag-groups-import-nonce" value="<?php echo wp_create_nonce( 'tag-groups-import' ) ?>" />
            <p><?php _e( 'Below you can import previously exported settings and groups from a file.', 'tag-groups' ) ?></p>
            <p><?php _e( 'It is recommended to back up the database of your blog before proceeding.', 'tag-groups' ) ?></p>
            <input type="hidden" id="action" name="action" value="import">
            <p><input type="file" id="settings_file" name="settings_file"></p>
            <p><input class='button-primary' type='submit' name='import' value='<?php _e( 'Import Settings', 'tag-groups' ); ?>' id='submitbutton' /></p>
        </form>
        </p>
    <?php endif; ?>

    <?php if ( $active_tab == 4 ): ?>
        <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
            <input type="hidden" name="tag-groups-reset-nonce" id="tag-groups-reset-nonce" value="<?php echo wp_create_nonce( 'tag-groups-reset' ) ?>" />
            <p><?php _e( 'Use this button to delete all tag groups and assignments. Your tags will not be changed. Check the checkbox to confirm.', 'tag-groups' ) ?></p>
            <p><?php _e( '(Please keep in mind that the tag assignments cannot be recovered by the export/import function.)', 'tag-groups' ) ?></p>
            <input type="checkbox" id="ok" name="ok" value="yes" />
            <label><?php _e( 'I know what I am doing.', 'tag-groups' ) ?></label>
            <input type="hidden" id="action" name="action" value="reset">
            <p><input class='button-primary' type='submit' name='delete' value='<?php _e( 'Delete Groups', 'tag-groups' ); ?>' id='submitbutton' /></p>
        </form>
        <p>&nbsp;</p>
        <h4><?php _e( 'Delete Settings and Groups', 'tag-groups' ) ?></h4>
        <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
            <p>
                <input type="hidden" name="tag-groups-uninstall-nonce" id="tag-groups-uninstall-nonce" value="<?php echo wp_create_nonce( 'tag-groups-uninstall' ) ?>" />
                <input type="checkbox" id="ok" name="ok" value="yes"  <?php if ( $tag_group_reset_when_uninstall ) echo 'checked'; ?> />
                <label><?php _e( 'Delete all groups and settings when uninstalling the plugin.', 'tag-groups' ) ?></label>
                <input type="hidden" id="action" name="action" value="uninstall">
            </p>
            <input class='button-primary' type='submit' name='save' value='<?php _e( 'Save', 'tag-groups' ); ?>' id='submitbutton' />
        </form>
    <?php endif; ?>


    <?php if ( $active_tab == 5 ): ?>
        <h4>Tag Groups, Version: <?php echo TAG_GROUPS_VERSION ?></h4>
        <p><?php printf( __( 'If you find a bug or have a question, please visit the official <a %s>support forum</a>. There is also a <a %s>dedicated page</a> with more examples and instructions for particular applications.', 'tag-groups' ), 'href="http://wordpress.org/support/plugin/tag-groups" target="_blank"', 'href="http://www.christoph-amthor.de/software/tag-groups/" target="_blank"' ); ?></p>
        <h2><?php _e( 'Donations', 'tag-groups' ) ?></h2>
        <p><?php _e( 'This plugin is the result of many hours of work, adding new features, fixing bugs and answering to support questions.', 'tag-groups' ) ?></p>
        <p><?php _e( 'If you find <b>Tag Groups</b> useful or use it to make money, I would appreciate a donation:', 'tag-groups' ); ?></p>
        <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NUR3YJG7VAENA" target="_blank"><img src="<?php echo plugins_url( 'images/btn_donateCC_LG.gif', __FILE__ ) ?>" alt="Donate via Paypal" title="Donate via Paypal" border="0" /></a></p>
        <p><strong>Bitcoin: </strong><a href="bitcoin:1Fe21r57vDK56Yy2MbwjEoTVMiLefpV1v?label=Donation%20for%20Free%20Software" target="_blank">1Fe21r57vDK56Yy2MbwjEoTVMiLefpV1v</a></p>
        <p><?php _e( 'Or support my work by a friendly link to one of these websites:', 'tag-groups' ) ?>
        <ul>
            <li><a href="https://unterwegs-in-tschechien.cz" target="_blank">unterwegs-in-tschechien.cz</a></li>
            <li><a href="https://www.weirdthingsinprague.com" target="_blank">www.weirdthingsinprague.com</a></li>
            <li><a href="http://www.myanmar-dictionary.org" target="_blank">www.myanmar-dictionary.org</a></li>
            <li><a href="http://www.burma-center.org" target="_blank">www.burma-center.org</a></li>
        </ul>
        <p><?php printf( __( 'If you travel a lot, you can also <a %s>use this affiliate link to book a hotel</a> so that I get a percentage of the sales.', 'tag-groups' ), 'href="http://www.booking.com/index.html?aid=947828" target="_blank"' );
        ?></p>
        <p><?php printf( __( 'You can also <a %s>donate to my favourite charity</a>.', 'tag-groups' ), 'href="http://www.burma-center.org/support-our-work/" target="_blank"' ); ?></p>
        <?php _e( 'Thanks!', 'tag-groups' ) ?></p>
        <p>Christoph</p>

        <h2><?php _e( 'Credits', 'tag-groups' ) ?></h2>
        <ul>
            <li>This plugin uses css and images by <a href="http://jqueryui.com/" target="_blank">jQuery UI</a>. Their license is part of this package.</li>
            <li>Spanish translation (es_ES) by <a href="http://www.webhostinghub.com/" target="_blank">Andrew Kurtis</a></li>
        </ul>
    <?php endif; ?>
    </div>

    <?php

}


/**
 * After deleting a tag group, this function removes its ID from the previously assigned tags.
 * 
 * @param int $id
 */
function tg_unassign( $id )
{

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $posttags = get_terms( $tag_group_taxonomy, array('hide_empty' => false) );


    foreach ( $posttags as $tag ) {

        if ( ($tag->term_group == $id) || ($id == 0) ) {

            $tag->term_group = 0;

            wp_update_term( $tag->term_id, $tag->taxonomy, array('term_group' => $tag->term_group) );
        }
    }

}


/**
 * Returns number of tags that are assigned to a given tag group. Needed for the table.
 * 
 * @param int $id
 * @return int
 */
function tg_number_assigned( $id )
{

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $posttags = get_terms( $tag_group_taxonomy, array('hide_empty' => false) );

    $number = 0;

    foreach ( $posttags as $tag ) {

        if ( $tag->term_group == $id ) {
            $number++;
        }
    }

    return $number;

}


/**
 * A piece of script for the tabs to work, including options, for each individual cloud
 * 
 * @param type $id
 * @param type $option_mouseover
 * @param type $option_collapsible
 * @return string
 */
function tg_custom_js( $id = null, $option_mouseover = null, $option_collapsible = null, $option_active = null )
{

    $options = array();

    if ( isset( $option_mouseover ) ) {

        if ( $option_mouseover ) {

            $options[] = 'event: "mouseover"';
        }
    } else {

        if ( get_option( 'tag_group_mouseover', '' ) ) {

            $options[] = 'event: "mouseover"';
        }
    }

    if ( isset( $option_collapsible ) ) {

        if ( $option_collapsible ) {

            $options[] = 'collapsible: true';
        }
    } else {

        if ( get_option( 'tag_group_collapsible', '' ) ) {

            $options[] = 'collapsible: true';
        }
    }

    if ( isset( $option_active ) ) {

        if ( $option_active ) {

            $options[] = 'active: true';
        } else {

            $options[] = 'active: false';
        }
    }

    if ( empty( $options ) ) {

        $options_serialized = '';
    } else {

        $options_serialized = "{\n" . implode( ",\n", $options ) . "\n}";
    }

    if ( !isset( $id ) ) {

        $id = 'tag-groups-cloud-tabs';
    } else {

        $id = sanitize_html_class( $id );
    }

    $html = '
<!-- begin Tag Groups plugin -->
<script type="text/javascript">
	jQuery(function() {
		jQuery( "#' . $id . '" ).tabs(' . $options_serialized . ');
	});
</script>
<!-- end Tag Groups plugin -->
';

    return $html;

}


/**
 * A piece of script for the tabs to work, including options, for each individual cloud
 * 
 * @param type $id
 * @param type $option_mouseover
 * @param type $option_collapsible
 * @return string
 */
function tg_custom_js_accordion( $id = null, $option_mouseover = null, $option_collapsible = null, $option_active = null )
{

    $options = array();

    if ( isset( $option_mouseover ) ) {

        if ( $option_mouseover ) {

            $options[] = 'event: "mouseover"';
        }
    } else {

        if ( get_option( 'tag_group_mouseover', '' ) ) {

            $options[] = 'event: "mouseover"';
        }
    }

    if ( isset( $option_collapsible ) ) {

        if ( $option_collapsible ) {

            $options[] = 'collapsible: true';
        }
    } else {

        if ( get_option( 'tag_group_collapsible', '' ) ) {

            $options[] = 'collapsible: true';
        }
    }

    /*
      if ( isset( $option_active ) ) {

      if ( $option_active ) {

      $options[] = 'active: true';
      } else {

      $options[] = 'active: false';
      }
      }
     */

    if ( empty( $options ) ) {

        $options_serialized = '';
    } else {

        $options_serialized = "{\n" . implode( ",\n", $options ) . "\n}";
    }

    if ( !isset( $id ) ) {

        $id = 'tag-groups-cloud-accordion';
    } else {

        $id = sanitize_html_class( $id );
    }

    $html = '
<!-- begin Tag Groups plugin -->
<script type="text/javascript">
	jQuery(function() {
		jQuery( "#' . $id . '" ).accordion(' . $options_serialized . ');
	});
</script>
<!-- end Tag Groups plugin -->
';

    return $html;

}


/**
 * Calculates the font size for the cloud tag for a particular tag ($min, $max and $size with same unit, e.g. pt.)
 * 
 * @param int $count
 * @param int $min
 * @param int $max
 * @param int $smallest
 * @param int $largest
 * @return int
 */
function tg_font_size( $count, $min, $max, $smallest, $largest )
{

    if ( $max > $min ) {

        $size = round( ($count - $min) * ($largest - $smallest) / ($max - $min) + $smallest );
    } else {

        $size = round( $smallest );
    }

    return $size;

}


/**
 * Makes sure that WPML knows about the tag group label that can have different language versions.
 * 
 * @param string $name
 * @param string $value
 */
function tg_register_string_wpml( $name, $value )
{


    if ( function_exists( 'icl_register_string' ) ) {
        icl_register_string( 'tag-groups', $name, $value );
    }

}


/**
 * Asks WPML to forget about $name
 * 
 * @param stirn $name
 */
function tg_unregister_string_wpml( $name )
{

    if ( function_exists( 'icl_unregister_string' ) ) {
        icl_unregister_string( 'tag-groups', $name );
    }

}


/**
 * If WPML is installed: return translation; otherwise return original
 * 
 * @param type $name
 * @param type $string
 * @return type
 */
function tg_translate_string_wpml( $name, $string )
{

    if ( function_exists( 'icl_t' ) ) {
        return icl_t( 'tag-groups', $name, $string );
    } else {
        return $string;
    }

}


/**
 * swaps the position of two elements in an array - needed for changing the order of list items
 * 
 * @param array $ary
 * @param int $element1
 * @param int $element2
 */
function tg_swap( &$ary, $element1, $element2 )
{

    $temp = $ary[$element1];

    $ary[$element1] = $ary[$element2];

    $ary[$element2] = $temp;

}


/**
 * Good idea to purge the cache after changing theme options - else your visitors won't see the change for a while. Currently implemented for W3T Total Cache and WP Super Cache.
 */
function tg_clear_cache()
{


    if ( function_exists( 'flush_pgcache' ) ) {
        flush_pgcache;
    }

    if ( function_exists( 'flush_minify' ) ) {
        flush_minify;
    }

    if ( function_exists( 'wp_cache_clear_cache' ) ) {
        wp_cache_clear_cache();
    }

}


/**
 * Adds a pull-down menu to the filters above the posts.
 * Based on the code by Ohad Raz, http://wordpress.stackexchange.com/q/45436/2487
 * License: Creative Commons Share Alike
 * @return void
 */
function tg_add_filter()
{

    $show_filter_posts = get_option( 'tag_group_show_filter', true );

    $tg_type = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( !$show_filter_posts || (count( $tg_type ) > 1) ) {
        return;
    }

    $type = ( isset( $_GET['post_type'] ) ) ? sanitize_title( $_GET['post_type'] ) : 'post';

    if ( count( array_intersect( $tg_type, get_object_taxonomies( $type ) ) ) ) {

        $tag_group_labels = get_option( 'tag_group_labels', array() );

        $tag_group_ids = get_option( 'tag_group_ids', array() );

        $values = array();

        $number_of_tag_groups = count( $tag_group_labels ) - 1;

        $values[0] = __( 'not assigned', 'tag-groups' );

        for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) {

            $values[$tag_group_ids[$i]] = $tag_group_labels[$i];
        }
        ?>
        <select name="tg_filter_posts_value">
            <option value=""><?php _e( 'Filter by tag group ', 'tag-groups' ); ?></option>
            <?php
            $current_v = isset( $_GET['tg_filter_posts_value'] ) ? sanitize_text_field( $_GET['tg_filter_posts_value'] ) : '';

            foreach ( $values as $value => $label ) {

                printf( '<option value="%s"%s>%s</option>', $value, ( $current_v != '' && $value == $current_v ) ? ' selected="selected"' : '', $label );
            }
            ?>
        </select>
        <?php
    }

}


/**
 * Applies the filter, if used.
 * Based on the code by Ohad Raz, http://wordpress.stackexchange.com/q/45436/2487
 * License: Creative Commons Share Alike
 * 
 * @global type $pagenow
 * @param type $query
 * @return type
 */
function tg_apply_filter( $query )
{

    global $pagenow;

    $filter_terms = array();

    $show_filter_posts = get_option( 'tag_group_show_filter', true );

    $tg_type = get_option( 'tag_group_taxonomy', array('post_tag') );

    if ( !$show_filter_posts || (count( $tg_type ) > 1) ) {
        return;
    }

    $type = ( isset( $_GET['post_type'] ) ) ? sanitize_title( $_GET['post_type'] ) : 'post';

    if ( count( array_intersect( $tg_type, get_object_taxonomies( $type ) ) ) && is_admin() && $pagenow == 'edit.php' && isset( $_GET['tg_filter_posts_value'] ) && $_GET['tg_filter_posts_value'] != '' ) {

        $terms = get_terms( $tg_type );

        $tag_group_ids = get_option( 'tag_group_ids', array() );

        $tg_selected = (int) $_GET['tg_filter_posts_value'];

        if ( $terms ) {

            if ( $tg_selected == '0' ) {

                foreach ( $terms as $term ) {

                    if ( $term->term_group != 0 && in_array( $term->term_group, $tag_group_ids ) ) {

                        $filter_terms[$term->taxonomy][] = $term->term_id;
                    }
                }

                foreach ( $filter_terms as $taxonomy => $filter_terms_item ) {

                    $tg_prefix = ($taxonomy == 'post_tag') ? 'tag' : $taxonomy;

                    $query->query_vars[$tg_prefix . '__not_in'] = $filter_terms_item;
                }
            } else {

                $filter_terms[] = 0;

                foreach ( $terms as $term ) {

                    if ( $term->term_group == $tg_selected ) {

                        $filter_terms[$term->taxonomy][] = $term->term_id;
                    }
                }

                foreach ( $filter_terms as $taxonomy => $filter_terms_item ) {

                    $tg_prefix = ($taxonomy == 'post_tag') ? 'tag' : $taxonomy;

                    $query->query_vars[$tg_prefix . '__in'] = $filter_terms_item;
                }
            }
        }
    }

}

/*
 * The following functions must be publically accessible.
 */


/**
 * 
 * Rendering the tabbed tag cloud, usually by a shortcode, or returning a multidimensional array
 * 
 * @param array $atts
 * @param bool $return_array
 * @return int
 */
function tag_groups_cloud( $atts = array(), $return_array = false )
{

    $include_array = array();

    $html_tabs = array();

    $html_tags = array();

    $post_id_terms = array();

    $assigned_terms = array();

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;

    extract( shortcode_atts( array(
        'active' => null,
        'adjust_separator_size' => false,
        'amount' => 40,
        'append' => '',
        'assigned_class' => null,
        'collapsible' => null,
        'div_class' => 'tag-groups-cloud-tabs',
        'div_id' => 'tag-groups-cloud-tabs',
        'groups_post_id' => -1,
        'hide_empty_tabs' => false,
        'hide_empty' => true,
        'include' => '',
        'largest' => 22,
        'link_target' => '',
        'mouseover' => null,
        'order' => 'ASC',
        'orderby' => 'name',
        'prepend' => '',
        'separator_size' => 12,
        'separator' => '',
        'show_all_groups' => false,
        'show_tabs' => '1',
        'show_tag_count' => true,
        'smallest' => 12,
        'tags_post_id' => -1,
        'taxonomy' => null,
        'ul_class' => ''
                    ), $atts ) );

    if ( $smallest < 1 ) {
        $smallest = 1;
    }

    if ( $largest < $smallest ) {
        $largest = $smallest;
    }

    if ( $amount < 1 ) {
        $amount = 1;
    }

    if ( isset( $taxonomy ) ) {

        if ( empty( $taxonomy ) ) {

            unset( $taxonomy );
        } else {

            $taxonomy_array = explode( ',', $taxonomy );

            $taxonomy_array = array_filter( array_map( 'trim', $taxonomy_array ) );
        }
    }

    $posttags = get_terms( $tag_group_taxonomy, array('hide_empty' => $hide_empty, 'orderby' => $orderby, 'order' => $order) );

    $div_id_output = $div_id ? ' id="' . sanitize_html_class( $div_id ) . '"' : '';

    $div_class_output = $div_class ? ' class="' . sanitize_html_class( $div_class ) . '"' : '';

    $ul_class_output = $ul_class ? ' class="' . sanitize_html_class( $ul_class ) . '"' : '';

    if ( !empty( $include ) ) {
        $include_array = explode( ',', str_replace( ' ', '', $include ) );
    }

    if ( $separator_size < 1 ) {
        $separator_size = 12;
    } else {
        $separator_size = (int) $separator_size;
    }

    /*
     *  applying parameter tags_post_id
     */

    if ( $tags_post_id < -1 ) {
        $tags_post_id = -1;
    }

    if ( $tags_post_id == 0 ) {
        $tags_post_id = get_the_ID();
    }

    if ( $tags_post_id > 0 ) {

        /*
         *  we have a particular post ID
         *  get all tags of this post
         */

        foreach ( $tag_group_taxonomy as $taxonomy_item ) {

            if ( isset( $taxonomy ) && !in_array( $taxonomy_item, $taxonomy_array ) ) {
                continue;
            }

            $terms = get_the_terms( (int) $tags_post_id, $taxonomy_item );

            /*
             *  merging the results of selected taxonomies
             */

            if ( !empty( $terms ) && is_array( $terms ) ) {
                $post_id_terms = array_merge( $post_id_terms, $terms );
            }
        }

        if ( $post_id_terms ) {

            /*
             *  clean all others from $posttags
             */
            foreach ( $posttags as $key => $tag ) {

                $found = false;

                foreach ( $post_id_terms as $id_tag ) {

                    if ( $tag->term_id == $id_tag->term_id ) {

                        $found = true;

                        break;
                    }
                }

                if ( !empty( $assigned_class ) ) {

                    /*
                     *  Keep all terms but mark for different styling
                     */

                    if ( $found ) {
                        $assigned_terms[$tag->term_id] = true;
                    }
                } else {

                    /*
                     *  Remove unused terms.
                     */

                    if ( !$found ) {
                        unset( $posttags[$key] );
                    }
                }
            }
        } else {
            /*
             * post has no tags
             */

            $posttags = array();
        }
    }


    /*
     *  applying parameter groups_post_id
     */

    if ( $groups_post_id < -1 ) {
        $groups_post_id = -1;
    }

    if ( $groups_post_id == 0 ) {
        $groups_post_id = get_the_ID();
    }

    if ( $groups_post_id ) {

        /*
         *  get all tags of this post
         */
        foreach ( $tag_group_taxonomy as $taxonomy_item ) {

            if ( isset( $taxonomy ) && !in_array( $taxonomy_item, $taxonomy_array ) ) {
                continue;
            }

            $terms = get_the_terms( (int) $groups_post_id, $taxonomy_item );

            if ( !empty( $terms ) && is_array( $terms ) ) {
                $post_id_terms = array_merge( $post_id_terms, $terms );
            }
        }

        /*
         *  get all involved groups, append them to $include
         */
        if ( $post_id_terms ) {

            foreach ( $post_id_terms as $term ) {

                if ( !in_array( $term->term_group, $include_array ) ) {
                    $include_array[] = $term->term_group;
                }
            }
        }
    }


    if ( $return_array ) {

        /*
         *  return tags as array
         */

        $output = array();

        for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) {

            if ( $show_all_groups || empty( $include_array ) || in_array( $tag_group_ids[$i], $include_array ) ) {

                $output[$i]['name'] = tg_translate_string_wpml( 'Group Label ID ' . $tag_group_ids[$i], $tag_group_labels[$i] );

                $output[$i]['term_group'] = $tag_group_ids[$i];

                if ( $posttags ) {

                    /*
                     *  find minimum and maximum of quantity of posts for each tag
                     */
                    $count_amount = 0;

                    $max = 0;

                    $min = 9999999;

                    foreach ( $posttags as $tag ) {

                        if ( $count_amount >= $amount ) {
                            break;
                        }

                        if ( $tag->term_group == $tag_group_ids[$i] ) {

                            if ( $tag->count > $max ) {
                                $max = $tag->count;
                            }

                            if ( $tag->count < $min ) {
                                $min = $tag->count;
                            }

                            $count_amount++;
                        }
                    }

                    $count_amount = 0;

                    foreach ( $posttags as $tag ) {

                        if ( $count_amount >= $amount ) {
                            break;
                        }

                        if ( $tag->term_group == $tag_group_ids[$i] ) {

                            $output[$i]['tags'][$count_amount]['term_id'] = $tag->term_id;

                            $output[$i]['tags'][$count_amount]['link'] = get_term_link( $tag->slug, $tag->taxonomy );

                            $output[$i]['tags'][$count_amount]['description'] = $tag->description;

                            $output[$i]['tags'][$count_amount]['count'] = $tag->count;

                            $output[$i]['tags'][$count_amount]['slug'] = $tag->slug;

                            $output[$i]['tags'][$count_amount]['name'] = $tag->name;

                            $output[$i]['tags'][$count_amount]['tg_font_size'] = tg_font_size( $tag->count, $min, $max, $smallest, $largest );

                            if ( !empty( $assigned_class ) ) {

                                $output[$i]['tags'][$count_amount]['assigned'] = $assigned_terms[$tag->term_id];
                            }

                            $count_amount++;
                        }
                    }

                    $output[$i]['amount'] = $count_amount;
                }
            }
        }

        return $output;
    } else {

        /*
         *  return as html (in the shape of a tabbed cloud)
         */

        $html = '<div' . $div_id_output . $div_class_output . '>';

        /*
         *  render the tabs
         */

        if ( $show_tabs == '1' ) {

            $html_tabs[0] = '<ul' . $ul_class_output . '>';

            for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) {

                if ( $show_all_groups || empty( $include_array ) || in_array( $tag_group_ids[$i], $include_array ) ) {

                    $html_tabs[$i] = '<li><a href="#tabs-' . $i . '" >' . tg_translate_string_wpml( 'Group Label ID ' . $tag_group_ids[$i], $tag_group_labels[$i] ) . '</a></li>';
                }
            }

            $html_tabs[] .= '</ul>';
        }

        /*
         *  render the tab content
         */

        for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) {

            if ( $show_all_groups || empty( $include_array ) || in_array( $tag_group_ids[$i], $include_array ) ) {

                $html_tags[$i] = '<div id="tabs-' . $i . '">';

                if ( $posttags ) {

                    // find minimum and maximum of quantity of posts for each tag
                    $count_amount = 0;

                    $max = 0;

                    $min = 9999999;

                    foreach ( $posttags as $tag ) {

                        if ( $count_amount > $amount )
                            break;

                        if ( $tag->term_group == $tag_group_ids[$i] ) {

                            if ( $tag->count > $max ) {
                                $max = $tag->count;
                            }

                            if ( $tag->count < $min ) {
                                $min = $tag->count;
                            }

                            $count_amount++;
                        }
                    }

                    $count_amount = 0;

                    foreach ( $posttags as $tag ) {

                        $other_tag_classes = '';

                        $description = '';

                        if ( $count_amount >= $amount ) {
                            break;
                        }

                        if ( $tag->term_group == $tag_group_ids[$i] ) {

                            $tag_link = get_term_link( $tag->slug, $tag->taxonomy );

                            $font_size = tg_font_size( $tag->count, $min, $max, $smallest, $largest );

                            $font_size_tag = $adjust_separator_size ? $font_size : $separator_size;

                            if ( $count_amount > 0 ) {
                                $html_tags[$i] .= '<span style="font-size:' . $font_size_tag . 'px">' . $separator . '</span> ';
                            }

                            if ( !empty( $assigned_class ) ) {

                                if ( $assigned_terms[$tag->term_id] ) {

                                    $other_tag_classes = ' ' . $assigned_class . '_1';
                                } else {

                                    $other_tag_classes = ' ' . $assigned_class . '_0';
                                }
                            }

                            $description = !empty( $tag->description ) ? esc_html( $tag->description ) . ' ' : '';

                            $tag_count = $show_tag_count ? '(' . $tag->count . ')' : '';

                            $link_target_html = !empty( $link_target ) ? 'target="' . $link_target . '"' : '';

                            $html_tags[$i] .= '<a href="' . $tag_link . '" ' . $link_target_html . ' title="' . $description . $tag_count . '"  class="' . $tag->slug . $other_tag_classes . '">'
                                    . '<span style="font-size:' . $font_size . 'px">' . esc_html( $prepend ) . $tag->name . esc_html( $append ) . ''
                                    . '</span>'
                                    . '</a>&nbsp; ';

                            $count_amount++;
                        }
                    }
                }

                if ( $hide_empty_tabs && !$count_amount ) {

                    unset( $html_tabs[$i] );

                    unset( $html_tags[$i] );
                } else {

                    $html_tags[$i] .= '</div>';
                }
            }
        }

        /*
         * assemble tabs
         */
        foreach ( $html_tabs as $html_tab ) {
            $html .= $html_tab;
        }

        /*
         * assemble tags
         */
        foreach ( $html_tags as $html_tag ) {
            $html .= $html_tag;
        }

        $html .= '</div>';

        $html .= tg_custom_js( $div_id, $mouseover, $collapsible, $active );

        return $html;
    }

}


/**
 * 
 * Rendering the accordion tag cloud
 * 
 * @param array $atts
 * @param bool $return_array
 * @return int
 */
function tag_groups_accordion( $atts = array() )
{

    $include_array = array();

    $post_id_terms = array();

    $assigned_terms = array();

    $tag_group_labels = get_option( 'tag_group_labels', array() );

    $tag_group_ids = get_option( 'tag_group_ids', array() );

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $number_of_tag_groups = count( $tag_group_labels ) - 1;

    extract( shortcode_atts( array(
        'active' => null,
        'adjust_separator_size' => false,
        'amount' => 40,
        'append' => '',
        'assigned_class' => null,
        'collapsible' => null,
        'div_class' => 'tag-groups-cloud-tabs', // change for different themes tabs vs. accordion
        'div_id' => 'tag-groups-cloud-accordion',
        'groups_post_id' => -1,
        'hide_empty_content' => false,
        'hide_empty' => true,
        'include' => '',
        'largest' => 22,
        'link_target' => '',
        'mouseover' => null,
        'order' => 'ASC',
        'orderby' => 'name',
        'prepend' => '',
        'separator_size' => 12,
        'separator' => '',
        'show_all_groups' => false,
        'show_accordion' => '1',
        'show_tag_count' => true,
        'smallest' => 12,
        'tags_post_id' => -1,
        'taxonomy' => null,
        'inner_div_class' => '',
        'header_class' => ''
                    ), $atts ) );

    if ( $smallest < 1 ) {
        $smallest = 1;
    }

    if ( $largest < $smallest ) {
        $largest = $smallest;
    }

    if ( $amount < 1 ) {
        $amount = 1;
    }

    if ( isset( $taxonomy ) ) {

        if ( empty( $taxonomy ) ) {

            unset( $taxonomy );
        } else {

            $taxonomy_array = explode( ',', $taxonomy );

            $taxonomy_array = array_filter( array_map( 'trim', $taxonomy_array ) );
        }
    }

    $posttags = get_terms( $tag_group_taxonomy, array('hide_empty' => $hide_empty, 'orderby' => $orderby, 'order' => $order) );

    $div_id_output = $div_id ? ' id="' . sanitize_html_class( $div_id ) . '"' : '';

    $div_class_output = $div_class ? ' class="' . sanitize_html_class( $div_class ) . '"' : '';

    $header_class_output = $header_class ? ' class="' . sanitize_html_class( $header_class ) . '"' : '';

    $inner_div_class_output = $inner_div_class ? ' class="' . sanitize_html_class( $inner_div_class ) . '"' : '';

    if ( !empty( $include ) ) {
        $include_array = explode( ',', str_replace( ' ', '', $include ) );
    }

    if ( $separator_size < 1 ) {
        $separator_size = 12;
    } else {
        $separator_size = (int) $separator_size;
    }

    /*
     *  applying parameter tags_post_id
     */

    if ( $tags_post_id < -1 ) {
        $tags_post_id = -1;
    }

    if ( $tags_post_id == 0 ) {
        $tags_post_id = get_the_ID();
    }

    if ( $tags_post_id ) {

        /*
         *  get all tags of this post
         */

        foreach ( $tag_group_taxonomy as $taxonomy_item ) {

            if ( isset( $taxonomy ) && !in_array( $taxonomy_item, $taxonomy_array ) ) {
                continue;
            }

            $terms = get_the_terms( (int) $tags_post_id, $taxonomy_item );

            /*
             *  merging the results of selected taxonomies
             */

            if ( !empty( $terms ) && is_array( $terms ) ) {
                $post_id_terms = array_merge( $post_id_terms, $terms );
            }
        }

        if ( $post_id_terms ) {

            /*
             *  clean all others from $posttags
             */
            foreach ( $posttags as $key => $tag ) {

                $found = false;

                foreach ( $post_id_terms as $id_tag ) {

                    if ( $tag->term_id == $id_tag->term_id ) {

                        $found = true;

                        break;
                    }
                }

                if ( !empty( $assigned_class ) ) {

                    /*
                     *  Keep all terms but mark for different styling
                     */

                    if ( $found ) {
                        $assigned_terms[$tag->term_id] = true;
                    }
                } else {

                    /*
                     *  Remove unused terms.
                     */

                    if ( !$found ) {
                        unset( $posttags[$key] );
                    }
                }
            }
        }
    }


    /*
     *  applying parameter groups_post_id
     */

    if ( $groups_post_id < -1 ) {
        $groups_post_id = -1;
    }

    if ( $groups_post_id == 0 ) {
        $groups_post_id = get_the_ID();
    }

    if ( $groups_post_id ) {

        /*
         *  get all tags of this post
         */
        foreach ( $tag_group_taxonomy as $taxonomy_item ) {

            if ( isset( $taxonomy ) && !in_array( $taxonomy_item, $taxonomy_array ) ) {
                continue;
            }

            $terms = get_the_terms( (int) $groups_post_id, $taxonomy_item );

            if ( !empty( $terms ) && is_array( $terms ) ) {
                $post_id_terms = array_merge( $post_id_terms, $terms );
            }
        }

        /*
         *  get all involved groups, append them to $include
         */
        if ( $post_id_terms ) {

            foreach ( $post_id_terms as $term ) {

                if ( !in_array( $term->term_group, $include_array ) ) {
                    $include_array[] = $term->term_group;
                }
            }
        }
    }




    /*
     *  return as html (in the shape of clouds in an accordion)
     */

    $html = '<div' . $div_id_output . $div_class_output . '>';


    for ( $i = 1; $i <= $number_of_tag_groups; $i++ ) {

        $html_header = '';

        $html_tags = '';

        if ( $show_all_groups || empty( $include_array ) || in_array( $tag_group_ids[$i], $include_array ) ) {

            /*
             *  render the accordion headers
             */

            if ( $show_accordion == '1' ) {

                $html_header .= '<h3' . $header_class_output . '>'
                        . tg_translate_string_wpml( 'Group Label ID ' . $tag_group_ids[$i], $tag_group_labels[$i] )
                        . '</h3>';
            }

            /*
             *  render the accordion content
             */

            if ( $posttags ) {


                // find minimum and maximum of quantity of posts for each tag
                $count_amount = 0;

                $max = 0;

                $min = 9999999;

                foreach ( $posttags as $tag ) {

                    if ( $count_amount > $amount ) {
                        break;
                    }

                    if ( $tag->term_group == $tag_group_ids[$i] ) {

                        if ( $tag->count > $max ) {
                            $max = $tag->count;
                        }

                        if ( $tag->count < $min ) {
                            $min = $tag->count;
                        }

                        $count_amount++;
                    }
                }

                $count_amount = 0;

                foreach ( $posttags as $tag ) {

                    $other_tag_classes = '';

                    $description = '';

                    if ( $count_amount >= $amount ) {
                        break;
                    }

                    if ( $tag->term_group == $tag_group_ids[$i] ) {

                        $tag_link = get_term_link( $tag->slug, $tag->taxonomy );

                        $font_size = tg_font_size( $tag->count, $min, $max, $smallest, $largest );

                        $font_size_tag = $adjust_separator_size ? $font_size : $separator_size;

                        if ( $count_amount > 0 ) {
                            $html_tags .= '<span style="font-size:' . $font_size_tag . 'px">' . $separator . '</span> ';
                        }

                        if ( !empty( $assigned_class ) ) {

                            if ( $assigned_terms[$tag->term_id] ) {

                                $other_tag_classes = ' ' . $assigned_class . '_1';
                            } else {

                                $other_tag_classes = ' ' . $assigned_class . '_0';
                            }
                        }

                        $description = !empty( $tag->description ) ? htmlentities( $tag->description, ENT_COMPAT | ENT_HTML401, "UTF-8" ) . ' ' : '';

                        $tag_count = $show_tag_count ? '(' . $tag->count . ')' : '';

                        $link_target_html = !empty( $link_target ) ? 'target="' . $link_target . '"' : '';

                        $html_tags .= '<a href="' . $tag_link . '" ' . $link_target_html . ' title="' . $description . $tag_count . '"  class="' . $tag->slug . $other_tag_classes . '"><span style="font-size:' . $font_size . 'px">' . sanitize_text_field( $prepend ) . $tag->name . sanitize_text_field( $append ) . '</span></a>&nbsp; ';

                        $count_amount++;
                    }
                }
            }

            if ( !$hide_empty_content || $count_amount ) {

                $html .= $html_header . '<div' . $inner_div_class_output . '>' . $html_tags . '</div>';
            }
        }
    }

    /*
     * Closing the accordion
     */
    $html .= '</div>';

    $html .= tg_custom_js_accordion( $div_id, $mouseover, $collapsible, $active );

    return $html;

}


/**
 * Checks if the post with $post_id has a tag that is in the tag group with $tag_group_id.
 * 
 * @param int $post_id
 * @param int $tag_group_id
 * @return boolean
 */
function post_in_tag_group( $post_id, $tag_group_id )
{

    $tag_group_taxonomy = get_option( 'tag_group_taxonomy', array('post_tag') );

    $tags = get_the_terms( $post_id, $tag_group_taxonomy );

    if ( $tags ) {

        foreach ( $tags as $tag ) {

            if ( $tag->term_group == $tag_group_id ) {
                return true;
            }
        }
    } else {

        return false;
    }

    return false;

}

/*
    guess what - the end
*/
