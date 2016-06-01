<?php
session_start();
?>
<fieldset style="background-color:#999999;">
<form action="" method="POST">
<input type="submit" name="Back" value="Back"/>
</form>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    require 'aws/aws-autoloader.php';
    use \Aws\DynamoDb\DynamoDbClient;
	if(isset($_POST['Back']))
   {
      //session_unset();
      header('Location: sell.php');
   }
   try
       {
	      //Creating a client instance and making it global
          $GLOBALS['client'] = DynamoDbClient::factory(array(
               'key' => 'Enter your key',
               'secret' => 'Enter your secret key',
               'region' => ''  // replace with your desired region
          ));
         echo '<fieldset style="background-color:#999999;">';
         echo "<table border='1'>";
         echo "<tbody>";
		 //scaning the table and using the filterexpression to filter the items of only logged in user
         $response = $client->scan(array(
         'TableName' => 'ProductCatalog',
        'ExpressionAttributeValues' =>  array (
            ':val1' => array('S' => $_SESSION['userId'])) ,
        'FilterExpression' => 'UserId = :val1',
         ));
		echo '<tr><td><b>Id</b></td>';
		echo '<td><b>UserId</b></td>';
		echo '<td><b>Description</b></td>';
		echo '<td><b>Product</b></td>';
		echo '<td><b>Category</b></td>';
		echo '<td><b>Delete</b></td></tr>';
		//looping through the scanned output 
		foreach ($response['Items'] as $key => $value) {
			$val=$value['Id']['N'];
			echo  '<tr><td> ' . $val . '</td>';
			echo  '<td> ' . $value['UserId']['S'] . '</td>';
			echo '<td> ' .  $value['Description']['S'] . '</td>';
			echo '<td><img src='. $value['Product']['S'].'></img>' . '</td>';
			echo '<td> ' . $value['Category']['S'] . '</td>';
			//echo '<td><br/><form action="delete.php" method="POST"><input type="hidden" name="del" value="'.$val.'"/><input type="submit" value="Delete"/></form></td></tr>';
			echo '<td><a href="delete.php?delete='.$val.' ">Delete</a></td></tr>';
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

<?php
 error_reporting(E_ALL);
 ini_set('display_errors','On');
 if(isset($_GET['delete']))
 {     
     $i = $_GET['delete'];
     $response = $client->deleteItem(array(
         'TableName' => 'ProductCatalog',
         'Key' => array(
         'Id' => array(
            'N' => $i
        )
    )
));
header('Location: delete.php');

}

?>
</fieldset>



