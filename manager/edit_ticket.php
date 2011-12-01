<?php
function edit_event_ticket(){
	global $wpdb;
	$files = espresso_ticket_template_files();
	$id=$_REQUEST['id'];
	$results = $wpdb->get_results("SELECT * FROM ". EVENTS_TICKET_TEMPLATES ." WHERE id =".$id);
	foreach ($results as $result){
		$ticket_id= $result->id;
		$ticket_name=stripslashes_deep($result->ticket_name);
		$ticket_file=stripslashes_deep($result->ticket_file);
		$ticket_logo_url=stripslashes_deep($result->ticket_logo_url);
		$ticket_content=stripslashes_deep($result->ticket_content);
	}
	?>

<div class="metabox-holder">
		<div class="postbox">
			<h3>
				<?php _e('Edit Ticket','event_espresso'); ?>
			</h3>
			<div class="inside">
			
				<form id="add-edit-new-event-ticket" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
					<input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
						<input type="hidden" name="action" value="update_ticket">
						<h4><?php echo stripslashes($ticket_name) ?></h4>
						<table class="form-table">
							<tbody>
								<tr>
									<th> 
										<label>
											<?php _e('Ticket Name','event_espresso'); ?>
										</label>
									</th>
									<td>
										<input type="text" name="ticket_name" size="25" value="<?php echo stripslashes($ticket_name);?>" />
									</td>
								</tr>
								<tr>
									<th>
									<label for="base-ticket-select" <?php echo $styled ?>>
										<?php _e('Select Stylesheet', 'event_espresso');  ?>
									</label>
									</th>
									<td>
										<select id="base-ticket-select" class="wide" <?php echo $disabled ?> name="ticket_file">
										 <option <?php espresso_ticket_is_selected($fname,$ticket_file) ?> value="simple.css">
											<?php _e('Default CSS - Simple', 'event_espresso'); ?>
										</option>
								<?php foreach( $files as $fname ) { ?>
										<option <?php espresso_ticket_is_selected($fname,$ticket_file) ?> value="<?php echo $fname ?>"><?php echo $fname; ?></option>
						<?php } ?>
										</select>
									</td>
								</tr>
						  <?php
							if(!empty($ticket_logo_url)){ 
								$ticket_logo = $ticket_logo_url;
							} else {
								$ticket_logo = '';
							}
							?>
								<tr>
									<th>
									  <label for="upload_image">
										<?php _e('Add a Logo', 'event_espresso'); ?>
									  </label>
									</th>
									<td>
										<div id="ticket-logo-image">
									  <input id="upload_image" type="hidden" size="36" name="upload_image" value="<?php echo $ticket_logo ?>" />
		  							<input id="upload_image_button" type="button" value="Upload Image" />
		 							 <?php if($ticket_logo){ ?>
		  							<p class="ticket-logo-thumb"><img src="<?php echo $ticket_logo ?>" alt="" /></p>
										<a id='remove-image' href='#' title='<?php _e('Remove this image', 'event_espresso'); ?>' onclick='return false;'><?php _e('Remove Image', 'event_espresso'); ?></a>
		  						<?php } ?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						
						<div id="descriptiondivrich" class="postarea">
						<label for="ticket_content">
							<strong><?php _e('Ticket Description/Instructions ','event_espresso'); ?></strong> <?php apply_filters('espresso_help', 'ticket_description_info') ?>
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

				<p>
					<input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Ticket'); ?>" id="update_ticket" />
				</p>

					<?php wp_nonce_field( 'espresso_form_check', 'update_ticket' ); ?>
				</form>
			</div>
		</div>
</div>
<script type="text/javascript" charset="utf-8">
	//<![CDATA[
 	jQuery(document).ready(function() {    
		var header_clicked = false; 
		jQuery('#upload_image_button').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=1');
		jQuery('p.ticket-logo-thumb').addClass('old');
		header_clicked = true;
	    return false;
	   });
		window.original_send_to_editor = window.send_to_editor;
					 
		window.send_to_editor = function(html) {
			if(header_clicked) {
				imgurl = jQuery('img',html).attr('src');
				jQuery('#' + formfield).val(imgurl);
				jQuery('#ticket-logo-image').append("<p id='image-display'><img class='show-selected-img' src='"+imgurl+"' alt='' /></p>");
				header_clicked = false;
				tb_remove();
				} else {
					window.original_send_to_editor(html);
				}
		}
		
		// process the remove link in the metabox
				jQuery('#remove-image').click(function(){
				confirm('Do you really want to delete this image? Please remember to update your ticket to complete the removal');
				jQuery("#upload_image").val('');
				jQuery("p.ticket-logo-thumb").remove();
				jQuery("p#image-display").remove();
				jQuery('#remove-image').remove();
				//jQuery("#show_thumb_in_lists, #show_on_calendar, #show_thumb_in_regpage").val('N');
				});
				
	});

	//]]>
</script>
<?php 
 //espresso_tiny_mce();
}
