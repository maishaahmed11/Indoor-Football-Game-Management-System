<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['email'], $_SESSION['temp_booking'])) {
    header("Location: index.php");
    exit();
}

$booking = $_SESSION['temp_booking'];


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cancel'])) {

    unset($_SESSION['temp_booking']);

    header("Location: user_home.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm'])) {

    $user_id   = (int)$booking['user_id'];
    $ground_id = (int)$booking['ground_id'];
    $date      = $booking['date'];
    $time      = $booking['time'];

    /* STEP 1: SLOT CHECK */
    $check = $conn->prepare("
        SELECT id 
        FROM bookings 
        WHERE ground_id = ? 
        AND booking_date = ? 
        AND booking_time = ? 
        AND status = 'Booked'
        LIMIT 1
    ");

    $check->bind_param("iss", $ground_id, $date, $time);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        die("❌ This slot is already booked.");
    }
    $payment_method = $_SESSION['payment_method'] ?? '';
    /* STEP 2: INSERT BOOKING */
    $stmt = $conn->prepare("
        INSERT INTO bookings 
        (user_id, ground_id, booking_date, booking_time,payment_method, status, payment_status)
        VALUES (?, ?, ?, ?, ?, 'Booked', 'paid')
    ");

    $stmt->bind_param(
        "iisss",
        $user_id,
        $ground_id,
        $date,
        $time,
        $payment_method
    );

    if ($stmt->execute()) {

        unset($_SESSION['temp_booking']);

        header("Location: payment_success.php");
        exit();

    } else {
        die("❌ Payment failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Confirm Payment</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f6fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:25px;
    border-radius:10px;
    width:350px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    text-align:center;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
    margin-top:10px;
}

.confirm{
    background:#16a34a;
    color:white;
}

.cancel{
    background:red;
    color:white;
}

.confirm:hover{ background:#15803d; }
.cancel:hover{ opacity:0.85; }
</style>
</head>

<body>

<div class="box">
    <h2>Confirm Payment</h2>

    <p><b>Ground:</b> <?= htmlspecialchars($booking['ground_name'] ?? '') ?></p>
    <p><b>Date:</b> <?= htmlspecialchars($booking['date']) ?></p>
    <p><b>Time:</b> <?= htmlspecialchars($booking['time']) ?></p>

    <form method="POST">

        <button type="submit" name="confirm" class="confirm">
            Confirm & Pay
        </button>

        <button type="submit" name="cancel" class="cancel">
            Cancel Booking
        </button>

    </form>
</div>

</body>
</html>