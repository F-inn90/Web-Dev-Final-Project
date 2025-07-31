CREATE TABLE StudentInfo(
  `ID Card` INT NOT NULL AUTO_INCREMENT,
  `First Name` VARCHAR(255) NOT NULL,
  `Last Name` VARCHAR(255),
  Gender ENUM('Male', 'Female'),
  Address VARCHAR(5000) NOT NULL,
  Class ENUM('9', '10', '11', '12'),
  `Rank` INT NOT NULL,
  PRIMARY KEY (`ID Card`)
);

INSERT INTO StudentInfo(`First Name`, `Last Name`, Gender, Address, Class, `Rank`)
VALUES 
('John', 'Stones', 'Male', 'Phnom Penh', '9', 2);

SELECT * FROM StudentInfo;
