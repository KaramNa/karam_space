<?php
$con = new mysqli("localhost","karam","nEwpAthlIfe","karamspace");

if ($con->connect_error){
    die("Connection failed" . $con->connect_error);
    $con->Close();
}
