<?php
/********************************************** 
parent-moderate-section-session.php

Displays a table with information about all the 
different sessions
***********************************************/
    session_start();

    $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

    $active_id = $_POST['active_ID'];

    /* get section information */
    $get_section_info_query = "SELECT Course.title, Course.orReq, Course.eeReq,
        Section.name, Section.tuition, Section.startDate, Section.endDate,
        Schedule.startTime, Schedule.endTime, Schedule.days,
        Course.cID, Section.SecID,
        Section.mentorCap, Section.menteeCap, Course.description
        FROM Course, Section, Schedule, Moderates
        WHERE Course.cID = Section.cID AND
            Section.secID = Moderates.secID AND
            Section.cID = Moderates.cID AND
            Course.cID = Moderates.cID AND
            Section.schedID = Schedule.schedID AND
            Moderates.modID = {$active_id};";
    $result1 = mysqli_query($myconnection, $get_section_info_query) or die ('Query failed: ' . mysqli_error($myconnection));


    /*$html_string = "
        <h1>Section Session List</h1>
        <head>
            <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }

                td, th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
                tr:nth-child(even) {
                    background-color: #dddddd;
                }
            </style>
        </head>
        <table style='width:75%' style='height:15%'>
            <tr>
                <th>Course Title</th>
                <th>Section Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Time Slot</th>
                <th>Capacity</th>
                <th>Mentor Req</th>
                <th>Mentee Req</th>
                <th>Enrolled Mentor</th>
                <th>Enrolled Mentee</th>
                <th colspan = '2'>Description</th>
            </tr>";*/
    $object = new stdClass();
    $object->sections=array();


    while ($row = mysqli_fetch_row($result1)){
        /*Get number of mentors enrolled in section*/
        $get_mentor_count_query = "SELECT count(*) FROM Teaches
            WHERE Teaches.secID = {$row[11]} AND Teaches.cID = {$row[10]};";
        $result3 = mysqli_query($myconnection, $get_mentor_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
        $a_row1 = mysqli_fetch_row($result3);
        mysqli_free_result($result3);

        /*Get number of mentees enrolled in section */
        $get_mentee_count_query = "SELECT COUNT(*) FROM Learns
            WHERE Learns.secID = {$row[11]} AND Learns.cID = {$row[10]};";
        $result4 = mysqli_query($myconnection, $get_mentee_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
        $a_row2 = mysqli_fetch_row($result4);
        mysqli_free_result($result4);

        $section_obj = new stdClass();
        $section_obj->Course_Title = $row[0];
        $section_obj->Section_Name = $row[3];
        $section_obj->Sessions = array();

    /*    $html_string .= "
        <tr>
            <td>$row[0]</td>
            <td>$row[3]</td>
            <td>$row[5]</td>
            <td>$row[6]</td>
            <td>$row[7] - $row[8]</td>
            <td>$row[4]</td>
            <td>$row[1]</td>
            <td>$row[2]</td>
            <td>$a_row1[0]/$row[12]</td>
            <td>$a_row2[0]/$row[13]</td>
            <td colspan = '2'>$row[14]:</td>
            </tr>";*/

            /* Get information about sessions of that section */
            $get_session_info_query_two = "SELECT Session.announcement, Session.sesID, Session.name, Session.theDate
                FROM Session, Section
                WHERE Session.cID = Section.cID AND Session.secID = Section.secID AND Session.cID = $row[10] AND Session.secID = $row[11];";
            $result2 = mysqli_query($myconnection, $get_session_info_query_two) or die ('Query failed: ' . mysqli_error($myconnection));

            $count=0;
            while ($row2 = mysqli_fetch_row($result2)){
                /* Get number of mentors participating in the session */
                $get_mentor_count_query = "SELECT count(*) FROM SessTeach 
                    WHERE SessTeach.sesID = {$row2[1]} AND SessTeach.secID = {$row[11]} AND SessTeach.cID = {$row[10]};";
                $result3 = mysqli_query($myconnection, $get_mentor_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $count1 = mysqli_fetch_row($result3);
                mysqli_free_result($result3);
                
                /* Get number of mentees participating in session */
                $get_mentee_count_query = "SELECT COUNT(*) FROM SessLearn 
                    WHERE SessLearn.sesID = {$row2[1]} AND SessLearn.secID = {$row[11]} AND SessLearn.cID = {$row[10]};";
                $result4 = mysqli_query($myconnection, $get_mentee_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $count2 = mysqli_fetch_row($result4);               

               /* if($count==0){$html_string .=  "<tr>
                    <th colspan='3' style = 'text-align: center;''>Session Info</th>
                    <th colspan = '1'>Session ID</th>
                    <th colspan = '2'>Session Name</th>
                    <th colspan = '2'>Date</th>
                    <th>Mentor Count</th>
                    <th>Mentee Count</th>
                    <th>Post Session Materials</th>
                    <th>Assign Mentor for Session</th>
                </tr>";}
                $count++;*/
                $session_obj = new stdClass();
                $session_obj->Session_Name = $row2[2];
                $session_obj->Announcement = $row2[0];
                $session_obj->Date = $row2[3];
                $session_obj->Start_Time = $row[7];
                $session_obj->End_Time = $row[8];
                $session_obj->Mentor_Count = $count1[0];
                $session_obj->Mentee_Count = $count2[0];
                $session_obj->cID = $row[10];
                $session_obj->secID = $row[11];
                $session_obj->sesID = $row2[1];

/*
                $html_string .= "<tr>
                    <td colspan='3' style = 'text-align: center;''>$row2[0]</th>
                    <td colspan = '1'>$row2[1]</th>
                    <td colspan = '2'>$row2[2]</th>
                    <td colspan = '2'>$row2[3]</th>
                    <td>$count1[0]/3</td>
                    <td>$count2[0]/6</td>
                    <td><form method='get' action='post-materials.php'>
                    <input type='hidden' value='".$row[1]."' name='mentorRequire'>
                    <input type='hidden' value='".$row2[1]."' name='sesID'>
                    <input type='hidden' value='".$row[0]."' name='classname'>
                    <input type='hidden' value='".$row[10]."' name='cID'>
                    <input type='hidden' value='".$row2[3]."' name='the_date'>
                    <button type='submit' value='".$row[11]."' name='secID'>Post

                    </form></td>
                    <td><form method='get' action='mentor-candidate-list.php'>
                    <input type='hidden' value='".$row[1]."' name='mentorRequire'>
                    <input type='hidden' value='".$row2[1]."' name='sesID'>
                    <input type='hidden' value='".$row[0]."' name='classname'>
                    <input type='hidden' value='".$row[10]."' name='cID'>
                    <input type='hidden' value='".$row2[3]."' name='the_date'>
                    <button type='submit' value='".$row[11]."' name='secID'>Assign Mentor

                    </form></td>
            </tr>";
*/          array_push($section_obj->Sessions, $session_obj);
        }   
        array_push($object->sections, $section_obj);
    }
    mysqli_free_result($result1);

    $the_json = json_encode($object);
    echo($the_json);
//    $html_string .= "</table>";
//    echo($html_string);
/*
    echo('<h3><a href="parent-dashboard.php">Back to dashboard</a></h3>');
    echo('<h3><a href="logout.php">Logout</a></h3>');
*/
    mysqli_close($myconnection);
    exit;
?>