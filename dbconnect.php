<?php

 // this will avoid mysql_connect() deprecation error.
 error_reporting( ~E_DEPRECATED & ~E_NOTICE );
 // but I strongly suggest you to use PDO or MySQLi.
  
 $mysqli=mysqli_connect('localhost','root','root','route');
 
 if ( !$mysqli) {
  die("Connection failed : " . mysqli_error());
 }
 
 if ( !$mysqli ) {
  die("Database Connection failed : " . mysqli_error());
 }