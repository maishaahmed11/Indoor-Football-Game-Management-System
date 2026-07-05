<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Booking Confirmed</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0f19;
}

.wrapper{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    width:330px;
    background:#111827;
    padding:30px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 0 20px rgba(0,0,0,0.6);
    border:1px solid #1f2937;
}

.icon{
    font-size:55px;
    margin-bottom:10px;
}

h2{
    color:#22c55e;
    margin-bottom:10px;
}

p{
    font-size:13px;
    color:#9ca3af;
    line-height:1.6;
}

.btn{
    display:inline-block;
    margin-top:15px;
    padding:10px 18px;
    background:#2563eb;
    color:white;
    text-decoration:none;
    border-radius:6px;
    font-size:14px;
}

.btn:hover{
    background:#1d4ed8;
}
</style>
</head>

<body>

<div class="wrapper">

    <div class="card">

        <div class="icon">✅</div>

        <h2>Booking Confirmed</h2>

        <p>Your payment has been completed successfully.</p>

        <p>Your booking has been confirmed successfully.</p>

        <a href="my_bookings.php" class="btn">
            View My Bookings
        </a>

    </div>

</div>

</body>
</html>