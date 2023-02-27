<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = mysqli_connect('localhost','root','','Practise Database');

if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>