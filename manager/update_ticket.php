<?php 
function update_event_ticket(){
	global $wpdb, $notices;
	
	$ticket_id= $_REQUEST['ticket_id'];
	$ticket_name= $_REQUEST['ticket_name'];
	$ticket_file= $_REQUEST['ticket_file'];
	$ticket_logo_url= $_REQUEST['upload_image'];
	$ticket_content= $_REQUEST['ticket_content'];
	$sql=array('ticket_name'=>$ticket_name, 'ticket_content'=>$ticket_content, 'ticket_file'=>$ticket_file, 'ticket_logo_url'=>$ticket_logo_url); 
		
	$update_id = array('id'=> $ticket_id);
	$sql_data = array('%s','%s','%s','%s');
	
	if ($wpdb->update( EVENTS_TICKET_TEMPLATES, $sql, $update_id, $sql_data, array( '%d' ) )){
		$notices['updates'][] = __('The ticket ', 'event_espresso') . $category_name .  __(' has been updated', 'event_espresso');
	}else { 
		$notices['errors'][] = __('The ticket', 'event_espresso') . $category_name .  __(' was not saved!', 'event_espresso');		
	}
}