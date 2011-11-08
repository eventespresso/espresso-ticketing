<?php
/**
  Plugin Name: Event Espresso - Ticketing
  Plugin URI: http://eventespresso.com/
  Description: Ticketing system for Event Espresso

  Version: 2.0.5

  Author: Seth Shoultes
  Author URI: http://www.eventespresso.com

  Copyright (c) 2011 Event Espresso  All Rights Reserved.

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
 
global $wpdb;
define( "ESPRESSO_TICKETING_VERSION", '2.0.5' );
define( "ESPRESSO_TICKETING_PATH", "/" . plugin_basename( dirname( __FILE__ ) ) . "/" );
define( "ESPRESSO_TICKETING_FULL_PATH", WP_PLUGIN_DIR . ESPRESSO_TICKETING_PATH );
define( "ESPRESSO_TICKETING_FULL_URL", WP_PLUGIN_URL . ESPRESSO_TICKETING_PATH );
define( "ESPRESSO_TICKETING_ACTIVE", TRUE );
define("EVENTS_TICKET_TEMPLATES", $wpdb->prefix . "events_ticket_templates");
//echo $espresso_path;
require_once('functions.php');
require_once('manager/index.php');
/*function event_espresso_ticket_config_mnu() {
}*/
//Install plugin
register_activation_hook( __FILE__, 'espresso_ticketing_install' );
register_deactivation_hook( __FILE__, 'espresso_ticketing_deactivate' );
//Deactivate the plugin
if ( !function_exists( 'espresso_ticketing_deactivate' ) ){
    function espresso_ticketing_deactivate() {
        update_option( 'espresso_ticketing_active', 0 );
    }
}

//Install the plugin
if ( !function_exists( 'espresso_ticketing_install' ) ){

    function espresso_ticketing_install() {

        update_option( 'espresso_ticketing_version', ESPRESSO_TICKETING_VERSION );
        update_option( 'espresso_ticketing_active', 1 );
        global $wpdb;

        $table_version = ESPRESSO_TICKETING_VERSION;

       	$table_name = "events_ticket_templates";
    	$sql = "id int(11) unsigned NOT NULL AUTO_INCREMENT,
			ticket_name VARCHAR(100) DEFAULT NULL,
			ticket_file VARCHAR(100) DEFAULT 'basic.html',
			ticket_subject VARCHAR(250) DEFAULT NULL,
			ticket_content TEXT,
			ticket_logo_url TEXT,
			ticket_meta LONGTEXT DEFAULT NULL,
			wp_user int(22) DEFAULT '1',
			UNIQUE KEY id (id)";
		
		event_espresso_run_install($table_name, $table_version, $sql);
		
		$table_name = "events_attendee_checkin";
    	$sql = "id int(11) unsigned NOT NULL AUTO_INCREMENT,
			attendee_id int(11) NOT NULL,
			registration_id varchar(23) NOT NULL,
			event_id int(11) NOT NULL,
			checked_in int(1) NOT NULL,
			date_scanned datetime NOT NULL,
			KEY attendee_id (attendee_id, registration_id, event_id)";
		
		event_espresso_run_install($table_name, $table_version, $sql);
		
	}
	
}

//Export PDF Ticket
if (isset($_REQUEST['ticket_launch'])&&$_REQUEST['ticket_launch'] == 'true') {
	//echo espresso_ticket_launch($_REQUEST['id'], $_REQUEST['registration_id']);
}

if (is_admin())
	wp_enqueue_style('espresso_ticketing_menu', ESPRESSO_TICKETING_FULL_URL . 'css/admin-menu-styles.css');

if (isset($_REQUEST['page']) && $_REQUEST['page']=='event_tickets') {
	wp_enqueue_style('espresso_ticketing', ESPRESSO_TICKETING_FULL_URL . 'css/admin-styles.css');
}