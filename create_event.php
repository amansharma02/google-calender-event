<?php
	SESSION_START();
require_once (dirname(__FILE__) . '/src/Google/autoload.php');	
    
       $calendarId = 'mj0miodsk2cedhtd0f6rifhgic@group.calendar.google.com' ;

		$client = new Google_Client();
		$client = new Google_Client();
		$client->setApplicationName("poc");
		$client->setDeveloperKey("Your developer key");  
		$client->setClientId('Your client id');
		$client->setClientSecret('Your client secret');
		$client->setRedirectUri('http://127.0.0.1/googlemaster/googlesidebar.php');
		$client->setAccessType('offline');   // Gets us our refreshtoken

		$client->setScopes(array('https://www.googleapis.com/auth/calendar'));
 
	    $client->setAccessToken($_SESSION['token']);
	
	    $service = new Google_Service_Calendar($client);    
	
		if( $_SESSION['token'] ){
			
			
	      $start_time=   str_replace('+0530','+05:30',date("Y-m-d\TH:i:sO" , strtotime($_POST['start_time']) ));
	      $end_time=   str_replace('+0530','+05:30',date("Y-m-d\TH:i:sO" , strtotime($_POST['end_time']) ));
	     
			$event = new Google_Service_Calendar_Event();
			$event->setSummary($_POST['title']);
			$event->setLocation('Una');
			$start = new Google_Service_Calendar_EventDateTime();
			$start->setDateTime($start_time);
			$event->setStart($start);
			$end = new Google_Service_Calendar_EventDateTime();
			$end->setDateTime($end_time);
			$event->setEnd($end);
		
		   $attendee1 = new Google_Service_Calendar_EventAttendee();
			$attendee1->setEmail($_POST['email']);
			
			$attendees = array($attendee1);
			$event->attendees = $attendees;
		
			  try {
			   $createdEvent = $service->events->insert( $calendarId , $event);
					
			    	$new_event_id= $createdEvent->getId();
			    	echo  "done";
				} catch (Google_Service_Exception $e) {
					if($e->getCode() == 403 ){
						echo 'You are not Autherise to perform this action';
					} else {
						echo $e->getMessage();
					}
				}
			
	   } else {
		echo "no";
	  }
?>
