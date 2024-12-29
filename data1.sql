-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 02:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `data1`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_table`
--

CREATE TABLE `class_table` (
  `Class_ID` varchar(20) NOT NULL,
  `Class_Name` text NOT NULL,
  `Instructor_ID` varchar(20) NOT NULL,
  `Room_No` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_table`
--

INSERT INTO `class_table` (`Class_ID`, `Class_Name`, `Instructor_ID`, `Room_No`) VALUES
('CL001', 'Piano', 'INST001', 'room 101'),
('CL002', 'Violin', 'INST002', 'room 102'),
('CL003', 'Drums', 'INST003', 'room 103'),
('CL004', 'Trombone', 'INST004', 'room 104'),
('CL005', 'Guitar', 'INST005', 'room 105'),
('CL006', 'Accordion', 'INST006', 'room 106'),
('CL007', 'Saxophone', 'INST007', 'room 107'),
('CL008', 'Ukulele ', 'INST008', 'room 108'),
('CL009', 'Harp', 'INST009', 'room 109'),
('CL010', 'Cello', 'INST010', 'room 110'),
('CL011', 'Computer', 'INST010', '123');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_table`
--

CREATE TABLE `enrollment_table` (
  `Enrollment_ID` varchar(30) NOT NULL,
  `Student_ID` varchar(20) NOT NULL,
  `Schedule_ID` varchar(20) NOT NULL,
  `Enrollment_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment_table`
--

INSERT INTO `enrollment_table` (`Enrollment_ID`, `Student_ID`, `Schedule_ID`, `Enrollment_Date`) VALUES
('enroll0001', 'ST-2405-001', 'PIA001', '2024-11-17'),
('enroll0002', 'ST-2405-002', 'VIO001', '2024-11-17'),
('enroll0005', 'ST-2407-003', 'SAX001', '2024-11-17'),
('enroll0006', 'ST-2405-001', 'TRO001', '2024-11-17'),
('enroll0007', 'ST-2405-001', 'HAR002', '2024-11-17'),
('enroll0008', 'ST-2405-002', 'CEL002', '2024-11-18'),
('enroll0009', 'ST-2405-003', 'PIA001', '2024-11-19'),
('enroll0010', 'ST-2407-001', 'VIO001', '2024-11-20'),
('enroll0011', 'ST-2407-002', 'DRU001', '2024-11-21'),
('enroll0012', 'ST-2407-003', 'TRO001', '2024-11-22'),
('enroll0013', 'ST-2407-004', 'GUI001', '2024-11-23'),
('enroll0014', 'ST-2407-005', 'ACC001', '2024-11-24'),
('enroll0015', 'ST-2407-006', 'SAX001', '2024-11-25'),
('enroll0016', 'ST-2410-001', 'UKU001', '2024-11-26'),
('enroll0017', 'ST-2410-002', 'HAR001', '2024-11-27'),
('enroll0018', 'ST-2410-003', 'CEL001', '2024-11-28'),
('enroll0019', 'ST-2410-004', 'PIA002', '2024-11-29'),
('enroll0020', 'ST-2410-005', 'VIO002', '2024-11-30'),
('enroll0021', 'ST-2410-006', 'DRU002', '2024-12-01'),
('enroll0022', 'ST-2410-007', 'TRO002', '2024-12-02'),
('enroll0023', 'ST-2410-008', 'GUI002', '2024-12-03'),
('enroll0024', 'ST-2410-009', 'ACC002', '2024-12-04'),
('enroll0025', 'ST-2410-010', 'SAX002', '2024-12-05'),
('enroll0026', 'ST-2412-001', 'UKU002', '2024-12-06'),
('enroll0027', 'ST-2412-002', 'HAR002', '2024-12-07'),
('enroll0028', 'ST-2412-003', 'CEL002', '2024-12-08'),
('enroll0029', 'ST-2412-004', 'TRO002', '2024-12-09');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_table`
--

