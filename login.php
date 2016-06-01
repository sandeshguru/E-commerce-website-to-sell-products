<?php
   //storing the user id in session to use throughout the website
   session_start();
?>

<html>
<head><title>Login Page</title>
</head>
<body bgcolor="#E6E6FA">
<fieldset style="background-color:#999999;">
<form action="" method="POST">
<label>Enter username:<input type="text" name="username"/></label><br/><br/>
<label>Enter Password:<input type="password" name="password"/></label><br/>
</fieldset>
<fieldset style="background-color:#999999;">
<input class="button" type="submit" name="Login" value="Login"/>
<input class="button" type="submit" name="register" value="register"/>
<br/>
<br/>
</form>
</fieldset>

<?php
  error_reporting(E_ALL);
  ini_set('display_errors','On');
  require 'aws/aws-autoloader.php';
  use \Aws\DynamoDb\DynamoDbClient;
  if(isset($_POST['register']))
  {
     header("Location: register.php");
  }
  if(isset($_POST['Login'])) 
  {
     if(!isset($_POST['username'])|| strlen($_POST['password']) == 0) 
     {
         echo " enter username and password to login";
     }
     else 
     {
        $user=$_POST['username'];
        $pass=md5($_POST['password']);
        try 
        {
		   //Creating client instance of DynamoDBclient
 	         $client = DynamoDbClient::factory(array(
           'key' => 'Enter your key',
               'secret' => 'Enter your secret key',
               'region' => ''  // replace with your desired region
          ));

		  //Getting the userid and password from the table
	      $response = $client->getItem(array(
          'TableName' => 'RegisterDetails',
          'Key' => array(
          	'UserId' => array( 'S' => $user),
          	'Pwd'  => array( 'S' => $pass)
           )  
         ));  
      
	  
	  //Matching the credentials the user entered vs stored in table
         if(count($response)>0){ 
           $_SESSION['userId'] = $response['Item']['UserId']['S'];
          //  print $_SESSION['userId'];
          // $_SESSION['username'] =$response['Item']['Fname']['S'];
	   header('Location: sell.php');
          }
          else 
                {
                     echo "please enter valid username and password";
                }
         }
         catch (PDOException $e) 
         {
              print "Error!: " . $e->POSTMessage() . "<br/>";
              die();
         }
    }
}
?>
</body>
</html>
