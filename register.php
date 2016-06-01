<html>
<head><title>User registration</title></head>
<body bgcolor="#E6E6FA">
<form method="POST" action="">
<fieldset>
<label>Enter fullname: <input type="text" name="Fullname"/></label><br/>
<label>Enter Email-Id: <input type="text" name="email"/></label><br/>
<label>Enter username: <input type="text" name="username"/></label><br/>
<label>Enter Password: <input type="password" name="password"/></label><br/>
<input type="submit" name="register" value="register"/>
</fieldset>
</form>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');
     require 'aws/aws-autoloader.php';
     use \Aws\DynamoDb\DynamoDbClient;
    if(isset($_POST['register']))
    {
       $fname=$_POST['Fullname'];
       $email=$_POST['email'];
       $uname=$_POST['username'];
       $pass=md5($_POST['password']);
       try
       {
          $client = DynamoDbClient::factory(array(
            'key' => 'Enter your key',
               'secret' => 'Enter your secret key',
               'region' => ''  // replace with your desired region
          ));
		 
         //Scan the table to see if user already exists.
		 
		  $res = $client->scan(array(
          'TableName' => 'RegisterDetails',
          'ExpressionAttributeValues' =>  array (
            ':val1' => array('S' => $uname)) ,
          'FilterExpression' => 'UserId = :val1',
          ));
		  
		 
          if($res['Count']>0)
		 { 
		   echo "User Already Exists";  
		 }
		 else
		 { 
		   //If user doesnt exists register him using putitem function
          $response = $client->putItem(array(
                  "TableName" => 'RegisterDetails',
                   "Item" => $client->formatAttributes(array(
                           "UserId" =>  $uname, // Primary Key
                           "Pwd"  =>  $pass,
                           "Fname"  =>  $fname,
                           "Email" =>   $email )
                     )
                 ));
				 header('Location: login.php');
        }
	 }
		   catch (PDOException $e)
		   {
			   print "Error!: " . $e->POSTMessage() . "<br/>";
			   die();
		   }
     }
?>
</body>
</html>
