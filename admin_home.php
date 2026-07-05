<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f5f6fa;
}

.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:200px;
    height:100%;
    background:#111;
    padding:20px 0;
}

.sidebar h2{
    color:#fff;
    text-align:center;
    margin-bottom:30px;
    font-size:18px;
}

.sidebar a{
    display:block;
    color:#bbb;
    padding:12px 20px;
    text-decoration:none;
    font-size:14px;
}

.sidebar a:hover{
    background:#222;
    color:#fff;
}


.main{
    margin-left:200px;
    padding:20px;
}


.header{
    background:#fff;
    padding:15px 20px;
    border-radius:8px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h1{
    font-size:18px;
    margin:0;
}

.badge{
    background:#007bff;
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    font-size:12px;
}


.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(200px,1fr));
    gap:15px;
    margin-top:20px;
}

.card{
    background:#fff;
    padding:15px;
    border-radius:8px;
    text-align:center;
}

.card i{
    font-size:25px;
    margin-bottom:10px;
    color:#007bff;
}

.card h3{
    font-size:15px;
    margin-bottom:5px;
}

.card p{
    font-size:12px;
    color:#666;
}

.card a{
    display:inline-block;
    margin-top:10px;
    padding:6px 12px;
    background:#007bff;
    color:#fff;
    text-decoration:none;
    border-radius:5px;
    font-size:12px;
}


.table-box{
    margin-top:20px;
    background:#fff;
    padding:15px;
    border-radius:8px;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

th,td{
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#f9f9f9;
}

</style>
</head>

<body>

<div class="sidebar">
    <h2>FOOTBALL</h2>

    <a href="admin_home.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="manage_grounds.php"><i class="fa fa-futbol"></i> Grounds</a>
    <a href="bookings.php"><i class="fa fa-calendar"></i> Bookings</a>
    <a href="manage_users.php"><i class="fa fa-users"></i> Users</a>
    <a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a>
    <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
</div>


<div class="main">

    
    <div class="header">
        <h1>Welcome, <?= htmlspecialchars($name); ?></h1>
        <div class="badge">ADMIN</div>
    </div>

    
    <div class="cards">

        <div class="card">
            <i class="fa fa-futbol"></i>
            <h3>Grounds</h3>
            <p>Manage football fields</p>
            <a href="manage_grounds.php">Open</a>
        </div>

        <div class="card">
            <i class="fa fa-calendar"></i>
            <h3>Bookings</h3>
            <p>View bookings</p>
            <a href="bookings.php">Open</a>
        </div>

        <div class="card">
            <i class="fa fa-users"></i>
            <h3>Users</h3>
            <p>Manage users</p>
            <a href="manage_users.php">Open</a>
        </div>

        <div class="card">
            <i class="fa fa-chart-line"></i>
            <h3>Reports</h3>
            <p>System reports</p>
            <a href="reports.php">Open</a>
        </div>

    </div>




<div class="table-box">

<h3>Recent Bookings</h3>

<table>
    <tr>
        <th>User</th>
        <th>Ground</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php

    $query = mysqli_query($conn,"
        SELECT
            bookings.*,
            users.name AS user_name,
            grounds.ground_name AS ground_name
        FROM bookings
        LEFT JOIN users
            ON bookings.user_id = users.id
        LEFT JOIN grounds
            ON bookings.ground_id = grounds.id
        ORDER BY bookings.id DESC
        LIMIT 5
    ");

    if(mysqli_num_rows($query) > 0){

        while($row = mysqli_fetch_assoc($query)){
    ?>

    <tr>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['ground_name']) ?></td>
        <td><?= htmlspecialchars($row['booking_date']) ?></td>
        <td><?= htmlspecialchars($row['booking_time']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
    </tr>

    <?php
        }

    } else {
    ?>

    <tr>
        <td colspan="5" style="text-align:center;">
            No bookings found
        </td>
    </tr>

    <?php } ?>

</table>


</div>

</body>
</html>