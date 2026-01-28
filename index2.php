<?php

$conn = oci_connect(
    'xxxxxxxx',  // replace with your username for Oracle
    'xxxxxxxx', // replace with your password for Oracle
    '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))'
);

if (!$conn) {
    die("<!DOCTYPE html>
    <html>
    <head>
        <title>DB Connection Failed</title>
        <style>
            body { font-family: 'Segoe UI', Arial, sans-serif; background:#0f172a; color:#e5e7eb; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
            .error-card { background:#111827; padding:32px 40px; border-radius:16px; box-shadow:0 20px 40px rgba(0,0,0,0.6); text-align:center; max-width:420px; }
            h1 { margin:0 0 10px; font-size:26px; }
            p { margin:6px 0 0; color:#9ca3af; }
        </style>
    </head>
    <body>
        <div class='error-card'>
            <h1>Database Connection Failed</h1>
            <p>Unable to connect to the database. Please try again later.</p>
        </div>
    </body>
    </html>");
}

function runSqlFile($conn, $filename) {
    $sql = file_get_contents($filename);
    $statements = explode(";", $sql);
    foreach ($statements as $stmt) {
        $trim = trim($stmt);
        if ($trim === "") continue;
        $stid = oci_parse($conn, $trim);
        @oci_execute($stid);
    }
}

function runQuery($conn, $query, $bindings = []) {
    $stid = oci_parse($conn, $query);
    foreach ($bindings as $name => $val) {
        oci_bind_by_name($stid, $name, $val);
    }
    $exec = oci_execute($stid);
    if (!$exec) {
        echo "<div class='alert alert-error'><strong>Database Error:</strong> Operation failed. Please check your inputs and try again.</div>";
        return false;
    }
    return $stid;
}

function printTable($stid) {
    if (!$stid) {
        echo "<div class='alert alert-muted'>No results to display due to an earlier error.</div>";
        return;
    }
    echo "<div class='table-wrapper'><table class='data-table'>";
    echo "<thead><tr>";
    $ncols = oci_num_fields($stid);
    for ($i = 1; $i <= $ncols; $i++) {
        echo "<th>" . htmlspecialchars(oci_field_name($stid, $i)) . "</th>";
    }
    echo "</tr></thead><tbody>";
    $rows = false;
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $rows = true;
        echo "<tr>";
        foreach ($row as $col) {
            echo "<td>" . htmlspecialchars($col) . "</td>";
        }
        echo "</tr>";
    }
    if (!$rows) {
        echo "<tr><td colspan='$ncols' class='empty-cell'>No records found.</td></tr>";
    }
    echo "</tbody></table></div>";
}

$message = "";
$message_class = "";

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "drop") {
        runSqlFile($conn, "drop_tables.sql");
        $message = "All tables were dropped successfully.";
        $message_class = "alert-info";

    } elseif ($action == "create") {
        runSqlFile($conn, "create_tables.sql");
        $message = "Tables were created successfully.";
        $message_class = "alert-success";

    } elseif ($action == "populate") {
        runSqlFile($conn, "populate_tables.sql");
        $message = "Tables were populated successfully.";
        $message_class = "alert-success";

    } elseif ($action == "queries") {
        $message = "Advanced queries executed (Part A). Scroll down to see results.";
        $message_class = "alert-info";

    } elseif ($action == "view") {
        $table = $_GET['table'] ?? '';
        if (empty($table)) {
            $message = "Error: No table specified to view.";
            $message_class = "alert-error";
        }

    } elseif ($action == "search") {
        $t = trim($_POST['table'] ?? '');
        $field = trim($_POST['field'] ?? '');
        $value = trim($_POST['value'] ?? '');

        if (empty($t) || empty($field) || $value === '') {
            $message = "Error: All search fields are required.";
            $message_class = "alert-error";
        }

    } elseif ($action == "update") {
        $t = trim($_POST['table'] ?? '');
        $field = trim($_POST['field'] ?? '');
        $value = $_POST['value'] ?? '';
        $pk = trim($_POST['pk'] ?? '');
        $pkval = $_POST['pkval'] ?? '';

        if (empty($t) || empty($field) || empty($pk) || $pkval === '') {
            $message = "Error: All fields except 'New Value' are required for update.";
            $message_class = "alert-error";
        }

    } elseif ($action == "delete") {
        $t = trim($_POST['table'] ?? '');
        $pk = trim($_POST['pk'] ?? '');
        $val = $_POST['val'] ?? '';

        if (empty($t) || empty($pk) || $val === '') {
            $message = "Error: All fields are required to delete a record.";
            $message_class = "alert-error";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payroll Management Dashboard</title>
    <meta charset="utf-8">
    <style>
        :root {
            --bg: #020617;
            --bg-elevated: #020617;
            --panel: #020617;
            --card: #020617;
            --accent: #22c55e;
            --accent-soft: rgba(34, 197, 94, 0.1);
            --accent-hover: #16a34a;
            --danger: #ef4444;
            --danger-soft: rgba(239, 68, 68, 0.12);
            --text: #e5e7eb;
            --muted: #9ca3af;
            --border: #1f2937;
            --shadow-strong: 0 32px 60px rgba(0, 0, 0, 0.9);
        }
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "SF Pro Text", -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            background: radial-gradient(circle at top, #111827 0, #020617 45%, #000 80%);
            color: var(--text);
            min-height: 100vh;
        }

        .shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px 40px;
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-end;
            margin-bottom: 20px;
        }

        .title-block h1 {
            margin: 0 0 6px;
            font-size: 28px;
            letter-spacing: .03em;
        }
        .title-block p {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(34,197,94,.14), rgba(52,211,153,.05));
            border: 1px solid rgba(34,197,94,.35);
            font-size: 11px;
            color: var(--accent);
        }

        .layout {
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            gap: 18px;
            align-items: flex-start;
        }

        @media (max-width: 960px) {
            .layout {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .sidebar {
            background: radial-gradient(circle at top left, #0f172a 0, #020617 55%);
            border-radius: 20px;
            border: 1px solid rgba(148,163,184,.18);
            box-shadow: var(--shadow-strong);
            padding: 18px 16px 18px;
            position: sticky;
            top: 18px;
        }

        .sidebar h2 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .16em;
            color: var(--muted);
            margin: 0 0 12px;
        }

        .menu-section {
            margin-bottom: 14px;
        }

        .menu-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #64748b;
            margin: 10px 6px 6px;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-list li + li {
            margin-top: 6px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 10px;
            border-radius: 11px;
            text-decoration: none;
            font-size: 13px;
            color: #cbd5f5;
            background: radial-gradient(circle at top left, rgba(15,23,42,.9) 0, rgba(15,23,42,.8) 50%, rgba(15,23,42,.92) 100%);
            border: 1px solid rgba(51,65,85,.65);
            transition: all .16s ease-out;
        }

        .menu-link span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-link .tag {
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 999px;
            border: 1px solid rgba(148,163,184,.5);
            color: #9ca3af;
            background: rgba(15,23,42,.8);
        }

        .menu-link:hover {
            border-color: rgba(34,197,94,.6);
            color: #f9fafb;
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(0,0,0,.85);
        }

        .menu-link.danger {
            border-color: rgba(248,113,113,.6);
            background: radial-gradient(circle at top left, rgba(127,29,29,.9) 0, rgba(15,23,42,.92) 60%);
        }

        .menu-link.danger .tag {
            border-color: rgba(252,165,165,.6);
            color: #fecaca;
        }

        .main {
            background: radial-gradient(circle at top right, #020617 0, #020617 55%);
            border-radius: 24px;
            border: 1px solid rgba(148,163,184,.25);
            box-shadow: var(--shadow-strong);
            padding: 20px 20px 22px;
        }

        .top-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .top-strip h2 {
            margin: 0;
            font-size: 16px;
        }
        .top-strip span {
            font-size: 12px;
            color: var(--muted);
        }

        .alert {
            margin-bottom: 14px;
            padding: 9px 11px;
            border-radius: 11px;
            font-size: 13px;
            border: 1px solid transparent;
        }
        .alert-info {
            background: rgba(37,99,235,.08);
            border-color: rgba(59,130,246,.4);
            color: #bfdbfe;
        }
        .alert-success {
            background: var(--accent-soft);
            border-color: rgba(34,197,94,.5);
            color: #bbf7d0;
        }
        .alert-error {
            background: var(--danger-soft);
            border-color: rgba(248,113,113,.65);
            color: #fecaca;
        }
        .alert-muted {
            background: rgba(15,23,42,.85);
            border-color: rgba(55,65,81,.9);
            color: var(--muted);
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
            gap: 14px;
        }
        @media (max-width: 960px) {
            .content-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .card {
            background: radial-gradient(circle at top left, rgba(15,23,42,.95) 0, #020617 55%);
            border-radius: 16px;
            border: 1px solid rgba(31,41,55,.9);
            padding: 14px 14px 15px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 10px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 14px;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #cbd5f5;
        }

        .card-header span {
            font-size: 11px;
            color: var(--muted);
        }

        form label {
            display: block;
            font-size: 12px;
            margin-bottom: 3px;
            color: var(--muted);
        }

        .field-row {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(120px,1fr));
            gap: 8px;
            margin-bottom: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 7px 8px;
            border-radius: 9px;
            border: 1px solid rgba(55,65,81,.95);
            background: rgba(15,23,42,.96);
            color: var(--text);
            font-size: 13px;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 1px rgba(34,197,94,.4);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 999px;
            font-size: 12px;
            padding: 7px 14px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all .14s ease-out;
        }
        .btn-primary {
            background: linear-gradient(90deg, var(--accent), #4ade80);
            color: #022c22;
        }
        .btn-primary:hover {
            filter: brightness(1.06);
            transform: translateY(-0.5px);
            box-shadow: 0 14px 22px rgba(34,197,94,.35);
        }
        .btn-outline {
            background: transparent;
            border: 1px solid rgba(148,163,184,.7);
            color: #e5e7eb;
        }
        .btn-outline:hover {
            border-color: var(--accent);
            color: #bbf7d0;
            transform: translateY(-0.5px);
        }

        .table-wrapper {
            margin-top: 12px;
            border-radius: 12px;
            border: 1px solid rgba(31,41,55,.9);
            overflow: hidden;
            background: rgba(15,23,42,.96);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .data-table thead {
            background: linear-gradient(90deg, rgba(15,23,42,1), rgba(30,64,175,.9));
        }

        .data-table th,
        .data-table td {
            padding: 7px 8px;
            border-bottom: 1px solid rgba(31,41,55,.95);
        }

        .data-table th {
            text-align: left;
            font-weight: 500;
            color: #e5e7eb;
            white-space: nowrap;
        }

        .data-table tbody tr:nth-child(odd) {
            background: rgba(15,23,42,.94);
        }

        .data-table tbody tr:nth-child(even) {
            background: rgba(15,23,42,.96);
        }

        .data-table tbody tr:hover {
            background: rgba(30,64,175,.55);
        }

        .empty-cell {
            text-align: center;
            color: var(--muted);
            font-style: italic;
        }

        .query-block {
            margin-top: 10px;
            padding: 8px 10px;
            border-radius: 12px;
            background: rgba(15,23,42,.9);
            border: 1px solid rgba(55,65,81,.9);
            font-size: 12px;
        }
        .query-block pre {
            margin: 0;
            white-space: pre-wrap;
            color: #cbd5f5;
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="title-block">
            <h1>Payroll Management Dashboard</h1>
            <p>Department, positions, employees, payroll, and attendance controls for CPS510 – Assignment 9.</p>
        </div>
        <div>
            <div class="badge">
                <span>●</span>
                <span>Oracle · Group 51 · Topic 26</span>
            </div>
        </div>
    </div>

    <div class="layout">
        <aside class="sidebar">
            <h2>Actions</h2>

            <div class="menu-section">
                <div class="menu-label">Setup</div>
                <ul class="menu-list">
                    <li>
                        <a class="menu-link danger" href="?action=drop">
                            <span>Drop All Tables</span>
                            <span class="tag">Destructive</span>
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="?action=create">
                            <span>Create Tables</span>
                            <span class="tag">DDL</span>
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="?action=populate">
                            <span>Populate Tables</span>
                            <span class="tag">Seed Data</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="menu-section">
                <div class="menu-label">Views</div>
                <ul class="menu-list">
                    <li><a class="menu-link" href="?action=view&amp;table=Department"><span>Department</span></a></li>
                    <li><a class="menu-link" href="?action=view&amp;table=Position"><span>Position</span></a></li>
                    <li><a class="menu-link" href="?action=view&amp;table=Employee"><span>Employee</span></a></li>
                    <li><a class="menu-link" href="?action=view&amp;table=Payroll"><span>Payroll</span></a></li>
                    <li><a class="menu-link" href="?action=view&amp;table=Payment"><span>Payment</span></a></li>
                    <li><a class="menu-link" href="?action=view&amp;table=Attendance"><span>Attendance</span></a></li>
                    <li><a class="menu-link" href="?action=view&amp;table=Deduction"><span>Deduction</span></a></li>
                </ul>
            </div>

            <div class="menu-section">
                <div class="menu-label">Reports</div>
                <ul class="menu-list">
                    <li>
                        <a class="menu-link" href="?action=queries">
                            <span>Run Advanced Queries</span>
                            <span class="tag">Part A</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="main">
            <div class="top-strip">
                <div>
                    <h2>Console</h2>
                    <span>Execute actions, search records, and inspect results below.</span>
                </div>
                <form method="get" action="">
                    <button class="btn btn-outline" type="submit">Reset View</button>
                </form>
            </div>

            <?php if (!empty($message)) : ?>
                <div class="alert <?php echo htmlspecialchars($message_class); ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="content-grid">
                <section class="card">
                    <div class="card-header">
                        <h3>Search</h3>
                        <span>Locate a record in any table</span>
                    </div>
                    <form method="post" action="?action=search">
                        <div class="field-row">
                            <div>
                                <label>Table</label>
                                <input type="text" name="table" placeholder="e.g. Employee">
                            </div>
                            <div>
                                <label>Field</label>
                                <input type="text" name="field" placeholder="e.g. EMP_ID">
                            </div>
                            <div>
                                <label>Value</label>
                                <input type="text" name="value" placeholder="Exact match">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Run Search</button>
                    </form>
                </section>

                <section class="card">
                    <div class="card-header">
                        <h3>Delete</h3>
                        <span>Remove a record by primary key</span>
                    </div>
                    <form method="post" action="?action=delete">
                        <div class="field-row">
                            <div>
                                <label>Table</label>
                                <input type="text" name="table" placeholder="e.g. Employee">
                            </div>
                            <div>
                                <label>PK Field</label>
                                <input type="text" name="pk" placeholder="e.g. EMP_ID">
                            </div>
                            <div>
                                <label>PK Value</label>
                                <input type="text" name="val" placeholder="ID to delete">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline">Delete Record</button>
                    </form>
                </section>
            </div>

            <section class="card" style="margin-top:12px;">
                <div class="card-header">
                    <h3>Update</h3>
                    <span>Modify a single field for a given row</span>
                </div>
                <form method="post" action="?action=update">
                    <div class="field-row">
                        <div>
                            <label>Table</label>
                            <input type="text" name="table" placeholder="e.g. Employee">
                        </div>
                        <div>
                            <label>PK Field</label>
                            <input type="text" name="pk" placeholder="e.g. EMP_ID">
                        </div>
                        <div>
                            <label>PK Value</label>
                            <input type="text" name="pkval" placeholder="ID to update">
                        </div>
                        <div>
                            <label>Field To Change</label>
                            <input type="text" name="field" placeholder="e.g. SALARY">
                        </div>
                        <div>
                            <label>New Value</label>
                            <input type="text" name="value" placeholder="New value">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Update</button>
                </form>
            </section>

            <section style="margin-top:14px;">
                <?php
                if (isset($_GET['action'])) {
                    $action = $_GET['action'];

                    if ($action == "queries") {
                        echo "<div class='card'><div class='card-header'><h3>Advanced Queries (Part A)</h3><span>Raw SQL and result sets</span></div>";
                        $queries = explode(";", file_get_contents("queries.sql"));
                        foreach ($queries as $q) {
                            $t = trim($q);
                            if ($t == "") continue;
                            echo "<div class='query-block'><strong>Query</strong><pre>" . htmlspecialchars($t) . "</pre></div>";
                            $stid = runQuery($conn, $t);
                            printTable($stid);
                        }
                        echo "</div>";
                    } elseif ($action == "view") {
                        $table = $_GET['table'] ?? '';
                        if (!empty($table)) {
                            echo "<div class='card'><div class='card-header'><h3>Table: " . htmlspecialchars($table) . "</h3><span>All rows and columns</span></div>";
                            $stid = oci_parse($conn, "SELECT * FROM " . $table);
                            $exec = @oci_execute($stid);
                            if (!$exec) {
                                echo "<div class='alert alert-error'>Table has not been made.</div>";
                            } else {
                                printTable($stid);
                            }
                            echo "</div>";
                        }
                    } elseif ($action == "search") {
                        $t = trim($_POST['table'] ?? '');
                        $field = trim($_POST['field'] ?? '');
                        $value = trim($_POST['value'] ?? '');
                        if (!empty($t) && !empty($field) && $value !== '') {
                            $tSafe = htmlspecialchars($t);
                            $fieldSafe = htmlspecialchars($field);
                            $valueSafe = htmlspecialchars($value);
                            echo "<div class='card'><div class='card-header'><h3>Search Result</h3><span>$tSafe where $fieldSafe = \"$valueSafe\"</span></div>";
                            $query = "SELECT * FROM $tSafe WHERE $fieldSafe = :val";
                            $stid = oci_parse($conn, $query);
                            oci_bind_by_name($stid, ':val', $value);
                            $exec = oci_execute($stid);
                            if (!$exec) {
                                echo "<div class='alert alert-error'>Database error. Operation failed. Please check your inputs and try again.</div>";
                            } else {
                                printTable($stid);
                            }
                            echo "</div>";
                        }
                    } elseif ($action == "update") {
                        $t = trim($_POST['table'] ?? '');
                        $field = trim($_POST['field'] ?? '');
                        $value = $_POST['value'] ?? '';
                        $pk = trim($_POST['pk'] ?? '');
                        $pkval = $_POST['pkval'] ?? '';

                        if (!empty($t) && !empty($field) && !empty($pk) && $pkval !== '') {
                            $query = "UPDATE $t SET $field = :newval WHERE $pk = :pkval";
                            $stid = oci_parse($conn, $query);
                            oci_bind_by_name($stid, ':newval', $value);
                            oci_bind_by_name($stid, ':pkval', $pkval);
                            $exec = oci_execute($stid);
                            if ($exec) {
                                echo "<div class='alert alert-success'>Record updated successfully.</div>";
                            } else {
                                echo "<div class='alert alert-error'>Database error. Operation failed. Please check your inputs and try again.</div>";
                            }
                        }
                    } elseif ($action == "delete") {
                        $t = trim($_POST['table'] ?? '');
                        $pk = trim($_POST['pk'] ?? '');
                        $val = $_POST['val'] ?? '';

                        if (!empty($t) && !empty($pk) && $val !== '') {
                            $query = "DELETE FROM $t WHERE $pk = :val";
                            $stid = oci_parse($conn, $query);
                            oci_bind_by_name($stid, ':val', $val);
                            $exec = oci_execute($stid);
                            if ($exec) {
                                echo "<div class='alert alert-success'>Record deleted successfully.</div>";
                            } else {
                                echo "<div class='alert alert-error'>Database error. Operation failed. Please check your inputs and try again.</div>";
                            }
                        }
                    }
                }
                ?>
            </section>
        </main>
    </div>
</div>
</body>
</html>
