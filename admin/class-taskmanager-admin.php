<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    TaskManager
 * @subpackage TaskManager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TaskManager
 * @subpackage TaskManager/admin
 * @author     up7
 */
class TaskManager_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $taskmanager    The ID of this plugin.
	 */
	private $taskmanager;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $taskmanager       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $taskmanager, $version ) {

		$this->taskmanager = $taskmanager;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TaskManager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TaskManager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->taskmanager, plugin_dir_url( __FILE__ ) . 'css/taskmanager-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TaskManager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TaskManager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->taskmanager, plugin_dir_url( __FILE__ ) . 'js/taskmanager-admin.js', array( 'jquery' ), $this->version, false );

	}

}
