<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    TaskManager
 * @subpackage TaskManager/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TaskManager
 * @subpackage TaskManager/includes
 * @author     up7
 */
class TaskManager_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		TaskManager_Activator::create_table();
	}

	// Создаем таблицу
	public static function create_table(){
    	global $wpdb;
    	$charset_collate = $wpdb->get_charset_collate();
    	$tablename = $wpdb->prefix . "tasks";
    	$sql = "CREATE TABLE $tablename (
			id int(11) NOT NULL AUTO_INCREMENT,
			dat datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  			name varchar(255) NOT NULL,
  			description text NOT NULL DEFAULT '',
			is_done int(1) NOT NULL  DEFAULT '0',
  			PRIMARY KEY  (id)
  		) $charset_collate;";

    	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    	dbDelta($sql);
	}







}
