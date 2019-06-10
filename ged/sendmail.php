<?php

echo " 1 ";

$file = 'testfile.txt';
$remote_file = 'readme.txt';

$ftp_server = "10.12.28.11";

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

echo " 2 ";

// login with username and password
$login_result = ftp_login($conn_id, 'asp.antunes', 'lareda10)');

echo " 3 ";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

// close the connection
ftp_close($conn_id);

?>
