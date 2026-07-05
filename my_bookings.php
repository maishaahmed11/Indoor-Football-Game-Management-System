<?php
session_start();
require_once "config.php";

/* AUTH CHECK */
if (!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/* FETCH BOOKINGS */
$stmt = $conn->prepare("
    SELECT id, booking_date, booking_time, payment_status, status
    FROM bookings 
    WHERE user_id = ?
    ORDER BY id DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Bookings</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f3f4f6;
}


.header{
    background:#111827;
    color:white;
    padding:15px 20px;
    text-align:center;
    font-size:18px;
    font-weight:bold;
}


.container{
    width:90%;
    margin:30px auto;
}

.card{
    background:white;
    padding:15px;
    margin-bottom:12px;
    border-radius:8px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}


.info{
    font-size:14px;
    margin:5px 0;
    color:#333;
}


.paid{
    color:green;
    font-weight:bold;
}

.pending{
    color:#f59e0b;
    font-weight:bold;
}


.back{
    display:block;
    text-align:center;
    margin-top:20px;
}

.back a{
    background:#2563eb;
    color:white;
    padding:10px 18px;
    text-decoration:none;
    border-radius:6px;
}

.back a:hover{
    background:#1d4ed8;
}
</style>
</head>

<body>

<div class="header">
    My Bookings
</div>

<div class="container">

<?php if($result->num_rows > 0){ ?>

    <?php while($row = $result->fetch_assoc()){ ?>

        <div class="card">

            <div class="info">
                <strong>Booking ID:</strong> <?= $row['id']; ?>
            </div>

            <div class="info">
                <strong>Date:</strong> <?= htmlspecialchars($row['booking_date']); ?>
            </div>

            <div class="info">
                <strong>Time:</strong> <?= htmlspecialchars($row['booking_time']); ?>
            </div>

            <div class="info">
                <strong>Status:</strong> 
                <?php if($row['payment_status'] == 'paid'){ ?>
                    <span class="paid">Paid</span>
                <?php } else { ?>
                    <span class="pending">Pending</span>
                <?php } ?>
            </div>

        </div>

    <?php } ?>

<?php } else { ?>

    <div class="card">
        No bookings found 
    </div>

<?php } ?>

<div class="back">
    <a href="user_home.php">Back to Home</a>
</div>

</div>

</body>
</html>