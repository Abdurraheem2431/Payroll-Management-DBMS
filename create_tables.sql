------------------------------
-- CREATE TABLES
------------------------------

CREATE TABLE Department (
    DepartmentID   VARCHAR(30) PRIMARY KEY,
    DepartmentName VARCHAR(100) NOT NULL,
    Budget         NUMBER(12,2) DEFAULT 0.00
);

CREATE TABLE Position (
    PositionID VARCHAR(30) PRIMARY KEY,
    Title      VARCHAR(100) NOT NULL,
    BasePay    NUMBER(10,2) NOT NULL
);

CREATE TABLE Employee (
    EmployeeID   VARCHAR(30) PRIMARY KEY,
    Name         VARCHAR(100) NOT NULL,
    ContactInfo  VARCHAR(200),
    DateOfBirth  DATE NOT NULL,
    DepartmentID VARCHAR(30) NOT NULL,
    PositionID   VARCHAR(30) NOT NULL,
    FOREIGN KEY (DepartmentID) REFERENCES Department(DepartmentID),
    FOREIGN KEY (PositionID) REFERENCES Position(PositionID)
);

CREATE TABLE Payroll (
    PayrollID      VARCHAR(30) PRIMARY KEY,
    EmployeeID     VARCHAR(30) NOT NULL,
    PayPeriodStart DATE NOT NULL,
    PayPeriodEnd   DATE NOT NULL,
    GrossSalary    NUMBER(10,2) NOT NULL,
    NetSalary      NUMBER(10,2) NOT NULL,
    DataProcessed  DATE NOT NULL,
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

CREATE TABLE Payment (
    PaymentID        VARCHAR(30) PRIMARY KEY,
    PayrollID        VARCHAR(30) NOT NULL,
    PaymentMethod    VARCHAR(20),
    CompletionStatus VARCHAR(20),
    PaymentDate      DATE NOT NULL,
    FOREIGN KEY (PayrollID) REFERENCES Payroll(PayrollID)
);

CREATE TABLE Attendance (
    AttendanceID   VARCHAR(30) PRIMARY KEY,
    EmployeeID     VARCHAR(30) NOT NULL,
    DateWorked     DATE NOT NULL,
    OvertimeHours  NUMBER,
    Status         VARCHAR(30),
    HoursWorked    NUMBER,
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

CREATE TABLE Deduction (
    DeductionID   VARCHAR(30) PRIMARY KEY,
    PayrollID     VARCHAR(30) NOT NULL,
    DeductionType VARCHAR(30),
    Amount        NUMBER,
    FOREIGN KEY (PayrollID) REFERENCES Payroll(PayrollID)
);
