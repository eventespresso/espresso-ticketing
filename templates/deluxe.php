<?php
global $org_options;

//Build the path to the css files
if (file_exists(EVENT_ESPRESSO_UPLOAD_DIR . "tickets/templates/css/base.css")) {
	$base_dir = EVENT_ESPRESSO_UPLOAD_URL . 'tickets/templates/css/';//If the template files have been moved to the uploads folder
} else {
	$base_dir = ESPRESSO_TICKETING_FULL_URL.'templates/css/';//Default location
}

//Output the $data (array) variable that contains the attendee information and ticket settings
//echo "<pre>".print_r($data,true)."</pre>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo stripslashes_deep($org_options['organization']) ?> <?php _e('Ticket for', 'event_espresso'); ?> <?php echo stripslashes_deep($data->attendee->fname . ' ' .$data->attendee->lname) ?> | <?php echo $data->attendee->registration_id ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Base Stylesheet do not change or remove -->
<link rel="stylesheet" type="text/css" href="<?php echo ESPRESSO_TICKETING_FULL_URL; ?>templates/extra/deluxe.css" media="screen" />

<!-- Color options -->
<link rel="stylesheet" type="text/css" href="<?php echo $base_dir.$data->event->css_file; ?>" />

<!-- Make sure the buttons don't print -->
<style type="text/css">
@media print{
	.noPrint{display:none!important;}
}
</style>

</head>

<body>
	<div class="outside">
		<div class="print_button_div">
			<form>
				<input class="print_button noPrint" type="button" value=" Print Ticket " onclick="window.print();return false;" />
			</form>
			<form method="post" action="<?php echo espresso_ticket_url($data->attendee->id, $data->attendee->registration_id, '&pdf=true'); ?>" >
				<input class="print_button noPrint" type="submit" value=" Download PDF " />
			</form>
		</div>
		<div class="instructions">Print and bring this ticket with you to the event</div>
		<div class="ticket">
			<div class="topbar">
				<hr>
			</div>
			<div class="topinfo">
				<span class="name">[event_name]</span><br>
				<span class="infotext">[fname] [lname] (ID: [att_id])</span><br>
				<span class="title">credits: </span><span class="infotext">[number_credits] </span><br>
			</div>
			<div class="mainimage">
				<div class="gravatar">
		    		<img src="images/gravatar.jpg"><br>
		    		<span class="title2"></span><br>
					<span class="infotext">[registration_id]</span><br>
		    	</div>
			</div>
			<div class="topbar">
				<hr>
			</div>
		</div>
		<div class="extra_info">
			<span class="price">[cost]</span><br>
		    <span class="title">when: </span><span class="infotext">[start_date] [start_time]</span><br>
		    <span class="title">what: </span><span class="infotext">[ticket_type]</span><br>
		    <span class="title">where: </span><span class="infotext">[venue_title]</span><br><br> 
		    <span class="title">location: </span><br>
		    <span class="infotext">[venue_address]</span><br>
		    <span class="infotext">[venue_city], [venue_state]</span><br>
		    <span class="infotext">[venue_phone]></span></td><br><br>
			<span class="map">[google_map_image]</span>
			<span class="qr_code">[qr_code]</span>
		</div>
	</div>
</body>
</html>