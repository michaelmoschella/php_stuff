<?php
/********************************************** 
p-profile-altered.php

Inserts new info for parent profile in User
table and adds to Moderator table if necessary.
***********************************************/
    session_start();

    $p_email = $_POST['Parent_Email']; 
    $p_pass = $_POST['Parent_Pass'];
    $p_pass_confirm = $_POST['Parent_Confirm_Pass'];
    $p_role = $_POST['p_role'];
    $p_name = $_POST['Parent_Name'];
    $p_phone = $_POST['Parent_Phone_Number'];
    $p_username = $_POST['p_username'];

    $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

    if ($p_role == 'None') {
        $p_role = 'Parent';
    } else {
        $check_moderator_query = "SELECT count(*) FROM Moderator WHERE modID={$_SESSION['active_ID']};";
        $result1 = mysqli_query($myconnection, $check_moderator_query) or die ('Query failed: ' . mysqli_error($myconnection));
        $row = mysqli_fetch_row($result1);
        if (!$row[0]) {
            $insert_moderator_query = "INSERT INTO Moderator VALUEs({$_SESSION['active_ID']});";
            $result1 = mysqli_query($myconnection, $insert_moderator_query) or die ('Query failed: ' . mysqli_error($myconnection));
        }
    }


    /* build query based on what info was entered*/
    $update_query = "UPDATE User SET";
    if ($p_email) {
        $update_query .= " email = '${p_email}',"; 
    }
    if ($p_pass) {
        $update_query .= " password = '${p_pass}',"; 
    }
    if ($p_name) {
        $update_query .= " name = '${p_name}',"; 
    }
    if ($p_username) {
        $update_query .= " username = '${p_username}',"; 
    }
    if ($p_phone) {
        $update_query .= " phone = '${p_phone}',"; 
    }
    if ($p_role) {
        $update_query .= " role = '${p_role}',"; 
    }
    $update_query = substr($update_query, 0, -1); #trim off extra ,
    $update_query .= " WHERE uID = {$_SESSION['active_ID']};";
    $result1 = mysqli_query($myconnection, $update_query) or die ('Query failed: ' . mysqli_error($myconnection));

    $object = new stdClass();
    $object->status=1; // success

    $the_json = json_encode($object);
    echo($the_json);

    echo("
        <h3><a href='parent-dashboard.php'>Back to dashboard</a></h3>
        <h3><a href='logout.php'>Logout</a>"
    ); // DELETE ME

    mysqli_close($myconnection);
    exit;
?>
