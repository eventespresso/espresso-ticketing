<?php
/**
  Plugin Name: Event Espresso - Ticketing
  Plugin URI: http://eventespresso.com/
  Description: Ticketing system for Event Espresso

  Version: 2.0.4

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
//global $espresso_path;
define( "ESPRESSO_TICKETING_TABLE", $wpdb->prefix . 'events_ticket_templates');
define( "ESPRESSO_TICKETING_PATH", "/" . plugin_basename( dirname( __FILE__ ) ) . "/" );
define( "ESPRESSO_TICKETING_FULL_PATH", WP_PLUGIN_DIR . ESPRESSO_TICKETING_PATH );
define( "ESPRESSO_TICKETING_FULL_URL", WP_PLUGIN_URL . ESPRESSO_TICKETING_PATH );
define( "ESPRESSO_TICKETING_ACTIVE", TRUE );
define( "ESPRESSO_TICKETING_VERSION", '2.0.4' );
define("EVENTS_TICKET_SETTINGS", $wpdb->prefix . "events_ticket_templates");
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
		
		$ticket_options = array(
			'use_gravatar' => 'N',
			'use_name_badge' => 'N',
			'image_file' => 'ticket-bg.jpg',
			'background_color' => '000000',
			'enable_personal_qr_code' => 'Y',
			'show_venue' => 'Y',
			'show_map' => 'Y',
			'show_price' => 'Y',
			'show_espresso_footer' => 'Y'
		);

		add_option('espresso_ticket_settings', $ticket_options);
		
	}
	
}

//Export PDF Ticket
if (isset($_REQUEST['ticket_launch'])&&$_REQUEST['ticket_launch'] == 'true') {
	//echo espresso_ticket_launch($_REQUEST['id'], $_REQUEST['registration_id']);
}


