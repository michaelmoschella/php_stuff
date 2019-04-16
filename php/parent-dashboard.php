<?php
/********************************************** 
parent-dashboard.php

Displays various actions as well as notification
for parent user.
***********************************************/
    session_start();

    $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

    $active_id = $_SESSION['active_ID'];

    /*get info of logged in parent*/
    $get_info_query = "SELECT name, role FROM User WHERE {$active_id} = uID;";
    $result1 = mysqli_query($myconnection, $get_info_query) or die ('Query failed: ' . mysqli_error($myconnection));
    $row = mysqli_fetch_row($result1);
    $p_role = $row[1];
    mysqli_free_result($result1);
    $object = new stdClass();
    $object->name=$row[0];
    $object->role=$row[1];
    $object->children=array(); 

    echo("<h1><a href='change-p-profile.php'>Change Your Profile</a></h1>"); // DELETE ME
    
    /* Get children of logged in parent */
    $get_children_query = "SELECT name, role, uid FROM User, Family WHERE Family.pID = {$active_id} AND Family.sID = User.uID;";
    $result2 = mysqli_query($myconnection, $get_children_query) or die ('Query failed: ' . mysqli_error($myconnection));
    while ($row = mysqli_fetch_row($result2)) {
        $child_object = new stdClass();
        $child_object->name=$row[0];
        $child_object->role=$row[1];
        $child_object->uid=$row[2];
        array_push($object->children, $child_object);
        
        echo("<h1><a href='change-c-profile.php?cID=".$row[2]."'>Change Your Child's Profile</a></h1>"); // DELETE ME
    }
    $the_json = json_encode($object);
    echo($the_json);
    echo('<h3><a href="logout.php">Logout</a></h3>'); // DELETE ME

    mysqli_close($myconnection);
    exit;
?>
