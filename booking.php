<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['user_id'], $_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];
$name = $_SESSION['name'] ?? 'User';
$ground_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ground = null;
$selected_date = $_SESSION['selected_date'] ?? date('Y-m-d', strtotime('+1 day'));


if (isset($_POST['booking_date'])) {
    $selected_date = $_POST['booking_date'];
    $_SESSION['selected_date'] = $selected_date;
}

if ($ground_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM grounds WHERE id = ?");
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $ground = $res->fetch_assoc();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book'])) {

    $date = trim($_POST['booking_date'] ?? '');
    $time = trim($_POST['booking_time'] ?? '');

    if ($date <= date('Y-m-d')) {
    die("Today's booking is not allowed");
}

    if (!$ground_id || !$date || !$time) {
        die("❌ Missing booking data");
    }

    
    $check = $conn->prepare("
        SELECT id 
        FROM bookings 
        WHERE ground_id = ? 
        AND booking_date = ? 
        AND booking_time = ? 
        AND status = 'Booked'
    ");

    $check->bind_param("iss", $ground_id, $date, $time);
    $check->execute();
    $exists = $check->get_result();

$_SESSION['temp_booking'] = [
    'user_id' => $user_id,
    'ground_id' => $ground_id,
    'date' => $date,
    'time' => $time
];

header("Location: payment.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Football Booking</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:url('images/pexels-diego-santacruz-252431696-12616082.jpg') no-repeat center center fixed;
    background-size:cover;
}

body::before{
    content:"";
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,0.39);
    z-index:-1;
}


.navbar{
    background:#111827;
    color:white;
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    font-weight:bold;
    font-size:18px;
}

.nav-links a{
    color:#ddd;
    margin-left:15px;
    text-decoration:none;
    font-size:14px;
}

.nav-links a:hover{
    color:white;
}


.container{
    max-width:1000px;
    margin:40px auto;
    padding:20px;
}


.card{
    display:flex;
    gap:25px;
    background:rgba(255,255,255,0.59);
    border-radius:12px;
    padding:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.15);
}

.left{
    flex:1;
}


.right{
    flex:1;
    border-left:1px solid #eee;
    padding-left:20px;
}

img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:10px;
}

.notice{
    font-size:14px;
    color:#555;
    margin-top:10px;
    line-height:1.6;
}

input{
    width:100%;
    padding:10px;
    margin-bottom:12px;
    border:1px solid #ddd;
    border-radius:6px;
}

.btn{
    width:80%;
    margin:25px auto 0 auto;
    display:block;
    background:#1f2937;
    border:none;
    padding:12px;
    color:white;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#111827;
    transform:translateY(-2px);
}

.slots{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-top:15px;
}

.slot{
    padding:12px;
    border-radius:8px;
    text-align:center;
    font-size:14px;
}

.available{
    background:green;
    color:white;
    cursor:pointer;
}

.available input{
    display:none;
}

.available:hover{
    transform:scale(1.03);
}

.available input:checked + span{
    font-weight:bold;
}

.reserved{
    background:#b91c1c;;
    color:white;
    cursor:not-allowed;
}

.reserved span{
    display:block;
    margin-top:5px;
    font-size:12px;
}
</style>

</head>

<body>

<div class="navbar">
    <div class="logo">Football Slot Booking</div>
    <div class="nav-links">
        <a href="user_home.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

<h2>Book Your Slot</h2>

<?php if($ground){ ?>

<div class="card">

    
    <div class="left">
        <img src="uploads/<?= htmlspecialchars($ground['image']); ?>">

        <h3><?= htmlspecialchars($ground['ground_name']); ?></h3>
        <p>📍 <?= htmlspecialchars($ground['location']); ?></p>
        <p>💰 ৳<?= htmlspecialchars($ground['price']); ?></p>
        <p>🕒 <?= htmlspecialchars($ground['available_time']); ?></p>
    </div>

    
    <div class="right">

        <div class="notice">
            🕙 10 AM - 10 PM<br>
            ⏱ 1 Hour Slot<br>
            🍽 Break: 12 - 1 PM
        </div>

<?php
$slots = [
    "10:00 AM - 11:00 AM",
    "11:00 AM - 12:00 PM",
    "1:00 PM - 2:00 PM",
    "2:00 PM - 3:00 PM",
    "3:00 PM - 4:00 PM",
    "4:00 PM - 5:00 PM",
    "5:00 PM - 6:00 PM",
    "6:00 PM - 7:00 PM",
    "7:00 PM - 8:00 PM",
    "8:00 PM - 9:00 PM",
    "9:00 PM - 10:00 PM"
];
?>

<form method="POST">

<label>Date</label>
<input type="date" name="booking_date"
       value="<?= htmlspecialchars($selected_date); ?>"
       min="<?= date('Y-m-d', strtotime('+1 day')); ?>"
       onchange="this.form.submit()"
       required>

<label>Time</label>

<div class="slots">

<?php foreach($slots as $slot){ ?>

<?php
$checkSlot = $conn->prepare("
    SELECT id
    FROM bookings
    WHERE ground_id = ?
    AND booking_date = ?
    AND booking_time = ?
    AND status = 'Booked'
");

$checkSlot->bind_param("iss", $ground_id, $selected_date, $slot);
$checkSlot->execute();
$slotResult = $checkSlot->get_result();

?>

<?php if($slotResult->num_rows > 0){ ?>

<div class="slot reserved">
    <?= htmlspecialchars($slot) ?>
    <span>Reserved</span>
</div>

<?php } else { ?>

<label class="slot available">
    <input type="radio" name="booking_time" value="<?= htmlspecialchars($slot) ?>" required>
    <span><?= htmlspecialchars($slot) ?></span>
</label>

<?php } ?>

<?php } ?>

</div>

<button class="btn" type="submit" name="book">
    Confirm Booking
</button>

</form>

    </div>

</div>

<?php } else { ?>
<p>❌ No ground selected</p>
<?php } ?>

</div>

</body>
</html>