
Extract This folder in your main root. Because I have set this while creating project on  google.  

eg. http://127.0.0.1/googlemaster/ 


If you want to create events  then change the calender id with your calender id 

 $calendarId = 'Your calender id' ;
  
  And 
  
$client = new Google_Client();
$client->setApplicationName("poc");
$client->setDeveloperKey("Your developer key");  
$client->setClientId('Your client id');
$client->setClientSecret('Your client secret');
$client->setRedirectUri('http://127.0.0.1/googlemaster/googlesidebar.php');

with your credentials .

your need to create an project in google to get these values. 

but You can see the events created via  clicking 'connect me  ' and after this you will connect to see the events listed in the calender.

and if  you want to use it as your the change the variables values . 


You can only see events after clicking "'connect me  '" button.


