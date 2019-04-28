<?php
/********************************************** 
s-profile-altered.php

Makes changes to User table based on what information
the student has altered and adds to Mentee and Mentor
tables if necessary.
***********************************************/
    session_start();

    $s_email = $_POST['Email']; 
    $s_pass = $_POST['Password'];
    $s_pass_confirm = $_POST['Confirm_Password'];
    $s_role = $_POST['Role'];
    $s_name = $_POST['Name'];
    $s_phone = $_POST['Phone'];
    $s_username = $_POST['Username'];
    
    $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');
    
    if ($s_role == 'None') {
        $s_role = 'Student';
    }
    if ($s_role == 'Mentor' || $s_role == 'Both'  ){
        $check_mentor_query = "SELECT count(*) FROM Mentor WHERE orID={$_POST['active_ID']};";
        $result1 = mysqli_query($myconnection, $check_mentor_query) or die ('Query failed: ' . mysqli_error($myconnection));
        $row = mysqli_fetch_row($result1);
        if (!$row[0]) {
            $insert_mentor_query = "INSERT INTO Mentor VALUES({$_POST['active_ID']});";
            $result1 = mysqli_query($myconnection, $insert_mentor_query) or die ('Query failed: ' . mysqli_error($myconnection));
        }
    }
    if ($s_role == 'Mentee' || $s_role == 'Both'  ){
        $check_mentee_query = "SELECT count(*) FROM Mentee WHERE eeID={$_POST['active_ID']};";
        $result1 = mysqli_query($myconnection, $check_mentee_query) or die ('Query failed: ' . mysqli_error($myconnection));
        $row = mysqli_fetch_row($result1);
        if (!$row[0]) {
            $insert_mentee_query = "INSERT INTO Mentee VALUES({$_POST['active_ID']});";
            $result1 = mysqli_query($myconnection, $insert_mentee_query) or die ('Query failed: ' . mysqli_error($myconnection));
        }
    }

    /* Build query based on what information changed */
    $update_query = "UPDATE User SET";
    if ($s_email) {
        $update_query .= " email = '${s_email}',"; 
    }
    if ($s_pass) {
        $update_query .= " password = '${s_pass}',"; 
    }
    if ($s_name) {
        $update_query .= " name = '${s_name}',"; 
    }
    if ($s_username) {
        $update_query .= " username = '${s_username}',"; 
    }
    if ($s_phone) {
        $update_query .= " phone = '${s_phone}',"; 
    }
    if ($s_role) {
        $update_query .= " role = '${s_role}',"; 
    }
    $update_query = substr($update_query, 0, -1); #trim off extra ,
    $update_query .= " WHERE uID = {$_POST['active_ID']};";
    $result1 = mysqli_query($myconnection, $update_query) or die ('Query failed: ' . mysqli_error($myconnection));

    $object = new stdClass();
    $object->status = 1; // success

    $the_json = json_encode($object);
    echo($the_json);
    /*echo("
        <h3><a href='student-dashboard.php'>Back to dashboard</a></h3>
        <h3><a href='logout.php'>Logout</a>"
    );*/ // DELETE ME

    mysqli_close($myconnection);
    exit;
?>

