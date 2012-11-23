<?php
/*
Plugin Name: Ostrichcize
Plugin URI: https://github.com/tollmanz/ostrichcize
Description: Hides PHP errors reported by specified plugins or themes.
Author: Zack Tollman
Version: 0.1
Author URI: http://tollmanz.com
*/

if ( ! class_exists( 'Struthio_Camelus' ) ) :

/**
 * The class that defines all functionality for the plugin.
 */
class Struthio_Camelus {

	/**
	 * Holds the plugin slugs to exclude from error reporting.
	 *
	 * @since	0.1
	 * @var		array
	 */
	private $_plugins = array();

	/**
	 * Holds the paths to exclude from error reporting.
	 *
	 * @since	0.1
	 * @var		array
	 */
	private $_excluded_paths = array();

	/**
	 * The one instance of Struthio_Camelus.
	 *
	 * @since	0.1
	 * @var		Struthio_Camelus
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Struthio_Camelus instance.
	 *
	 * @uses	Struthio_Camelus, Struthio_Camelus::init
	 *
	 * @since	0.1
	 * @return	Struthio_Camelus
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Struthio_Camelus();
			self::$instance->_init();
		}
		return self::$instance;
	}

	/**
	 * Dummy constructor for PHP 5+ compatibility.
	 *
	 * @since	0.1
	 * @return	Struthio_Camelus
	 */
	private function __construct() {}

	/**
	 * Setup the basic plugin actions.
	 *
	 * @uses	set_error_handler, apply_filters, Struthio_Camelus::_prepend_dir_to_slugs, get_stylesheet_directory
	 *
	 * @since	0.1
	 * @return	void
	 */
	private function _init() {
		set_error_handler( array( $this, 'error_handler' ) );

		$this->_plugins = apply_filters( 'ostrichcized_plugins', array() );

		$this->_excluded_paths = $this->_prepend_dir_to_slugs( WP_PLUGIN_DIR, $this->_plugins );

		if ( apply_filters( 'ostrichcize_theme', false ) )
			$this->_excluded_paths[] = get_stylesheet_directory();
	}

	/**
	 * Takes an array of slugs and prepends it with a directory path.
	 *
	 * @uses 	trailingslashit
	 *
	 * @since 	0.1
	 *
	 * @param 	string		$dir		Directory path to prepend.
	 * @param 	array		$slugs		Slugs that are prepended.
	 * @return 	array					Array of slugs prepended with a specified directory path.
	 */
	private function _prepend_dir_to_slugs( $dir, $slugs ) {
		$paths = array();

		foreach ( $slugs as $slug ) {
			$path    = trailingslashit( $dir ) . $slug;
			$paths[] = trailingslashit( $path );
		}

		return $paths;
	}

	/**
	 * Override the default error handler to suppress some errors from being reported.
	 *
	 * PHP allows a developer to define a custom error handler to print custom error messages. This function provides a
	 * custom error handler, which serves suppress errors that show in certain paths. If one of the paths defined in
	 * $this->_excluded_paths is present in either the $errstr or the $errfile, the error will not be reported. If this
	 * function returns (bool) true, no error is reported. The normal error handling (and other error handler
	 * extensions, such as Xdebug) will be used if this function returns (bool) false.
	 *
	 * @uses	strpos
	 *
	 * @since	0.1
	 *
	 * @param 	string		$errno		The error number.
	 * @param 	string		$errstr		The error message.
	 * @param 	string		$errfile	Path to the file that caused the error.
	 * @param 	int			$errline	Line number of the error.
	 * @return 	bool					True to success error reporting; false to use default error handler.
	 */
	public function error_handler( $errno, $errstr, $errfile, $errline ) {
		foreach ( $this->_excluded_paths as $path ) {
			if ( false !== strpos( $errstr, $path ) )
				return true;

			if ( false !== strpos( $errfile, $path ) )
				return true;
		}

		// The path was not found, so report the error
		return false;
	}
}

/**
 * Wrapper function to return the one Struthio_Camelus instance.
 *
 * @return	Struthio_Camelus
 */
function struthrio_get_the_ostrich() {
	return Struthio_Camelus::instance();
}

// Initiate the plugin functionality
add_action( 'plugins_loaded', 'struthrio_get_the_ostrich' );

endif;