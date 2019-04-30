<?php
/********************************************** 
add-moderator.php

adds moderator to Moderates table
***********************************************/

    session_start();

    $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

    $active_id = $_POST['active_ID'];
    $c_id = $_POST['c_ID'];
    $sec_id = $_POST['sec_ID'];

    $get_student_info_query = "INSERT INTO Moderates VALUES ({$sec_id}, {$c_id}, {$active_id});";
    $result2 = mysqli_query($myconnection, $get_student_info_query) or die ('Query failed: ' . mysqli_error($myconnection));

    /*echo('<h1>Congratulations you are successfully added as a Moderator!</h1>');
    echo('<h3><a href="parent-dashboard.php">Back to dashboard</a></h3>');
    echo('<h3><a href="logout.php">Logout</a></h3>');*/
    $object = new stdClass();
    $object->status = 1;

    $the_json = json_encode($object);
    echo($the_json);

    mysqli_close($myconnection);
    exit;
?>