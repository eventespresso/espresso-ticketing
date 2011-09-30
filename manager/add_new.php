<?php
function add_new_event_ticket(){
	// read our template dir and build an array of files
	$dhandle = opendir(ESPRESSO_TICKETING_FULL_PATH . 'templates/');
	$files = array();
	
	if ($dhandle) { //if we managed to open the directory
		// loop through all of the files
		while (false !== ($fname = readdir($dhandle))) {
			// if the file is not this file, and does not start with a '.' or '..',
			// then store it for later display
			if ( ($fname != '.') && ($fname != '..') && ($fname != '.svn') && ($fname != basename($_SERVER['PHP_SELF'])) ) {
				// store the filename
				$files[] = $fname;
			}
		}
		// close the directory
		closedir($dhandle);
	}
	?>
<!--Add event display-->
<div class="metabox-holder">
  <div class="postbox">
 
		<h3><?php _e('Add a Ticket Template','event_espresso'); ?></h3>
 	<div class="inside">
  <form id="add-edit-new-event-ticket" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
  <input type="hidden" name="action" value="add">
   <ul>
    <li><label for="ticket_name"><?php _e('Ticket Name','event_espresso'); ?></label> <input type="text" name="ticket_name" size="25" /></li>
    <li>
  <label for="base-ticket-select" <?php echo $styled ?>>
    <?php _e('Select Base Template', 'event_espresso');  ?>
  </label>
  <select id="base-ticket-select" class="wide" <?php echo $disabled ?> name="ticket_file">
    <option <?php espresso_ticket_is_selected($fname) ?> value="basic.html">
    <?php _e('Default Template - Basic', 'event_espresso'); ?>
    </option>
    <?php foreach( $files as $fname ) { ?>
    <option <?php espresso_ticket_is_selected($fname) ?> value="<?php echo $fname ?>"><?php echo $fname; ?></option>
    <?php } ?>
  </select>
</li>
			<li>

			<div id="descriptiondivrich" class="postarea">   
		 		<label for="ticket_content"><?php _e('Ticket Description/Instructions','event_espresso'); ?></label>
				
                
				<div class="postbox">
				
					<?php the_editor('', $id = 'ticket_content', $prev_id = 'title', $media_buttons = true, $tab_index = 3);?>
				
						<table id="manage-event-ticket-form" cellspacing="0">
							<tbody>
								<tr>
									<td class="aer-word-count"></td>
									<td class="autosave-info">
										<span>
											<a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=custom_ticket_info"><?php _e('View Custom Ticket Tags', 'event_espresso'); ?></a> | <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=custom_ticket_example"> <?php _e('Ticket Example','event_espresso'); ?></a>
										</span>
									</td>
								</tr>
							</tbody>
						</table>				
				</div>
			
			</div>
				
   	</li>
   	<li>
    	<p>
					<input class="button-primary" type="submit" name="Submit" value="<?php _e('Add Ticket'); ?>" id="add_new_ticket" />
    	</p>
    </li>
   </ul>
	</form>
 </div>
</div>
</div>
<?php 
//espresso_tiny_mce();
} 