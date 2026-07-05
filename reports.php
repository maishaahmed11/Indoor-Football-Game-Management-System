<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}


function countData($conn, $query) {
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}


$users = countData($conn, "SELECT COUNT(*) AS total FROM users");
$grounds = countData($conn, "SELECT COUNT(*) AS total FROM grounds");
$bookings = countData($conn, "SELECT COUNT(*) AS total FROM bookings");


?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f5f6fa;
}


.header{
    background:#111827;
    color:white;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header a{
    background:#2563eb;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:6px;
}


.container{
    width:90%;
    margin:20px auto;
}


.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));
    gap:15px;
}


.card{
    background:white;
    padding:15px;
    border-radius:8px;
    text-align:center;
    border:1px solid #eee;
}

.card h2{
    margin:0;
    font-size:22px;
    color:#111827;
}

.card p{
    margin-top:5px;
    font-size:13px;
    color:#666;
}


.blue{ border-left:4px solid #3b82f6; }
.green{ border-left:4px solid #10b981; }
.orange{ border-left:4px solid #f59e0b; }
.red{ border-left:4px solid #ef4444; }

</style>
</head>

<body>

<div class="header">
    <h3>Admin Reports</h3>
    <a href="admin_home.php">Dashboard</a>
</div>

<div class="container">

<div class="grid">

    <div class="card blue">
        <h2><?= $users ?></h2>
        <p>Users</p>
    </div>

    <div class="card green">
        <h2><?= $grounds ?></h2>
        <p>Grounds</p>
    </div>

    <div class="card orange">
        <h2><?= $bookings ?></h2>
        <p>Bookings</p>
    </div>

    

    
    
    
    

</div>

</div>

</body>
</html>