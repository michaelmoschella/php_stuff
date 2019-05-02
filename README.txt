Mike Moschella
Ross Hall
DB 2
Phase 3
5/1/19

To Install Software:

- Make sure XAMPP is running on your computer
    and that you have started apache and mysql
    using the control panel.

- Move the folder named "phase3" into your htdocs folder

- Make sure you can access mysql using the command 
    line and use the following commands to set up
    the database:

    - mysql -u root
    - GRANT ALL PRIVILEGES ON *.* TO root@Localhost
    - CREATE DATABASE DB2;
    - USE DB2;
    - source C:\xampp\htdocs\phase3\php_stuff\sql\create_tables.sql

- Make sure the file structure for the php files is C:\xampp\htdocs\phase3\php_stuff\php

- Import the directory DB2phase3 into Android Studio

- Compile and run the Android Studio project

*************************************************************************
Notes: 

- We have implemented all of the tasks in the assignment description.  
In addition to what is listed in the project desription, mentors can view 
the list of sessions that they can mentor, and moderators can view the list
of sessions from sections that they are moderating.  This was not listed anywhere
in the assignment description or mentioned to the whole class as a requirement but 
we decided to implement it to go above and beyond.

- We implemented the project in a style similar to the TA, We used
 different php files for various tasks.

- If a query was made but there is no information (ex. A student is
not enrolled in any sections) no information will show up on the GUI.

You can log in as becky the moderator:
email = becky@becky.com
password = password

You can log in as bart the student:
email = bart@bart.com
password = password




