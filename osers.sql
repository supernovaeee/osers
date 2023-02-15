-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(4) AUTO_INCREMENT,
  `sID` varchar(8) NOT NULL,
  `name` varchar(18) NOT NULL,
  `surname` varchar(18) NOT NULL,
  `phone` char(8) NOT NULL,
  `email` varchar(25) NOT NULL,
  `type` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO `user` (`sID`, `name`, `surname`, `phone`, `email`, `type`, `password`) VALUES
('10227392', 'Azza', 'Sekarnova', '81817676', 'abc@gmail.com', 'student', 'helloworld'),
('716522', 'Richard', 'Baxter', '82856762', 'asdf@gmail.com', 'educator', 'asdf'),
('1025', 'Alicia', 'Rachel', '93515799', 'qwerty@gmail.com', 'admin', 'qwerty');
