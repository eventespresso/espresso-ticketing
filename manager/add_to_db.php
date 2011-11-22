<?php
function add_ticket_to_db(){
	global $wpdb, $espresso_wp_user, $notices;
	if ( $_REQUEST['action'] == 'add' && check_admin_referer('espresso_form_check', 'add_new_ticket')){
		$ticket_name= $_REQUEST['ticket_name'];
		$ticket_file= $_REQUEST['ticket_file'];
		$ticket_logo_url= $_REQUEST['upload_image'];
		$ticket_content= $_REQUEST['ticket_content']; 	
        
		$sql=array(
			'ticket_name'=>$ticket_name,
			'ticket_content'=>$ticket_content,
			'ticket_file'=>$ticket_file,
			'ticket_logo_url'=>$ticket_logo_url,
			'wp_user'=>$espresso_wp_user
		);
		
		$sql_data = array('%s','%s','%s','%s');
	
		if ($wpdb->insert( EVENTS_TICKET_TEMPLATES, $sql, $sql_data )){
			$notices['updates'][] = __('The ticket ', 'event_espresso') . $category_name .  __(' has been added', 'event_espresso');
		}else { 
			$notices['errors'][] = __('The ticket', 'event_espresso') . $category_name .  __(' was not saved!', 'event_espresso');		
		}
	}
}
