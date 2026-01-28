------------------------------
-- POPULATE TABLES
------------------------------

INSERT INTO Department VALUES (1, 'Human Resources', 500000);
INSERT INTO Department VALUES (2, 'Finance', 750000);

INSERT INTO Position VALUES (1, 'HR Manager', 60000);
INSERT INTO Position VALUES (2, 'Accountant', 55000);

INSERT INTO Employee VALUES (1, 'William Nylander', 'will.nylander@email.com', TO_DATE('1990-04-15','YYYY-MM-DD'), 1, 1);
INSERT INTO Employee VALUES (2, 'Bob John', 'bob.john@email.com', TO_DATE('1988-09-23','YYYY-MM-DD'), 2, 2);

INSERT INTO Attendance VALUES (1, 1, TO_DATE('2025-09-01','YYYY-MM-DD'), 'Present', 8, 2);
INSERT INTO Attendance VALUES (2, 2, TO_DATE('2025-09-01','YYYY-MM-DD'), 'Present', 7.5, 0);

INSERT INTO Payroll VALUES (1, 1, TO_DATE('2025-09-01','YYYY-MM-DD'), TO_DATE('2025-09-15','YYYY-MM-DD'), 3000, 2800, TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (2, 2, TO_DATE('2025-09-01','YYYY-MM-DD'), TO_DATE('2025-09-15','YYYY-MM-DD'), 2750, 2600, TO_DATE('2025-09-16','YYYY-MM-DD'));

INSERT INTO Deduction VALUES (1, 1, 'Tax', 200);
INSERT INTO Deduction VALUES (2, 2, 'Health Insurance', 150);

INSERT INTO Payment VALUES (1, 1, TO_DATE('2025-09-17','YYYY-MM-DD'), 'Direct Deposit', 'Completed');
INSERT INTO Payment VALUES (2, 2, TO_DATE('2025-09-17','YYYY-MM-DD'), 'Cheque', 'Pending');


INSERT INTO Department VALUES (3, 'IT', 1000000);
INSERT INTO Department VALUES (4, 'Sales', 800000);
INSERT INTO Department VALUES (5, 'Marketing', 600000);

INSERT INTO Position VALUES (3, 'Software Engineer', 85000);
INSERT INTO Position VALUES (4, 'Sales Associate', 45000);
INSERT INTO Position VALUES (5, 'Marketing Specialist', 55000);
INSERT INTO Position VALUES (6, 'IT Support', 50000);
INSERT INTO Position VALUES (7, 'Data Analyst', 70000);

INSERT INTO Employee VALUES (101, 'Alice Johnson', 'alice@email.com', TO_DATE('1990-04-15','YYYY-MM-DD'), 1, 1);
INSERT INTO Employee VALUES (102, 'Bob Smith', 'bob@email.com', TO_DATE('1988-09-23','YYYY-MM-DD'), 2, 2);
INSERT INTO Employee VALUES (103, 'Carol White', 'carol@email.com', TO_DATE('1995-01-12','YYYY-MM-DD'), 3, 3);
INSERT INTO Employee VALUES (104, 'David Green', 'david@email.com', TO_DATE('1993-07-09','YYYY-MM-DD'), 4, 4);
INSERT INTO Employee VALUES (105, 'Eva Brown', 'eva@email.com', TO_DATE('1992-03-19','YYYY-MM-DD'), 5, 5);
INSERT INTO Employee VALUES (106, 'Frank Miller', 'frank@email.com', TO_DATE('1991-11-01','YYYY-MM-DD'), 3, 6);
INSERT INTO Employee VALUES (107, 'Grace Wilson', 'grace@email.com', TO_DATE('1989-02-10','YYYY-MM-DD'), 2, 7);
INSERT INTO Employee VALUES (108, 'Henry Adams', 'henry@email.com', TO_DATE('1994-12-22','YYYY-MM-DD'), 1, 1);
INSERT INTO Employee VALUES (109, 'Irene Thomas', 'irene@email.com', TO_DATE('1996-05-28','YYYY-MM-DD'), 4, 4);
INSERT INTO Employee VALUES (110, 'Jack Lee', 'jack@email.com', TO_DATE('1993-10-02','YYYY-MM-DD'), 5, 5);

INSERT INTO Attendance VALUES ('1','101',TO_DATE('2025-09-01','YYYY-MM-DD'),2,'Present',8);
INSERT INTO Attendance VALUES ('2','102',TO_DATE('2025-09-01','YYYY-MM-DD'),0,'Present',8);
INSERT INTO Attendance VALUES ('3','103',TO_DATE('2025-09-01','YYYY-MM-DD'),1,'Present',9);
INSERT INTO Attendance VALUES ('4','104',TO_DATE('2025-09-01','YYYY-MM-DD'),0,'Absent',0);
INSERT INTO Attendance VALUES ('5','105',TO_DATE('2025-09-01','YYYY-MM-DD'),0,'Present',8);
INSERT INTO Attendance VALUES ('6','106',TO_DATE('2025-09-01','YYYY-MM-DD'),3,'Present',10);
INSERT INTO Attendance VALUES ('7','107',TO_DATE('2025-09-01','YYYY-MM-DD'),0,'Present',7);
INSERT INTO Attendance VALUES ('8','108',TO_DATE('2025-09-01','YYYY-MM-DD'),0,'Present',8);
INSERT INTO Attendance VALUES ('9','109',TO_DATE('2025-09-01','YYYY-MM-DD'),1,'Present',9);
INSERT INTO Attendance VALUES ('10','110',TO_DATE('2025-09-01','YYYY-MM-DD'),0,'Present',8);

INSERT INTO Payroll VALUES (1,101,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),3200,3000,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (2,102,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),2800,2650,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (3,103,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),4000,3800,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (4,104,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),2500,2400,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (5,105,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),2700,2600,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (6,106,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),4200,3950,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (7,107,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),3100,3000,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (8,108,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),3300,3150,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (9,109,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),2900,2750,TO_DATE('2025-09-16','YYYY-MM-DD'));
INSERT INTO Payroll VALUES (10,110,TO_DATE('2025-09-01','YYYY-MM-DD'),TO_DATE('2025-09-15','YYYY-MM-DD'),2950,2800,TO_DATE('2025-09-16','YYYY-MM-DD'));

