<?php
   session_start();
?>

<html>
<head>
<title>Item Sale</title>
<style type="text/css">
   table {
            background-color: Beige;
            margin: 8px;
            border: 1px solid black;
   }
   td {
           border: 1px solid #DDD;
   }
</style>
</head>
<body bgcolor="#E6E6FA">
<fieldset style="background-color:#999999;">
<form action="" method="POST">
<input type="submit" name="logout" value="logout"/>
</form>
<form action="upload.php" method="POST">
<input type="submit" name="sell" value="sell"/>
</form>
<form action="delete.php" method="POST">
<input type="submit" name="Remove" value="Remove"/>
</form>
</fieldset>
<br/>

<?php
   error_reporting(E_ALL);
    ini_set('display_errors','On');
require 'aws/aws-autoloader.php';
     use \Aws\DynamoDb\DynamoDbClient;
   if(isset($_POST['logout']))
   {
      session_unset();
      header('Location: login.php');
   }
   try
       {
	      //create a client instance of DynamoDbClient
          $client = DynamoDbClient::factory(array(
            'key' => 'Enter your key',
               'secret' => 'Enter your secret key',
               'region' => ''  // replace with your desired region
          ));
         echo '<fieldset style="background-color:#999999;">';
         echo "<table border='1'>";
         echo "<tbody>";
		 //the table is scaned to display all the items that are for sale
         $response = $client->scan(array(
         'TableName' => 'ProductCatalog'
)); 
 echo '<tr><td><b>Id</b></td>';
 echo '<td><b>UserId</b></td>';
   echo '<td><b>Description</b></td>';
   echo '<td><b>Product</b></td>';
   echo '<td><b>Category</b></td></tr>';
//the items are looped to display
foreach ($response['Items'] as $key => $value) {
/*    echo '<tr><td><b>UserId:</b></td>';
   echo '<td><b>Description:</b></td>';
   echo '<td><b>Product:</b></td></tr>'; */
   $v=$value['Product']['S'];
   echo  '<tr><td> ' . $value['Id']['N'] . '</td>';
   echo  '<td> ' . $value['UserId']['S'] . '</td>';
    echo '<td> ' .  $value['Description']['S'] . '</td>';
    //echo '<td><img src='. $v.'></img>' . '</td>';
	echo '<td> ' . file_get_contents($v) . '</td>';
	echo '<td> ' . $value['Category']['S'] . '</td></tr>';
	
    //echo 'PostedBy: ' . $value['PostedBy']['S'] . PHP_EOL;
    echo PHP_EOL;
}
      echo "</tbody>";
      echo "</table>";
      echo "</fieldset>";
       }
catch (PDOException $e)
       {
           print "Error!: " . $e->POSTMessage() . "<br/>";
           die();
       }


?>
</body>
</html>
