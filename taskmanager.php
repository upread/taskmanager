<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           TaskManager
 *
 * @wordpress-plugin
 * Plugin Name:       TaskManager
 * Plugin URI:        https://upread.ru/
 * Description:       TaskManager
 * Version:           1.0.0
 * Author:            up7
 * Author URI:        https://upread.ru/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       taskmanager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Update it as you release new versions.
 */
define( 'TASKMANAGER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-taskmanager-activator.php
 */
function activate_taskmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taskmanager-activator.php';
	TaskManager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-taskmanager-deactivator.php
 */
function deactivate_taskmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taskmanager-deactivator.php';
	TaskManager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_taskmanager' );
register_deactivation_hook( __FILE__, 'deactivate_taskmanager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-taskmanager.php';


function myfirstplugin_menu()
{
    add_menu_page("Таск-менеджер", "Таск-менеджер", "manage_options","taskmanager", "displayList", "dashicons-email-alt");
}

function displayList()
{
    global $wpdb;
    $tablename = $wpdb->prefix."tasks";

    $tasks = $wpdb->get_results("SELECT * FROM ".$tablename." order by dat desc");

    echo "<div><label for='new_task_dat'>Дата</label>";
    echo "<input type='datetime-local' id='new_task_dat' />";

    echo "<div><label for='new_task_name'>Название</label>";
    echo "<input type='text' id='new_task_name' />";

    echo "<div><label for='new_task_description'>Описание</label>";
    echo "<input type='text' id='new_task_description' />";

    echo "<button id='add_task'>Добавить задачу</button>";
    echo "</div>";

    echo "<table id='tasks_table'><tr><th>Дата</th><th>Название</th><th>Описание</th><th>Выполнена</th><th>Действие</th></tr>";
   

    if(count($tasks) > 0){
        foreach($tasks as $task){
            $id = $task->id;
            $name =$task->name;
            $description =$task->description;
            $dat = $task->dat;

            $checked = "";
            if ($task->is_done == "1"){
                $checked = "checked";
            }

            echo "<tr id='tr$id'>
                    <td>
                    <input type='datetime-local'  id='task_dat$id' value='$dat' 
                    </td>
                    <td><input type='text' id='task_name$id' value='$name' /></td>
                    <td><input type='text' id='task_description$id' value='$description' /></td>
                    <td><input type='checkbox' id='task_is_done$id' $checked /></td>
                    <td>
                        <button class='edit_task' data-task_id='$id'>Сохранить изменения</button>
                        <button class='del_task' data-task_id='$id'>Удалить</button>
                    </td>
            </tr>";
        }
    }

    echo "</table>";
 
}

add_action("admin_menu", "myfirstplugin_menu");

add_action( 'wp_ajax_my_action_add_task', 'my_action_add_task_callback' );
add_action( 'wp_ajax_my_action_del_task', 'my_action_del_task_callback' );
add_action( 'wp_ajax_my_action_edit_task', 'my_action_edit_task_callback' );

function my_action_add_task_callback(){
    global $wpdb;
    $tablename = $wpdb->prefix."tasks";

    $resp["success"] = true;
    $resp["mess"] = "Задача успешно добавлена";

	$name = $_POST['name'];
    $description = $_POST['description']; 
    $dat = $_POST['dat'];  

    if (!$name){
        $resp["success"] = false;
        $resp["mess"] = "Название задачи не может быть пустым!";
    }

    $data = [
        "name" => $name,
        "description" => $description,
        "dat" => $dat
    ];
    $wpdb->insert($tablename, $data);  
    $last_id = $wpdb->insert_id;
    $resp["last_id"] = $last_id;

	echo json_encode($resp);
	wp_die();
}

function my_action_del_task_callback(){
    global $wpdb;
    $tablename = $wpdb->prefix."tasks";

    $resp["success"] = true;

	$task_id = (int)$_POST['task_id'];

    $data = ['id' => $task_id];
    $wpdb->delete($tablename, $data);  

	echo json_encode($resp);
	wp_die();
}

function my_action_edit_task_callback(){
    global $wpdb;
    $tablename = $wpdb->prefix."tasks";

    $resp["success"] = true;
    $resp["mess"] = "Задача успешно обновлена";

	$task_id = (int)$_POST['task_id'];
    $name = $_POST['name'];
    $description = $_POST['description']; 
    $dat = $_POST['dat'];  	
    $is_done = ($_POST['is_done']) ? 1 : 0;

    $data = ['id' => $task_id];

    $wpdb->update($tablename,
	    [ 
            "name" => $name,
            "description" => $description,
            "dat" => $dat,
            "is_done" => $is_done

        ],
	    [ 'id' => $task_id ]
    ); 

    if ($wpdb->last_error){
        $resp["mess"] = $wpdb->last_error;
    }

	echo json_encode($resp);
	wp_die();
}



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_taskmanager() {

	$plugin = new TaskManager();
	$plugin->run();

}
run_taskmanager();
