This project contains our work on a music class scheduling system done on PHP and MYSQL using XAMPP AND PHPMYADMIN

STEPS TO RUN:-

1.INSTALL XAMPP AND RUN APACHE AND SQL.
2.COPY THE DatabasePHP FOLDER TO htdocs in xampp FOLDER
3.LOAD THE data1.sql table TO PHPMYADMIN USING IMPORT IN PHPMYADMIN IN LOCAL SERVER.
4.First page is http://localhost/DatabasePHP/index.html

We have 5 modules here:

=========================
Student Management
=========================
-Add New Student
-View Students
-Enroll a Student
-View Students's Timetable

=========================
Instructor Management
=========================
-Add New Instructor
-View Instructor
-View Instructor's Timetable

=========================
Schedule Management
=========================
-Add New Schedule
-View Schedule
-View Student By Schedule

=========================
Class Management
=========================
-Add Class
-View Class

=========================
Backend Management
=========================
-View All Enrollment
-View Students By Instructor
-View Weekday Calendar


process flow for adding new class:
Add Instructor > Add Class > Add new Schedule > Add New Student > Enroll a student


Notes:
1.All view button will have edit/delete function
2.No multiple class are allowed to enroll into same timeslot.