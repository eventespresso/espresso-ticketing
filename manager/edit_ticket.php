<?php
function edit_event_ticket(){
	global $wpdb;
	// read our style dir and build an array of files
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
	$id=$_REQUEST['id'];
	$results = $wpdb->get_results("SELECT * FROM ". EVENTS_TICKET_SETTINGS ." WHERE id =".$id);
	foreach ($results as $result){
		$ticket_id= $result->id;
		$ticket_name=stripslashes_deep($result->ticket_name);
		$ticket_file=stripslashes_deep($result->ticket_file);
		$ticket_content=stripslashes_deep($result->ticket_content);
	}
	?>

<div class="metabox-holder">
  <div class="postbox">
    <h3>
      <?php _e('Edit Ticket Template:','event_espresso'); ?>
      <?php echo stripslashes($ticket_name) ?></h3>
    <div class="inside">
      <form id="add-edit-new-event-ticket" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
        <input type="hidden" name="action" value="update_ticket">
        <ul>
          <li>
            <label>
              <?php _e('Ticket Name:','event_espresso'); ?>
            </label>
            <input type="text" name="ticket_name" size="25" value="<?php echo stripslashes($ticket_name);?>" />
          </li>
          <li>
            <label for="base-ticket-select" <?php echo $styled ?>>
              <?php _e('Select Base Template', 'event_espresso');  ?>
            </label>
            <select id="base-ticket-select" class="wide" <?php echo $disabled ?> name="ticket_file">
              <option <?php espresso_ticket_is_selected($fname,$ticket_file) ?> value="basic.html">
              <?php _e('Default Template - Basic', 'event_espresso'); ?>
              </option>
              <?php foreach( $files as $fname ) { ?>
              <option <?php espresso_ticket_is_selected($fname,$ticket_file) ?> value="<?php echo $fname ?>"><?php echo $fname; ?></option>
              <?php } ?>
            </select>
          </li>
          <li>
            <div id="descriptiondivrich" class="postarea">
              <label for="ticket_content">
                <?php _e('Ticket Description/Instructions','event_espresso'); ?>
              </label>
              <div class="postbox">
                <?php the_editor(stripslashes_deep($ticket_content), $id = 'ticket_content', $prev_id = 'title', $media_buttons = true, $tab_index = 3);?>
                <table id="manage-event-ticket-form" cellspacing="0">
                  <tbody>
                    <tr>
                      <td class="aer-word-count"></td>
                      <td class="autosave-info"><span> <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=custom_ticket_info">
                        <?php _e('View Custom Ticket Tags', 'event_espresso'); ?>
                        </a> | <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=custom_ticket_example">
                        <?php _e('Ticket Example','event_espresso'); ?></a> 
                        | <a class="thickbox" href="<?php echo ESPRESSO_TICKETING_FULL_URL.'templates/'.$ticket_file; ?>?TB_iframe=true&height=200&width=630">
                        <?php _e('Preview','event_espresso'); ?>
                        </a> </span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </li>
          <li>
            <p>
              <input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Ticket'); ?>" id="update_ticket" />
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
