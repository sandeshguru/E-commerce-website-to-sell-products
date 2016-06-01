
<?php
   session_start();
?>

<form action="" method="POST" enctype="multipart/form-data">
	<fieldset>
	<legend>Image Upload Form</legend>
<textarea name="text" rows="20" cols="50">
</textarea>
<br/>
<label>Select the Category:</label>
<select name="list" id="list" value="list">
<option value="Books">Books</option>
<option value="Clothing">Clothing</option>
<option value="Electronics">Electronics</option>
<option value="Games">Games</option>
</select>
<br/>
<br/>
    <input type="file" name="fileToUpload"><br/><br/>
    <input type="submit" value="sell" name="sell">
</form>
</fieldset>

<?php
//include('image_check.php');
 error_reporting(E_ALL);
   ini_set('display_errors','Off');
require 'aws/aws-autoloader.php';
use \Aws\S3\S3Client;
use \Aws\S3\Exception\S3Exception;
use \Aws\DynamoDb\DynamoDbClient;
$bucket = 'earthquakedetails';
if(isset($_POST['sell']))
{
        //Extracting file name from form 
	    $fileName = $_FILES['fileToUpload']['name'];
        $fileTempName = $_FILES['fileToUpload']['tmp_name'];
        $text=$_POST['text'];
		$cat=$_POST['list'];
        //$ext = getExtension($fileName);
        $user = $_SESSION['userId'];
		//generating random number for each image to be identified
        $a=rand();
        //echo $a;
        //$actual_image_name = time().".".$ext;
        try
	{
	    //creating a client instance of S3Client
		$suk = S3Client::factory(array(
	'key' => 'Enter your key',
               'secret' => 'Enter your secret key',
               'region' => ''  // replace with your desired region
	));
        //creating a client instance of DynamoDbClient
        $client = DynamoDbClient::factory(array(
				'key' => 'Enter your key',
               'secret' => 'Enter your secret key',
               'region' => ''  // replace with your desired region
          ));

/*	if($suk->upload($bucket, $fileName, fopen($fileTempName, 'rb'), 'public-read'))
	//if($suk->putObjectFile($fileTempName, "earthquakedetails", $fileName, S3::ACL_PUBLIC_READ)) 
	{
		echo "We successfully uploaded your file.";
	}
	else
	{
		echo "Something went wrong while uploading your file... sorry.";
	}*/
	    //Inserting the image into S3 bucket
		$res = $suk->putObject(array(
        	'Bucket' => $bucket,
        	'Key'    => $fileName,
        	'Body'   => fopen($fileTempName,'rb'),
		'ACL'    => 'public-read'
		));
            //Image URL	from S3 bucket
              $tak="https://s3.amazonaws.com/earthquakedetails/".$fileName;
			  //Insertion of image,description,category into DynamoDB table
        	$response = $client->putItem(array(
          "TableName" => 'ProductCatalog',
          "Item" => $client->formatAttributes(array(
                           "Id" => $a, //Primary key
                           "UserId" =>  $user, 
                           "Description"  =>  $text,
                           "Product"   =>   $tak,
						   "Category"  =>   $cat
			 )
                )
          ));
//        echo  "<img src=$tak></img>";
	header('Location: sell.php');
	}	
	catch (PDOException $e)
       {
           print "Error!: " . $e->POSTMessage() . "<br/>";
           die();
       }
?>


