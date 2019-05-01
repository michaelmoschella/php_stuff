<?php
/**********************************************
change-c-profile.php

parent provide inputs for changing their childs
profile information
***********************************************/
    session_start();

    $c_ID = $_POST['cID']; # get parameter from link

    $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

    $active_id = $_POST['active_ID'];

    /* Get childs current information from User table */
    $get_info_query = "SELECT name, username, password, email, phone, role FROM User WHERE {$c_ID} = uID;";
    $result1 = mysqli_query($myconnection, $get_info_query) or die ('Query failed: ' . mysqli_error($myconnection));
    $row = mysqli_fetch_row($result1);
    mysqli_free_result($result1);

    $object = new stdClass();
    $object->Name=$row[0];
    $object->Username=$row[1];
    $object->Password=$row[2];
    $object->Confirm_Password=$row[2];
    $object->Email=$row[3];
    $object->Phone=$row[4];
    $object->Role=$row[5];

    $the_json = json_encode($object);
    echo($the_json);

    echo("<h3><a href='parent-dashboard.php'>Back to dashboard</a></h3>"); // DELETE ME
    echo("<h3><a href='logout.php'>Logout</a><h3>"); // DELETE ME

    mysqli_close($myconnection);
    exit;
?>
