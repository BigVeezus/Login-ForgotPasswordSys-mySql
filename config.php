<?php

$conn = mysqli_connect('localhost','root','','Practise Database');

if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
echo "Connected Successfully";
?>