<?php    

session_start(); 

require_once (dirname(__FILE__) . '/src/Google/autoload.php');

$client = new Google_Client();
$client = new Google_Client();
$client->setApplicationName("poc");
$client->setDeveloperKey("Your developer key");  
$client->setClientId('Your client id');
$client->setClientSecret('Your client secret');
$client->setRedirectUri('http://127.0.0.1/googlemaster/googlesidebar.php');
$client->setAccessType('offline');   // Gets us our refreshtoken

$client->setScopes(array('https://www.googleapis.com/auth/calendar'));
$eventsDatas = '';
 $calendarId = 'mj0miodsk2cedhtd0f6rifhgic@group.calendar.google.com' ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Calendar</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--calendar css-->
    <link href="js/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container" >

   <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
            <section class="panel">
                    <header class="panel-heading">
                   <?php 
                   
                   
//For loging out.
if (isset($_GET['logout'])) {
	unset($_SESSION['token']);
}


// Step 2: The user accepted your access now you need to exchange it.
if (isset($_GET['code'])) {
	
	$client->authenticate($_GET['code']);  
	$_SESSION['token'] = $client->getAccessToken();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

// Step 1:  The user has not authenticated we give them a link to login    
if (!isset($_SESSION['token'])) {

	$authUrl = $client->createAuthUrl();

	print "<a class='login btn btn-primary pull-left' href='$authUrl'>Connect Me!</a>";
}    
// Step 3: We have access we can now create our service
if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
	print "<a class=' login btn btn-primary pull-left logout' href='".$_SERVER['PHP_SELF']."?logout=1'>LogOut</a><br>";	
	
	$service = new Google_Service_Calendar($client);    
	
	$calendarList  = $service->calendarList->listCalendarList();

	/*events list*/
	$eventsData =[] ;
	// echo '<br /> <br />events listed <br /><br />';
		$events = $service->events->listEvents($calendarId);

       $projectEventsCreated  =  $events->getItems() ;
      
        for ($i = 0; $i < count($projectEventsCreated); $i++) {
           
          $eventsData[$i]['title'] = $projectEventsCreated[$i]->getSummary();
          if( !empty($projectEventsCreated[$i]['end']['date']) ){
				   $eventsData[$i]['start'] =date("D M j Y  G:i:s \G\M\TO (T) " , strtotime($projectEventsCreated[$i]->getStart()->getDate()) ); 
				   $eventsData[$i]['end'] =date("D M j Y  G:i:s \G\M\TO (T) " , strtotime($projectEventsCreated[$i]->getEnd()->getDate()) );  
				
			} else {
				   $eventsData[$i]['start'] =date("D M j Y  G:i:s \G\M\TO (T) " , strtotime($projectEventsCreated[$i]->getStart()->getDateTime()) ); 
				   $eventsData[$i]['end'] =date("D M j Y  G:i:s \G\M\TO (T) " , strtotime($projectEventsCreated[$i]->getEnd()->getDateTime()) );   
			}
         
        }
        $eventsDatas = json_encode($eventsData);
        //~ echo  "<pre>";
        //~ print_r($eventsData);
      //~ echo "</pre>";
      //~ die ;
}
                   
                   
                    ?>
                    </header>
                    <div class="panel-body">
                        <!-- page start-->
                        <div class="row">
							
                            <aside class="col-lg-9">
								<input type="hidden" value='<?php echo $eventsDatas; ?>' id="events"/>
                                  <div id="calendar" class="has-toolbar"></div>
                            </aside>
                            <aside class="col-lg-3">

         <div class="position-center ">
                            <div class="text-center">
							<?php	if (isset($_SESSION['token'])) {   ?>
                                <a href="#myModal" data-toggle="modal" class="btn btn-success">
                                    Create Event For Calender
                                </a>
                                <?php  } ?>
                            </div>

                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4 class="modal-title">Event Add</h4>
                                    </div>
                                    <div class="modal-body">

                                        <form role="form" id="event_form" method="POST">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Event Title</label>
                                                <input type="text" required="" name="title" class="form-control" id="title" placeholder="Enter Title">
                                                <div class="alert4 alert-error" ></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Start Time</label>
												<input type="text" required="" placeholder="Enter Start Time" class="form_datetime form-control" readonly="" name="start_time" id="start_time">
												 <div class="alert1 alert-error" ></div>
                                                                        
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">End Time</label>
												<input type="text" required="" placeholder="Enter End Time" class="form_datetime form-control" readonly="" name="end_time" id="end_time"> 
												 <div class="alert2 alert-error" ></div>
 
											</div>                                 
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">Attendee Email address</label>
                                                <input type="text" id="email" required="" name="email" class="form-control" id="email" placeholder="Email">
                                              <div class="alert3 alert-error"></div>

                                                
                                            </div>                                           
                                            <div class="form-group">
												<button type="button" id="eventadd" class="btn btn-default">Submit</button>
                          
                                            </div>

									 <div class="modal-footer">
                                      </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                       
                           								
								
                         
                            </aside>
                        </div>
                        <!-- page end-->
                    </div>
                </section>
        <!-- page end-->
        </section>
    </section>
    <!--main content end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="js/advanced-form.js"></script>


<!--Core js-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script src="js/fullcalendar/fullcalendar.min.js"></script>
<!--script for this page only-->
<script src="js/external-dragging-calendar.js"></script>

<script>
$(document).ready(function(){
	
	$('#eventadd').click(function(){
		
     
		if ($('#title').val() === '') {
			
			$('.alert4').html('Title can not be blank');
			$('#title').focus();
			return false;
		} else if($('#start_time').val() === ''){
			$('.alert4').hide();
			$('.alert1').html('Start time can not be blank');
			$('#start_time').focus();
			return false;
						
		} else if($('#end_time').val() === ''){
			$('.alert1').hide();
			$('.alert2').html('End time can not be blank');
			$('#end_time').focus();
			return false;			
						
		} else if($('#email').val() === ''){
			$('.alert2').hide();
			$('.alert3').html('Email can not be blank');
			$('#email').focus();
			return false;			
		}
		else if (validateEmail($('#email').val())==false) {
			
		    $('.alert3').html('Please enter a valid email.');
		    $('#email').focus()
		 	return false;
		} else if (validateEmail($('#email').val())==true) {
		    $('.alert3').html('');	 	
		}					
	  	var form_data = $('#event_form').serialize();
		
		$.ajax({
		    type: "POST",
            url: "create_event.php",
            data: form_data,
            success: function(data) {
				if(data==='done'){
					  alert('Event created Successfully.');
					 location.reload();
				} else {
					
					alert(data);
				}
               
            },	
			
		});
	});

});
function validateEmail(Email) {
	var sEmail = Email;
	var Emailfilter = /^([a-zA-Z0-9_+\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (Emailfilter.test(sEmail)) {
	return true;
	}
	else {
	return false;
	}
	
}




</script>
</body>
</html>
