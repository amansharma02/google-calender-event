
Extract This folder in your main root. Because I have set this while creating project on  google.  

eg. http://127.0.0.1/googlemaster/ 


If you want to create events  then change the calender id with your calender id 

 $calendarId = 'mj0miodsk2cedhtd0f6rifhgic@group.calendar.google.com' ;
  
  And 
  
$client = new Google_Client();
$client->setApplicationName("poc");
$client->setDeveloperKey("394846341398-998v71sprnkiqnnar2aq9331meeugl9b@developer.gserviceaccount.com");  
$client->setClientId('394846341398-998v71sprnkiqnnar2aq9331meeugl9b.apps.googleusercontent.com');
$client->setClientSecret('uOslV_oQfJi8gFUrFJzKvXPE');
$client->setRedirectUri('http://127.0.0.1/googlemaster/googlesidebar.php');

with your credentials .

If you want to change events from your id after clicking 'connect me ' , then share your email id with me. I need to grant permission to your Id . so that you can create events from your id.


your need to create an project in google to get these values. 

but You can see the events created via  clicking 'connect me  ' and after this you will connect to see the events listed in the calender.

and if  you want to use it as your the change the variables values . 


You can only see events after clicking "'connect me  '" button.

