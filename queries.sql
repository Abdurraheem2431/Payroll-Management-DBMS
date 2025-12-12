
SELECT e.Name AS "Employee Name", p.NetSalary AS "Net Salary ($)"
FROM Employee e, Payroll p
WHERE e.EmployeeID = p.EmployeeID
AND p.NetSalary > (SELECT AVG(NetSalary) FROM Payroll)
ORDER BY p.NetSalary DESC;

SELECT d.DepartmentName AS "Department",
       ROUND(AVG(p.NetSalary),2) AS "Avg Net Salary ($)",
       MAX(p.NetSalary) AS "Highest Net Salary ($)"
FROM Department d, Employee e, Payroll p
WHERE d.DepartmentID = e.DepartmentID
AND e.EmployeeID = p.EmployeeID
GROUP BY d.DepartmentName
ORDER BY "Avg Net Salary ($)" DESC;

SELECT EmployeeID FROM Payroll
INTERSECT
SELECT EmployeeID FROM Attendance;

SELECT d.DepartmentName AS "Department",
       COUNT(DISTINCT e.EmployeeID) AS "Employees",
       ROUND(AVG(p.NetSalary),2) AS "Avg Salary ($)",
       ROUND(SUM(p.GrossSalary),2) AS "Total Payroll ($)",
       ROUND(SUM(dc.Amount),2) AS "Total Deductions ($)"
FROM Department d, Employee e, Payroll p, Deduction dc
WHERE d.DepartmentID = e.DepartmentID
AND e.EmployeeID = p.EmployeeID
AND p.PayrollID = dc.PayrollID
GROUP BY d.DepartmentName
ORDER BY "Avg Salary ($)" DESC;

SELECT e.Name AS "Employee",
       a.DateWorked,
       a.HoursWorked,
       a.OvertimeHours
FROM Employee e, Attendance a
WHERE e.EmployeeID = a.EmployeeID
AND a.OvertimeHours > 1
ORDER BY a.OvertimeHours DESC;

SELECT DepartmentName, Budget
FROM Department
WHERE Budget > (SELECT AVG(Budget) FROM Department)
ORDER BY Budget DESC;

SELECT e.Name AS "Employee",
       SUM(d.Amount) AS "Total Deductions ($)"
FROM Employee e, Payroll p, Deduction d
WHERE e.EmployeeID = p.EmployeeID
AND p.PayrollID = d.PayrollID
GROUP BY e.Name
ORDER BY "Total Deductions ($)" DESC;

SELECT e.Name AS "Employee", 'Worked Overtime' AS "Category"
FROM Employee e, Attendance a
WHERE e.EmployeeID = a.EmployeeID
AND a.OvertimeHours > 0
UNION
SELECT e.Name, 'No Overtime'
FROM Employee e
WHERE e.EmployeeID NOT IN (SELECT EmployeeID FROM Attendance WHERE OvertimeHours > 0)
ORDER BY "Employee";

SELECT 'The current roles in the Finance department are:' AS MSG,
       Title, BasePay
FROM Department d, Employee e, Position p
WHERE DepartmentName = 'Finance'
AND d.DepartmentID = e.DepartmentID
AND e.PositionID = p.PositionID;

COMMIT;
