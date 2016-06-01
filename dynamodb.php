<?php
error_reporting(E_ALL);
  ini_set('display_errors','On');
require 'aws/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

// Creating Client instance of DynamoDbClient

$client = DynamoDbClient::factory(array(
   'key' => 'Enter your key',
    'secret' => 'Enter your secret key',
    'region' => ''  // replace with your desired region
));

//Table name 
 
$tableName = 'RegisterDetails';
echo "Creating table $tableName..." . PHP_EOL;

//Create table function with UserId being Hash value and Password being Range value

$response = $client->createTable(array(
    'TableName' => $tableName,
    'AttributeDefinitions' => array(
     //   array('AttributeName' => 'Fname',      'AttributeType' => 'S'),
      //  array('AttributeName' => 'Email',      'AttributeType' => 'S'),
        array('AttributeName' => 'UserId',     'AttributeType' => 'S'),
         array('AttributeName' => 'Pwd',       'AttributeType' => 'S')
    ),

    'KeySchema' => array(
        array('AttributeName' => 'UserId',   'KeyType' => 'HASH' )
        array('AttributeName' => 'Pwd',     'KeyType' => 'RANGE' )
     ),

    'ProvisionedThroughput' => array(
         'ReadCapacityUnits' => 1,
         'WriteCapacityUnits' => 1
    )
));
echo "Table $tableName has been created." . PHP_EOL;
?>
