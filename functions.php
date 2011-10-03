<?php
function espresso_ticket_qr_code($atts){
	global $org_options;
	extract( $atts );
	$qr_data = '<img src="http://chart.googleapis.com/chart?chs=135x135&cht=qr&chl='.urlencode(json_encode(array( 'event_code'=>$event_code, 'registration_id'=>$registration_id, 'attendee_id'=>$attendee_id,'attendee_name'=>$attendee_first . ' ' . $attendee_last, 'event_name'=>$event_name,'ticket_type'=>$ticket_type, 'event_time'=>$event_time, 'amount_pd'=>html_entity_decode($org_options['currency_symbol']).$amount_pd )) ).'" alt="QR Check-in Code" />';
	return $qr_data;
}
function espresso_ticket_is_selected($name, $selected='') {
	   $input_item = $name;
			 $option_selections = array($selected);
	   if (!in_array( $input_item, $option_selections )  )
	   return false;
	   else
	   echo  'selected="selected"';
	   return; 
	}
function espresso_ticket_content($id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM " . ESPRESSO_TICKETING_TABLE . " WHERE id =" . $id);
    foreach ($results as $result) {
        $ticket_id = $result->id;
        $ticket_name = stripslashes_deep($result->ticket_name);
        $ticket_content = stripslashes_deep($result->ticket_content);
    }
    $ticket_data = array('id' => $id, 'ticket_name' => $ticket_name,'ticket_content' => $ticket_content);
    return $ticket_data;
}

//Creates the ticket pdf
function espresso_ticket_launch($attendee_id=0, $registration_id=0){
	global $wpdb, $org_options, $ticket_options;
	$data = new stdClass;
	
	//Make sure we have attendee data
	if ($attendee_id==0 || $registration_id==0)
		return;
	
	//Get the event record
    $sql = "SELECT ed.*, et.ticket_file, et.ticket_content, et.ticket_logo_url ";
    isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ? $sql .= ", v.id venue_id, v.name venue_name, v.address venue_address, v.city venue_city, v.state venue_state, v.zip venue_zip, v.country venue_country, v.meta venue_meta " : '';
    $sql .= " FROM " . EVENTS_DETAIL_TABLE . " ed ";
    isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ? $sql .= " LEFT JOIN " . EVENTS_VENUE_REL_TABLE . " r ON r.event_id = ed.id LEFT JOIN " . EVENTS_VENUE_TABLE . " v ON v.id = r.venue_id " : '';
    $sql .= " JOIN " . EVENTS_ATTENDEE_TABLE . " ea ON ea.event_id=ed.id ";
	$sql .= " LEFT JOIN " . EVENTS_TICKET_TEMPLATES . " et ON et.id=ed.ticket_id ";
    $sql .= " WHERE ea.id = '" . $attendee_id . "' AND ea.registration_id = '" . $registration_id . "' ";
	//echo $sql;
    $data->event = $wpdb->get_row($sql, OBJECT);
	
	//Get the attendee record
    $sql = "SELECT ea.* FROM " . EVENTS_ATTENDEE_TABLE . " ea WHERE ea.id = '" . $attendee_id . "' ";
    $data->attendee = $wpdb->get_row($sql, OBJECT);
	
	//Get the primary/first attendee
	$data->primary_attendee = espresso_is_primary_attendee($data->attendee->id) == true ? true : false;
	
	//Get the registration date
	$data->attendee->registration_date = $data->attendee->date;
	
	$data->event->ticket_file = (!empty($data->event->ticket_file) && $data->event->ticket_file > '0') ? $data->event->ticket_file : 'basic.html';
	//echo $data->event->ticket_file;
	
	//Venue information
    if (isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y') {
		$data->event->venue_id = !empty($data->event->venue_id)?$data->event->venue_id:'';
		$data->event->venue_name = !empty($data->event->venue_name)?$data->event->venue_name:'';
		$data->event->address = !empty($data->event->venue_address)?$data->event->venue_address:'';
		$data->event->address2 = !empty($data->event->venue_address2)?$data->event->venue_address2:'';
		$data->event->city = !empty($data->event->venue_city)?$data->event->venue_city:'';
		$data->event->state = !empty($data->event->venue_state)?$data->event->venue_state:'';
		$data->event->zip = !empty($data->event->venue_zip)?$data->event->venue_zip:'';
		$data->event->country = !empty($data->event->venue_country)?$data->event->venue_country:'';
		$data->event->venue_meta = !empty($data->event->venue_meta)?unserialize($data->event->venue_meta):'';
    } else {
        $data->event->venue_name = !empty($data->event->venue_title)?$data->event->venue_title:'';
    }
	
	//Create the Gravatar image
	$data->gravatar = espresso_get_gravatar($data->attendee->email, $size = '100', $default = 'http://www.gravatar.com/avatar/' );
	
	//Google map IMAGE creation
	$data->event->google_map_image = espresso_google_map_link(array('id' => $data->event->venue_id, 'address' => $data->event->address, 'city' => $data->event->city, 'state' => $data->event->state, 'zip' => $data->event->zip, 'country' => $data->event->country, 'type'=>'map'));
	
	//Google map LINK creation
	$data->event->google_map_link = espresso_google_map_link(array('address' => $data->event->address, 'city' => $data->event->city, 'state' => $data->event->state, 'zip' => $data->event->zip, 'country' => $data->event->country, 'type'=>'text'));
	
	//Create the QR Code image
	$data->qr_code = espresso_ticket_qr_code( array(
		'attendee_id' => $data->attendee->id, 
		'event_name' => stripslashes_deep($data->event->event_name), 
		'attendee_first' => $data->attendee->fname, 
		'attendee_last' => $data->attendee->lname, 
		'registration_id' => $data->attendee->registration_id, 
		'event_code' => $data->event->event_code, 
		'ticket_type' => $data->attendee->price_option, 
		'event_time' => $data->attendee->event_time, 
		'amount_pd' => espresso_attendee_price(array(
			'registration_id' => $data->attendee->registration_id, 
			'reg_total' => true
		)),
	));
	//Build the ticket name
	$ticket_name = sanitize_title_with_dashes($data->attendee->id.' '.$data->attendee->fname.' '.$data->attendee->lname);
	//Get the HTML as an object
    ob_start();
	require_once('templates/'.$data->event->ticket_file);
	$content = ob_get_clean();
	$content = espresso_replace_ticket_shortcodes($content, $data);
	
	//Check if debugging or mobile is set
	if ( (isset($_REQUEST['debug']) && $_REQUEST['debug']==true) || stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') !== false ){
		echo $content; 
		exit(0);
	}
	
	//Create the PDF
	define('DOMPDF_ENABLE_REMOTE',true);
	require_once(EVENT_ESPRESSO_PLUGINFULLPATH . '/class/dompdf/dompdf_config.inc.php');
	$dompdf = new DOMPDF();
	$dompdf->load_html($content);
	//$dompdf->set_paper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream($ticket_name.".pdf", array("Attachment" => false));
	exit(0);
	
}

