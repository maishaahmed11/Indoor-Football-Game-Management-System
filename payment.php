<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* SAFE SESSION START */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

/* TEMP BOOKING CHECK (FIXED) */
if (!isset($_SESSION['temp_booking'])) {
    die("Invalid booking session");
}

$booking = $_SESSION['temp_booking'];

/* VALIDATION */
if (empty($booking['ground_id']) || empty($booking['date']) || empty($booking['time'])) {
    die("Invalid booking data");
}

/* =========================
   PAYMENT PROCESS
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pay'])) {

    $method = trim($_POST['method'] ?? '');

    if (empty($method)) {

        echo "<script>alert('Please select payment method');</script>";

    } else {

        /* SAVE PAYMENT METHOD ONLY */
        $_SESSION['payment_method'] = $method;

        header("Location: confirm_payment.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#f5f5f5;
}

/* NAVBAR */
.navbar{
    background:#111827;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    font-size:20px;
    font-weight:bold;
}

.nav-links a{
    color:white;
    text-decoration:none;
    margin-left:20px;
    font-size:15px;
}

.nav-links a:hover{
    color:#00c3ff;
}

/* CONTAINER */
.container{
    width:600px;
    margin:40px auto;
}

/* CARD */
.card{
    background:white;
    border:1px solid #ddd;
    padding:25px;
}

/* INFO BOX */
.info{
    background:#fafafa;
    border:1px solid #ddd;
    padding:15px;
    margin-bottom:20px;
}

.info p{
    margin-bottom:10px;
}

/* FORM */
label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

select{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
}

/* BUTTON */
.btn{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    background:#007bff;
    color:white;
    cursor:pointer;
    font-size:15px;
}

.btn:hover{
    background:#0056b3;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">Indoor Game Booking</div>

    <div class="nav-links">
        <a href="user_home.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">

        <h2>Payment Information</h2>

        <div class="info">

            <p><strong>Ground:</strong> <?= htmlspecialchars($booking['ground_name'] ?? '') ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($booking['date']) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($booking['time']) ?></p>

        </div>

        <form method="POST">

            <label>Payment Method</label>

            <select name="method" required>
                <option value="">Select Method</option>
                <option value="bKash">bKash</option>
                <option value="Nagad">Nagad</option>
                <option value="Card">Card</option>
            </select>

            <button type="submit" name="pay" class="btn">
                Confirm Payment
            </button>

        </form>

    </div>

</div>

</body>
</html>