INSERT INTO Deduction VALUES (1,1,'Tax',150);
INSERT INTO Deduction VALUES (2,1,'Health Insurance',50);
INSERT INTO Deduction VALUES (3,2,'Tax',120);
INSERT INTO Deduction VALUES (4,3,'Tax',200);
INSERT INTO Deduction VALUES (5,3,'Health Insurance',75);
INSERT INTO Deduction VALUES (6,4,'Tax',100);
INSERT INTO Deduction VALUES (7,5,'Tax',110);
INSERT INTO Deduction VALUES (8,6,'Tax',210);
INSERT INTO Deduction VALUES (9,6,'Health Insurance',60);
INSERT INTO Deduction VALUES (10,7,'Tax',150);
INSERT INTO Deduction VALUES (11,8,'Tax',180);
INSERT INTO Deduction VALUES (12,9,'Tax',140);
INSERT INTO Deduction VALUES (13,10,'Tax',130);
INSERT INTO Deduction VALUES (14,10,'Pension',60);

INSERT INTO Payment VALUES ('1','1','Direct Deposit','Completed',TO_DATE('2025-09-17','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('2','2','Cheque','Completed',TO_DATE('2025-09-17','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('3','3','Direct Deposit','Pending',TO_DATE('2025-09-17','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('4','4','Cheque','Completed',TO_DATE('2025-09-18','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('5','5','Direct Deposit','Completed',TO_DATE('2025-09-18','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('6','6','Cheque','Pending',TO_DATE('2025-09-18','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('7','7','Direct Deposit','Completed',TO_DATE('2025-09-18','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('8','8','Direct Deposit','Completed',TO_DATE('2025-09-19','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('9','9','Cheque','Pending',TO_DATE('2025-09-19','YYYY-MM-DD'));
INSERT INTO Payment VALUES ('10','10','Direct Deposit','Completed',TO_DATE('2025-09-19','YYYY-MM-DD'));

COMMIT;
