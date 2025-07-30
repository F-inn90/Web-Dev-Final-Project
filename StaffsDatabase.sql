
CREATE DATABASE StaffsDatabase;
USE StaffsDatabase;

CREATE TABLE Staffs (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Gender ENUM('F', 'M'),
    Phone_Number VARCHAR(50),
    Position VARCHAR(50),
    Salary INT
);

INSERT INTO Staffs (Name, Gender, Phone_Number, Position, Salary) VALUES
('Davolio Nancy', 'F', '62(877)545-4437', 'Instructor', 800),
('Fuller Andrew', 'F', '86(411)120-9165', 'Assistant', 500),
('Leverling Janet', 'M', '249(943)412-2687', 'Instructor', 800),
('Peacock Margaret', 'F', '95(176)122-4405', 'Assistant', 500),
('Buchanan Steven', 'M', '355(495)357-1235', 'Instructor', 800),
('Suyama Michael', 'M', '86(580)267-4030', 'Head of department', 1500),
('King Robert', 'F', '98(993)127-6349', 'Instructor', 800),
('Callahan Laura', 'F', '86(173)539-5499', 'Head of department', 1500),
('Dodsworth Anne', 'M', '62(346)853-6762', 'Course Supervisor', 1200);

CREATE TABLE Departments (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Dep_Name VARCHAR(100),
    Staff_Id INT,
    FOREIGN KEY (Staff_Id) REFERENCES Staffs(Id)
);

INSERT INTO Departments (Dep_Name, Staff_Id) VALUES
('Computer Science', 1),
('Computer Science', 9),
('Computer Science', 3),
('Accounting', 7),
('Accounting', 8),
('Hospitality and tourism', 2),
('Hospitality and tourism', 4),
('Hospitality and tourism', 5),
('Hospitality and tourism', 6);


SELECT * FROM Staffs WHERE Name LIKE 'C%' AND Salary = 1500;


SELECT Staffs.Id, Staffs.Name, Staffs.Gender, Staffs.Position, Staffs.Salary, Departments.Dep_Name AS Department
FROM Staffs
JOIN Departments ON Staffs.Id = Departments.Staff_Id;


SELECT Staffs.Id, Staffs.Name, Staffs.Gender, Staffs.Position, Staffs.Salary, Departments.Dep_Name AS Department
FROM Staffs
JOIN Departments ON Staffs.Id = Departments.Staff_Id
WHERE Staffs.Salary BETWEEN 800 AND 1500
ORDER BY Departments.Dep_Name ASC, Staffs.Position ASC;


SELECT Departments.Dep_Name AS Department, MIN(Staffs.Salary) AS MIN_Salary, MAX(Staffs.Salary) AS MAX_Salary
FROM Staffs
JOIN Departments ON Staffs.Id = Departments.Staff_Id
GROUP BY Departments.Dep_Name
ORDER BY Departments.Dep_Name DESC;