function espresso_replace_ticket_shortcodes($content, $data) {
    global $org_options;
    $SearchValues = array(
		//Attendee/Event Information
        "[att_id]",
		"[qr_code]",
		"[gravatar]",
		"[event_id]",
        "[event_identifier]",
        "[registration_id]",
		"[registration_date]",
        "[fname]",
        "[lname]",
        "[event_name]",
        "[description]",
        "[event_link]",
        "[event_url]",
        
        //Payment details
        "[cost]",
        "[ticket_type]",
        
		//Organization details
        "[company]",
        "[co_add1]",
        "[co_add2]",
        "[co_city]",
        "[co_state]",
        "[co_zip]",

		//Dates
        "[start_date]",
        "[start_time]",
        "[end_date]",
        "[end_time]",
		
		//Ticket data
		"[ticket_content]",
		"[ticket_logo_url]",
		
		//Venue information
		"[venue_title]",
		"[venue_address]",
		"[venue_address2]",
		"[venue_city]",
		"[venue_state]",
		"[venue_zip]",
		"[venue_country]",
		"[venue_phone]",
		"[venue_description]",
		
        "[venue_website]",
        "[venue_image]",
        
		"[google_map_image]",
        "[google_map_link]",
    );

    $ReplaceValues = array(
		//Attendee/Event Information
		$data->attendee->id,
		$data->qr_code,
		$data->gravatar,
        $data->attendee->event_id,
        $data->event->event_identifier,
        $data->attendee->registration_id,
		event_date_display($data->attendee->registration_date),
        stripslashes_deep($data->attendee->fname),
        stripslashes_deep($data->attendee->lname),
        stripslashes_deep($data->event->event_name),
        stripslashes_deep($data->event->event_desc),
       	$data->event_link,
        $data->event_url,
        
		//Payment details
        $org_options['currency_symbol'] .' '. espresso_attendee_price(array('registration_id' => $data->attendee->registration_id, 'session_total' => true)),
        $data->attendee->price_option,
        
		//Organization details
        stripslashes_deep($org_options['organization']),
        $org_options['organization_street1'],
        $org_options['organization_street2'],
        $org_options['organization_city'],
        $org_options['organization_state'],
        $org_options['organization_zip'],
        
		//Dates
        event_date_display($data->attendee->start_date),
        event_date_display($data->attendee->event_time, get_option('time_format')),
        event_date_display($data->attendee->end_date),
        event_date_display($data->attendee->end_time, get_option('time_format')),
		
		//Ticket data
		wpautop(stripslashes_deep(html_entity_decode($data->event->ticket_content, ENT_QUOTES))),
		$data->event->ticket_logo_url = empty($data->event->ticket_logo_url) ? $org_options['default_logo_url']: $data->event->ticket_logo_url,
		
		//Venue information
		$data->event->venue_name,		
		$data->event->address,
		$data->event->address2,
		$data->event->city,
		$data->event->state,
		$data->event->zip,
		$data->event->country,
		$data->event->venue_meta['phone'],
		wpautop(stripslashes_deep(html_entity_decode($data->event->venue_meta['description'], ENT_QUOTES))),
		
		$data->event->venue_meta['website'],
        $data->event->venue_meta['image'],        
		
		$data->event->google_map_image,
        $data->event->google_map_link,
    );
    return str_replace($SearchValues, $ReplaceValues, $content);
}

if ( !function_exists( 'espresso_ticket_dd' ) ){
	function espresso_ticket_dd($current_value = 0){
		global $espresso_premium; if ($espresso_premium != true) return;
		global $wpdb;
		$sql = "SELECT id, ticket_name FROM " .EVENTS_TICKET_TEMPLATES;
		$sql .= " WHERE ticket_name != '' ORDER BY ticket_name ";
		//echo $sql;
		$tickets = $wpdb->get_results($sql);
		$num_rows = $wpdb->num_rows;
		//return print_r( $tickets );
		if ($num_rows > 0) {
			$field = '<select name="ticket_id" id="ticket_id">\n';
			$field .= '<option value="0">'.__('Select a Ticket', 'event_espresso').'</option>';

			foreach ($tickets as $ticket){
				$selected = $ticket->id == $current_value ? 'selected="selected"' : '';
				$field .= '<option '. $selected .' value="' . $ticket->id .'">' . $ticket->ticket_name. '</option>\n';
			}
			$field .= "</select>";
			$html = '<p>' .__('Custom Ticket:','event_espresso') . $field .'</p>';
			return $html;
		}
	}
}