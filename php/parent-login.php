<?php
/********************************************** 
parent-login.php

Check that parent entered correct email and password
and start session holding their uID
***********************************************/
    session_start();
    $p_email = $_POST['Parent_Email_Login'];
    $p_pass = $_POST['Parent_Pass_Login'];

    $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

    $get_pass_query = "SELECT password, username, uID FROM User WHERE email = \"{$p_email}\" AND uID IN
    (SELECT pID FROM Parent);";
    $result1 = mysqli_query($myconnection, $get_pass_query) or die ('Query failed: ' . mysqli_error($myconnection));
    $row = mysqli_fetch_row($result1);
    if ($row){
        $p_stored_pass = $row[0];
        $p_username = $row[1];
        if ($p_stored_pass == $p_pass) {
            $_SESSION["active_ID"] = $row[2];
            $object = new stdClass();
            $object->status = 1; // 1 indicates success
            $object->p_username = $p_username;
           /* echo("
                <h3><a href='./parent-dashboard.php'>Click here to go to your parent dashboard</a></h3>
                <h5><a href='./logout.php'>Logout</a></h5>"); // DELETE ME*/
        } else {
            $object = new stdClass();
            $object->status = 0; // 0 indicates failure: password does not match email
            $object->p_email = $p_email;
           // echo("            <h3><a href='../Phase2.html'>Back to main page</a></h3>"); // DELETE ME
        }
    } else {
        $object = new stdClass();
        $object->status = -1; // 0 indicates failure: bad email
        $object->p_email = $p_email;
       // echo("            <h3><a href='../Phase2.html'>Back to main page</a></h3>"); // DELETE ME
    }
    $the_json = json_encode($object);
    echo($the_json);

    mysqli_free_result($result1);

    mysqli_close($myconnection);
    exit;
?>