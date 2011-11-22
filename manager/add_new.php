<?php
function add_new_event_ticket(){

	$files = espresso_ticket_template_files();
	?>
<!--Add event display-->

<div class="metabox-holder">
	<div class="postbox">
		<h3>
			<?php _e('Add a Ticket Template','event_espresso'); ?>
		</h3>
			<div class="inside">
				<form id="add-edit-new-event-ticket" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
					<input type="hidden" name="action" value="add">
						<table class="form-table">
							<tbody>
								<tr>
									<th> 
										<label for="ticket_name">
											<?php _e('Ticket Name ','event_espresso'); ?><?php apply_filters('espresso_help', 'ticket-guide'); ?>
										</label>
									</th>
									<td><input type="text" name="ticket_name" size="25" /></td>
								</tr>
								<tr>
									<th>
										<label for="base-ticket-select" <?php echo $styled ?>> 
											<?php _e('Select Base Template', 'event_espresso');  ?>
										</label>
									</th>
									<td>
										<select id="base-ticket-select" class="wide" <?php echo $disabled ?> name="ticket_file">
										<option <?php espresso_ticket_is_selected($fname) ?> value="basic.html">
										<?php _e('Default Template - Basic', 'event_espresso'); ?>
										</option>
										<?php foreach( $files as $fname ) { ?>
										<option <?php espresso_ticket_is_selected($fname) ?> value="<?php echo $fname ?>"><?php echo $fname; ?></option>
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
										<?php // var_dump($event_meta['event_thumbnail_url']); ?>
										<input id="upload_image" type="hidden" size="36" name="upload_image" value="<?php echo $ticket_logo ?>" />
              		<input id="upload_image_button" type="button" value="Upload Image" />
              		
										<?php if($ticket_logo){ ?>
										<p class="ticket-logo"><img src="<?php echo $ticket_logo ?>" alt="" /></p>
										<a id='remove-image' href='#' title='<?php _e('Remove this image', 'event_espresso'); ?>' onclick='return false;'>
										<?php _e('Remove Image', 'event_espresso'); ?>
									</a>
									<?php } ?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>

						<div id="descriptiondivrich" class="postarea">
							<label for="ticket_content">
							<?php _e('Ticket Description/Instructions','event_espresso'); ?>
							<a class="thickbox"  href="#TB_inline?height=400&amp;width=500&amp;inlineId=ticket_description_info" target="_blank"><img src="<?php echo EVENT_ESPRESSO_PLUGINFULLURL ?>images/question-frame.png" width="16" height="16" alt="" /></a> </label>
							<div class="postbox">
								<?php the_editor('', $id = 'ticket_content', $prev_id = 'title', $media_buttons = true, $tab_index = 3);?>
									<table id="manage-event-ticket-form" cellspacing="0">
										<tbody>
											<tr>
												<td class="aer-word-count"></td>
												<td class="autosave-info">
													<span> 
													<a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=custom_ticket_tags">
													<?php _e('View Custom Ticket Tags', 'event_espresso'); ?>
													</a>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						<p>
							<input class="button-primary" type="submit" name="Submit" value="<?php _e('Add Ticket'); ?>" id="add_new_ticket" />
						</p>
						 <?php wp_nonce_field( 'espresso_form_check', 'add_new_ticket' ); ?>
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
				jQuery("#ticket-logo-image").append("<a id='remove-image' href='#' title='<?php _e('Remove this image', 'event_espresso'); ?>' onclick='return false;'><?php _e('Remove Image', 'event_espresso'); ?></a>");
				jQuery('#remove-image').click(function(){
				//alert('delete this image');
				jQuery('#' + formfield).val('');
				jQuery("#image-display").empty();
				jQuery('#remove-image').remove();
				});
				} else {
					window.original_send_to_editor(html);
				}
		}
	});

	//]]>
</script>
<?php
//espresso_tiny_mce();
}


