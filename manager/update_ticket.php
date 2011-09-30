<?php 
function update_event_ticket(){
	global $wpdb;
$ticket_id= $_REQUEST['ticket_id'];
		$ticket_name= $_REQUEST['ticket_name'];
		$ticket_file= $_REQUEST['ticket_file'];
		$ticket_logo_url= $_REQUEST['upload_image'];
		$ticket_content= $_REQUEST['ticket_content'];
		$sql=array('ticket_name'=>$ticket_name, 'ticket_content'=>$ticket_content, 'ticket_file'=>$ticket_file, 'ticket_logo_url'=>$ticket_logo_url); 
		
		$update_id = array('id'=> $ticket_id);
		
		$sql_data = array('%s','%s','%s','%s');
	
	if ($wpdb->update( EVENTS_TICKET_SETTINGS, $sql, $update_id, $sql_data, array( '%d' ) )){?>
	<div id="message" class="updated fade"><p><strong><?php _e('The ticket', 'event_espresso'); ?> <?php echo stripslashes(htmlentities2($_REQUEST['ticket_name']));?> <?php _e('has been updated', 'event_espresso'); ?>.</strong></p></div>
<?php }else { ?>
	<div id="message" class="error"><p><strong><?php _e('The ticket', 'event_espresso'); ?> <?php echo stripslashes(htmlentities2($_REQUEST['ticket_name']));?> <?php _e('was not updated', 'event_espresso'); ?>. <?php print mysql_error() ?>.</strong></p></div>

<?php
	}
}