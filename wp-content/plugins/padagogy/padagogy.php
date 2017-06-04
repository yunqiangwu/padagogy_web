<?php
/**
 * Plugin Name: Padagogy
 * Plugin URI:  https://github.com/yunqiangwu/Padagogy
 * Description: Padagogy website
 * Version:     0.0.0
 * Author:      wayne
 * Author URI:  https://github.com/yunqiangwu/Padagogy
 * Donate link: https://github.com/yunqiangwu/Padagogy
 * License:     GPLv2
 * Text Domain: padagogy
 * Domain Path: /languages
 *
 * @link    https://github.com/yunqiangwu/Padagogy
 *
 * @package Padagogy
 * @version 0.0.0
 *
 * Built using generator-plugin-wp (https://github.com/WebDevStudios/generator-plugin-wp)
 */

/**
 * Copyright (c) 2017 wayne (email : jajabjbj@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Autoloads files with classes when needed.
 *
 * @since  0.0.0
 * @param  string $class_name Name of the class being requested.
 */
function padagogy_autoload_classes( $class_name ) {

	// If our class doesn't have our prefix, don't load it.
	if ( 0 !== strpos( $class_name, 'P_' ) ) {
		return;
	}

	// Set up our filename.
	$filename = strtolower( preg_replace( '/(?!^)([A-Z])/', '-\1', substr( $class_name, strlen( 'P_' ) ) ) );

	// Include our file.
	Padagogy::include_file( 'includes/class-' . $filename );
}
include_once 'includes/functions.php';
spl_autoload_register( 'padagogy_autoload_classes' );

/**
 * Main initiation class.
 *
 * @since  0.0.0
 */
final class Padagogy {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	const VERSION = '0.0.0';

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $basename = '';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  0.0.0
	 */
	protected $activation_errors = array();

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    Padagogy
	 * @since  0.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   0.0.0
	 * @return  Padagogy A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *W QE     W Q EEewy76
	 * @since  0.0.0
	 */
	protected function __construct() {


		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );


        define('PADAGOGY_FILE_PATH', dirname(__FILE__));
        define('PADAGOGY_DIR_NAME', basename(PADAGOGY_FILE_PATH));


        register_nav_menus(
            array('padagogy-menu' => 'Padagogy多重筛选菜单')
        );

        add_filter('nav_menu_link_attributes',function( $atts, $item, $args){
            if($args->theme_location=='padagogy-menu') {
                $temp  = get_term($item->object_id);
                $temp && ($atts['data-slug']= $temp->slug );
                $atts['href'] = '#';
            }
            return $atts;
        },10,3);


        add_filter('wp_nav_menu_objects',function ($menu_items, $args){
            if($args->theme_location=='padagogy-menu') {
                foreach ($menu_items as $menu_item) {
                    $temp  = get_term($menu_item->object_id);
                    if($temp){
                        $temp = $temp->slug;
                        if($temp&& $_GET[$menu_item->object] && strpos($_GET[$menu_item->object],urldecode($temp))){
                            $menu_item->classes[]='current-menu-item';
                        }
                    }
                }
            }
            if($args->theme_location=='primary'){
                foreach ($menu_items as $menu_item) {
                    if($menu_item->url =='/padagogy/' && preg_match('/\/padagogy\/.+|app_classification[=\/].+/',$_SERVER['REQUEST_URI'])){
                        $menu_item->classes[]='current-menu-item';
                        break;
                    }
                }

            }
            return $menu_items;
        },10,2);
        add_filter('redirect_canonical',function ($redirect_url, $requested_url){
            $r =strpos($requested_url, '/?');//trailingslashit(home_url()).'?'
            if(is_int($r)&&$r>=0 ){
                return null;
            }
            return $redirect_url;
        },10,2);


        add_filter('comments_template',function ($theme_template){
            if(get_post_type()=='padagogy'){
                return  Padagogy::dir('includes/comments.php');
            }
            return $theme_template;
        },10,1);

        //pre_get_avatar
        add_filter('pre_get_avatar_data',function ($avatar,$commentObj){
            if(!($commentObj instanceof WP_Comment) ){
                return $avatar;
            }
            $post = get_post();
            if($post->post_type=='padagogy' && get_comment_meta($commentObj->comment_ID,"user_icon")){
                $avatar['url'] = get_comment_meta($commentObj->comment_ID,"user_icon")[0];
            }
            return $avatar;
        },10,2);

    }

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  0.0.0
	 */
	public function plugin_classes() {
		$this->PadagogyPostType = new P_PadagogyPostType( );


        add_action( 'widgets_init', create_function( '', 'register_widget( "P_PadagogyWidget" );' ) );



	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  0.0.0
	 */
	public function _activate() {
		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin.
	 * Uninstall routines should be in uninstall.php.
	 *
	 * @since  0.0.0
	 */
	public function _deactivate() {
		// Add deactivation cleanup functionality here.
	}

	/**
	 * Init hooks
	 *
	 * @since  0.0.0
	 */
	public function init() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Load translated strings for plugin.
		load_plugin_textdomain( 'padagogy', false, dirname( $this->basename ) . '/languages_d/' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  0.0.0
	 *
	 * @return boolean True if requirements met, false if not.
	 */
	public function check_requirements() {

		// Bail early if plugin meets requirements.
		if ( $this->meets_requirements() ) {
			return true;
		}

		// Add a dashboard notice.
		add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

		// Deactivate our plugin.
		add_action( 'admin_init', array( $this, 'deactivate_me' ) );

		// Didn't meet the requirements.
		return false;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  0.0.0
	 */
	public function deactivate_me() {

		// We do a check for deactivate_plugins before calling it, to protect
		// any developers from accidentally calling it too early and breaking things.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check that all plugin requirements are met.
	 *
	 * @since  0.0.0
	 *
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {

		// Do checks for required classes / functions or similar.
		// Add detailed messages to $this->activation_errors array.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * @since  0.0.0
	 */
	public function requirements_not_met_notice() {

		// Compile default message.
		$default_message = sprintf( __( 'Padagogy is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'padagogy' ), admin_url( 'plugins.php' ) );

		// Default details to null.
		$details = null;

		// Add details if any exist.
		if ( $this->activation_errors && is_array( $this->activation_errors ) ) {
			$details = '<small>' . implode( '</small><br /><small>', $this->activation_errors ) . '</small>';
		}

		// Output errors.
		?>
		<div id="message" class="error">
			<p><?php echo wp_kses_post( $default_message ); ?></p>
			<?php echo wp_kses_post( $details ); ?>
		</div>
		<?php
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $field Field to get.
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $filename Name of the file to be included.
	 * @return boolean          Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path.
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path.
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the Padagogy object and return it.
 * Wrapper for Padagogy::get_instance().
 *
 * @since  0.0.0
 * @return Padagogy  Singleton instance of plugin class.
 */
function padagogy() {
	return Padagogy::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( padagogy(), 'hooks' ) );

// Activation and deactivation.
register_activation_hook( __FILE__, array( padagogy(), '_activate' ) );
register_deactivation_hook( __FILE__, array( padagogy(), '_deactivate' ) );
