<?php
/********************************************** 
student-dashboard.php

Displays actions available to the student as
well as notifications for any  courses that were
canceled for that week.
***********************************************/
    session_start();
    
    $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');
    
    $active_id = $_POST['active_ID'];
    
    /* get info of logged in student */
    $get_info_query = "SELECT name, role, grade FROM User, Student WHERE {$active_id} = uID AND Student.sID = User.uID;";
    $result1 = mysqli_query($myconnection, $get_info_query) or die ('Query failed: ' . mysqli_error($myconnection));
    $row = mysqli_fetch_row($result1);
    mysqli_free_result($result1);

    /*echo("<h1><a href='view-mentor.php'>View Mentor</a></h1>");
    echo("<h1><a href='student-view-sections.php'>View Sections</a></h1>");
    echo("<h1><a href='change-s-profile.php'>Change Your Profile</a></h1>");*/

    $object = new stdClass();
    $object->name=$row[0];
    $object->role=$row[1];
    $object->grade=$row[2]; 

    $the_json = json_encode($object);
    echo($the_json);
    //echo('<h3><a href="logout.php">Logout</a></h3>'); // DELETE ME

    mysqli_close($myconnection);
    exit;
?>
