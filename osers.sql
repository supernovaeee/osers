-- Table structure for table `user`
--

-- CREATE TABLE `user` (
--   `ID` int(4) AUTO_INCREMENT,
--   `sID` varchar(8) NOT NULL,
--   `name` varchar(50) NOT NULL,
--   `phone` char(8) NOT NULL,
--   `email` varchar(25) NOT NULL,
--   `type` varchar(10) NOT NULL,
--   `password` varchar(20) NOT NULL,
--   PRIMARY KEY (`ID`)
-- );

-- INSERT INTO `user` (`sID`, `name`,`phone`, `email`, `type`, `password`) VALUES
-- ('10227392', 'Asa Snova', '81817676', 'abc@gmail.com', 'student', 'helloworld'),
-- ('10337390', 'Marcellinus Priesto', '81827676', 'nyunyu@gmail.com', 'student', 'nyunyu'),
-- ('10256787', 'Sam Avaro', '85857354', 'samdut@gmail.com', 'student', 'helloworld'),
-- ('10899157', 'Alice Rachael', '89675432', 'cidut@gmail.com', 'student', 'helloworld'),
-- ('716522', 'Fitzgerald Ryan', '81106577', 'asdf@gmail.com', 'educator', 'asdf'),
-- ('716523', 'Jeremy Yang', '81106577', 'jyang@gmail.com', 'educator', 'asdf'),
-- ('716524', 'Liam Carolus Hus', '81106577', 'liamhus@gmail.com', 'educator', 'asdf'),
-- ('716525', 'Adam Cheng', '89768857', 'acheng@gmail.com', 'educator', 'asdf'),
-- ('716526', 'Levina Lee', '89798868', 'levlee@gmail.com', 'educator', 'asdf'),
-- ('716527', 'Clara Kayden', '89798869', 'ckay@gmail.com', 'educator', 'asdf'),
-- ('1025', 'Mr. Roti', '93515799', 'qwerty@gmail.com', 'admin', 'qwerty'),
-- ('1026', 'Baby Koala', '93515799', 'zxcv@gmail.com', 'admin', 'zxcv'),
-- ('1027', 'Mazza', '93515799', 'meowmeow@gmail.com', 'admin', 'meow');

-- CREATE TABLE `subject` (
--   `code` char(7) NOT NULL,
--   `name` varchar(50) NOT NULL,
--   `lecturer` varchar(50) NOT NULL,
--   `venue` varchar(50) NOT NULL,
--   `type` varchar(16) NOT NULL,
-- PRIMARY KEY(`code`)

-- );

INSERT INTO `subject`(`code`,`name`, `lecturer`, `venue`, `type`) VALUES 
('ISIT307', 'Web Server Programming', 'Fitzgerald Ryan', 'B.1.05','active'),
('ISIT332', 'Business Process Management', 'Jeremy Yang', 'A.4.03','active'),
('CSIT121', 'Programming Fundamentals', 'Adam Cheng', 'A.5.03','active'),
('CSIT127', 'Networks and Communications', 'Liam Carolus Hus', 'B.2.07','inactive'),
('CSIT226', 'Human Computer Interaction', 'Adam Cheng', 'A.2.03','inactive'),
('MATH223', 'Mathematics for Information Technology', 'Levina Lee', 'A.2.05','removed'),
('ECON251', 'Industry and Trade in Asia', 'Clara Kayden', 'B.5.07','removed');

-- CREATE TABLE `user-subject`(
-- `sID` varchar(8) NOT NULL, -- user ID
-- `code` char(7) NOT NULL,  -- subject code the user is assigned to /enrolled in

-- );

-- INSERT INTO `user-subject`(`sID`,`code`) VALUES 
-- ('10227392', 'ISIT307'),
-- ('10227392', 'ISIT332'),
-- ('10337390', 'ISIT307'),
-- ('10256787', 'ISIT332'),
-- ('10899157', 'ISIT332'),
-- ('716522', 'ISIT307'),
-- ('716523', 'ISIT332'),
-- ('716524', 'CSIT127');