CREATE TABLE `instructor_table` (
  `Instructor_ID` varchar(20) NOT NULL,
  `Instructor_Name` text NOT NULL,
  `Instructor_Email` varchar(50) NOT NULL,
  `Phone_Number` text NOT NULL,
  `Gender` enum('M','F') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_table`
--

INSERT INTO `instructor_table` (`Instructor_ID`, `Instructor_Name`, `Instructor_Email`, `Phone_Number`, `Gender`) VALUES
('INST001', 'Dr. Lim Wei Sheng', 'lim.weisheng@logischool.com', '60123456704', 'M'),
('INST002', 'Prof. Siti Nur Aisyah binti Razali', 'nur.aisyah@logischool.com', '60189045678', 'F'),
('INST003', 'Arvind Kumar a/l Ramesh', 'arvind.kumar@logischool.com', '60167890127', 'M'),
('INST004', 'Tan Mei Ling', 'tan.meiling@logischool.com', '60187654322', 'F'),
('INST005', 'Mohamad Faiz bin Ahmad', 'faiz.ahmad@logischool.com', '60198765490', 'M'),
('INST006', 'Priya a/p Muniandy', 'priya.muniandy@logischool.com', '60143219876', 'F'),
('INST007', 'Dr. Wong Zhi Hao', 'wong.zhihao@logischool.com', '60123456708', 'M'),
('INST008', 'Khairul Anwar bin Ismail', 'khairul.anwar@logischool.com', '60198765412', 'M'),
('INST009', 'Kavitha a/p Subramaniam', 'kavitha.subramaniam@logischool.com', '60132178954', 'F'),
('INST010', 'Dr. Chong Kai Wen', 'chong.kaiwen@logischool.com', '60123456717', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_table`
--

CREATE TABLE `schedule_table` (
  `Schedule_ID` varchar(20) NOT NULL,
  `Class_Duration` int(5) NOT NULL,
  `Start_Time` time NOT NULL,
  `End_Time` time NOT NULL,
  `Week_Day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `Class_ID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_table`
--

INSERT INTO `schedule_table` (`Schedule_ID`, `Class_Duration`, `Start_Time`, `End_Time`, `Week_Day`, `Class_ID`) VALUES
('ACC001', 120, '10:00:00', '12:00:00', 'Monday', 'CL006'),
('ACC002', 120, '13:00:00', '15:00:00', 'Monday', 'CL006'),
('CEL001', 150, '10:00:00', '12:30:00', 'Friday', 'CL010'),
('CEL002', 150, '13:00:00', '15:30:00', 'Friday', 'CL010'),
('COO001', 150, '11:00:00', '12:30:00', 'Wednesday', 'CL011'),
('DRU001', 120, '10:00:00', '12:00:00', 'Wednesday', 'CL003'),
('DRU002', 120, '13:00:00', '15:00:00', 'Thursday', 'CL003'),
('GUI001', 120, '10:00:00', '12:00:00', 'Friday', 'CL005'),
('GUI002', 120, '13:00:00', '15:00:00', 'Friday', 'CL005'),
('HAR001', 120, '10:00:00', '12:00:00', 'Thursday', 'CL009'),
('HAR002', 120, '13:00:00', '15:00:00', 'Thursday', 'CL009'),
('PIA001', 90, '10:00:00', '11:30:00', 'Monday', 'CL001'),
('PIA002', 90, '13:00:00', '14:30:00', 'Monday', 'CL001'),
('SAX001', 120, '10:00:00', '12:00:00', 'Tuesday', 'CL007'),
('SAX002', 120, '13:00:00', '15:00:00', 'Tuesday', 'CL007'),
('TRO001', 180, '10:00:00', '13:00:00', 'Thursday', 'CL004'),
('TRO002', 180, '13:30:00', '16:30:00', 'Thursday', 'CL004'),
('UKU001', 120, '10:00:00', '12:00:00', 'Wednesday', 'CL008'),
('UKU002', 120, '13:00:00', '15:00:00', 'Wednesday', 'CL008'),
('VIO001', 120, '10:00:00', '12:00:00', 'Tuesday', 'CL002'),
('VIO002', 120, '13:00:00', '15:00:00', 'Tuesday', 'CL002'),
('VIO003', 150, '10:00:00', '11:30:00', 'Monday', 'CL002');

-- --------------------------------------------------------

--
-- Table structure for table `student_table`
--

CREATE TABLE `student_table` (
  `Student_ID` varchar(11) NOT NULL,
  `Student_Name` text NOT NULL,
  `Student_Email` varchar(50) NOT NULL,
  `Contact_No` text NOT NULL,
  `DOB` date NOT NULL,
  `Gender` enum('M','F') NOT NULL,
  `Academic_Status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_table`
--

INSERT INTO `student_table` (`Student_ID`, `Student_Name`, `Student_Email`, `Contact_No`, `DOB`, `Gender`, `Academic_Status`) VALUES
('ST-2405-001', 'John Doe', 'john.doe@logischool.student.com', '60148276341', '1997-01-01', 'M', 'Active'),
('ST-2405-002', 'Emily Smith', 'emily.smith@logischool.student.com', '60112345678', '1998-02-01', 'F', 'Active'),
('ST-2405-003', 'Michael Johnson', 'michael.j@logischool.student.com', '60197854321', '1999-11-21', 'M', 'Active'),
('ST-2407-001', 'Sarah Williams', 'sarah.w@logischool.student.com', '60123409876', '1985-01-18', 'F', 'Active'),
('ST-2407-002', 'Chris Brown', 'chris.b@logischool.student.com', '60189045673', '1988-04-21', 'M', 'Active'),
('ST-2407-003', 'Linda Clark', 'linda.c@logischool.student.com', '60143210987', '1994-12-22', 'F', 'Active'),
('ST-2407-004', 'Robert Harris', 'robert.h@logischool.student.com', '60156789012', '1988-04-21', 'M', 'Active'),
('ST-2407-005', 'Jessica Moore', 'jessica.m@logischool.student.com', '60187654321', '1999-03-25', 'F', 'Active'),
('ST-2407-006', 'David Lee', 'david.l@logischool.student.com', '60123456780', '1999-09-06', 'M', 'Active'),
('ST-2410-001', 'Sophia Walker', 'sophia.w@logischool.student.com', '60198765432', '2000-05-27', 'F', 'Active'),
('ST-2410-002', 'Daniel White', 'daniel.w@logischool.student.com', '60187654310', '2001-09-23', 'M', 'Active'),
('ST-2410-003', 'Evelyn Lewis', 'evelyn.l@logischool.student.com', '60165498732', '2002-10-02', 'F', 'Active'),
('ST-2410-004', 'Noah Martin', 'noah.m@logischool.student.com', '60123456712', '2003-01-12', 'M', 'Active'),
('ST-2410-005', 'Mia Green', 'mia.g@logischool.student.com', '60187654345', '2002-04-09', 'F', 'Active'),
('ST-2410-006', 'James Hall', 'james.h@logischool.student.com', '60109812345', '2002-01-04', 'M', 'Active'),
('ST-2410-007', 'Ava King', 'ava.k@logischool.student.com', '60123456741', '2003-07-11', 'F', 'Active'),
('ST-2410-008', 'Liam Wright', 'liam.w@logischool.student.com', '60123409812', '1998-02-16', 'M', 'Active'),
('ST-2410-009', 'Charlotte Scott', 'charlotte.s@logischool.student.com', '60189045671', '2005-01-20', 'F', 'Active'),
('ST-2410-010', 'Lucas Young', 'lucas.y@logischool.student.com', '60132145678', '2006-01-16', 'M', 'Active'),
('ST-2412-001', 'Amelia Hill', 'amelia.h@logischool.student.com', '60198765431', '1992-06-14', 'F', 'Active'),
('ST-2412-002', 'Olivia Baker', 'olivia.b@logischool.student.com', '60165432109', '2000-03-10', 'F', 'Active'),
('ST-2412-003', 'Henry Allen', 'henry.a@logischool.student.com', '60134567834', '2000-09-20', 'M', 'Active'),
('ST-2412-004', 'Ella Rivera', 'ella.r@logischool.student.com', '60167890198', '2001-05-28', 'F', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_table`
--
ALTER TABLE `class_table`
  ADD PRIMARY KEY (`Class_ID`),
  ADD KEY `Class Relationship` (`Instructor_ID`);

--
-- Indexes for table `enrollment_table`
--
ALTER TABLE `enrollment_table`
  ADD PRIMARY KEY (`Enrollment_ID`),
  ADD KEY `Enrollment and student` (`Student_ID`),
  ADD KEY `Enrollment and schedule` (`Schedule_ID`);

--
-- Indexes for table `instructor_table`
--
ALTER TABLE `instructor_table`
  ADD PRIMARY KEY (`Instructor_ID`);

--
-- Indexes for table `schedule_table`
--
ALTER TABLE `schedule_table`
  ADD PRIMARY KEY (`Schedule_ID`),
  ADD KEY `Schedule and Class` (`Class_ID`);

--
-- Indexes for table `student_table`
--
ALTER TABLE `student_table`
  ADD PRIMARY KEY (`Student_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_table`
--
ALTER TABLE `class_table`
  ADD CONSTRAINT `Class Relationship` FOREIGN KEY (`Instructor_ID`) REFERENCES `instructor_table` (`Instructor_ID`);

--
-- Constraints for table `enrollment_table`
--
ALTER TABLE `enrollment_table`
  ADD CONSTRAINT `Enrollment and schedule` FOREIGN KEY (`Schedule_ID`) REFERENCES `schedule_table` (`Schedule_ID`),
  ADD CONSTRAINT `Enrollment and student` FOREIGN KEY (`Student_ID`) REFERENCES `student_table` (`Student_ID`);

--
-- Constraints for table `schedule_table`
--
ALTER TABLE `schedule_table`
  ADD CONSTRAINT `Schedule and Class` FOREIGN KEY (`Class_ID`) REFERENCES `class_table` (`Class_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
