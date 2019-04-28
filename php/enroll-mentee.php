<?php
/********************************************** 
enroll-mentee.php

Puts mentees id and section info in the Learns
table which stores which mentees are enrolled
in which sections.
***********************************************/
    session_start();
  
    $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');
    
    $active_id = $_POST['active_ID'];
    $c_id = $_POST['cID'];
    $sec_id = $_POST['secID'];

    $get_student_info_query = "INSERT INTO Learns VALUES ({$sec_id}, {$c_id}, {$active_id});";
    $result2 = mysqli_query($myconnection, $get_student_info_query) or die ('Query failed: ' . mysqli_error($myconnection));

    $object = new stdClass();
    $object->status = 1;

    $the_json = json_encode($object);
    echo($the_json);

    mysqli_close($myconnection);
    exit;
?>