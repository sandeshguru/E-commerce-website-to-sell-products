This Programming assignment is implemented on Amazon EC2 Server using PHP programming language. The website is hosted with the help of Apache Tomcat Server installed in EC2 server instance. The backend table is created in DynamoDB and all the data is stored in DynamoDB. While the upload of image is first done to Amazon S3 and then from there the URL of the image is copied to DynamoDB table

Flow of Execution:
1. Create an account in AWS.
2.Create an EC2 instance using the putty and Public DNS of putty.
3.Install the Apache Tomcat Server on the EC2 instance.
4.All the php code file should be in this folder.
5.Execute the php pages in the browser initially starting with the login page.
6.Each time any changes is made to this file, apache server should be restarted with the following command:
	sudo service apache2 restart
7.The flow follows like login page (login.php). If user wants to register then to the register page (register.php) or else to the sell page(sell.php).  If he wants to sell something then it takes to the upload page(upload.php).In case he needs to delete any item then he can go to delete page(delete.php). If the user clicks on logout button then he will be back to login page.
