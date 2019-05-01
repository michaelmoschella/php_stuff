
<?php
/********************************************** 
enroll-mentor-session.php

Puts mentors id and session info in the SessTeach
table which stores which mentors are enrolled
in which sessions.
***********************************************/
    session_start();
  
    $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');
    
    $active_id = $_POST['active_ID'];
    $c_id = $_POST['cID'];
    $sec_id = $_POST['secID'];
    $ses_id = $_POST['sesID'];

    $insert_mentor_query = "INSERT INTO SessTeach VALUES ({$ses_id}, {$sec_id}, {$c_id}, {$active_id});";
    $result2 = mysqli_query($myconnection, $insert_mentor_query) or die ('Query failed: ' . mysqli_error($myconnection));
/*
    echo('<h1>Congratulations you have successfully enrolled as a Mentor!</h1>');
    echo('<h3><a href="student-view-sessions.php">Back to view sessions</a></h3>');
    echo('<h3><a href="student-dashboard.php">Back to dashboard</a></h3>');
    echo('<h3><a href="logout.php">Logout</a></h3>');
*/
    $object = new stdClass();
    $object->status = 1;

    $the_json = json_encode($object);
    echo($the_json);

    mysqli_close($myconnection);
    exit;
?>
