<?php
session_start();
require_once 'config.php';

/* AUTH CHECK */
if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'user') {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'] ?? 'User';

/* GET GROUNDS */
$sql = "SELECT * FROM grounds ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, sans-serif;
}

body{
    min-height:100vh;
    background-image:url('images/ground_1779829147.avif');
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    color:#111;
}

/* NAVBAR */

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

.navbar a{
    color:#ddd;
    margin-left:15px;
    text-decoration:none;
    font-size:14px;
}

.navbar a:hover{
    color:white;
}

/* MAIN */

.container{
    max-width:1000px;
    margin:40px auto;
    padding:0 20px;
}

/* WELCOME */

.welcome{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(6px);
    -webkit-backdrop-filter:blur(6px);

    padding:25px;
    border-radius:10px;
    margin-bottom:25px;

    border:1px solid rgba(255,255,255,0.2);
    color:white;
}

.welcome h2{
    margin-bottom:10px;
}

.welcome p{
    color:#f1f1f1;
    line-height:1.6;
}

/* CARDS */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(7px);
    -webkit-backdrop-filter:blur(7px);

    padding:20px;
    border-radius:10px;

    text-align:center;
    text-decoration:none;
    color:white;

    border:1px solid rgba(255,255,255,0.2);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card img{
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius:8px;
    margin-bottom:10px;
}

.card h3{
    margin-bottom:10px;
}

/* FOOTER */

footer{
    text-align:center;
    margin-top:40px;
    padding:15px;
    color:white;
}

</style>
</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        Indoor Football
    </div>

    <div>
        <a href="user_home.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

</div>

<!-- MAIN -->

<div class="container">

    <div class="welcome">

        <h2>Welcome, <?= htmlspecialchars($name); ?></h2>

        <p>Book your football slot easily and enjoy your game.</p>

        <p style="margin-top:10px;">
            Ground Open: <b>10:00 AM - 10:00 PM</b><br>
            Each Slot: <b>1 Hour</b><br>
            Break Time: <b>12 PM - 1 PM</b>
        </p>

    </div>

    <h3 style="color:white; margin-bottom:15px;">
        Available Grounds
    </h3>

    <div class="cards">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <a href="booking.php?id=<?= $row['id']; ?>" class="card">

                <img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Ground">

                <h3><?= htmlspecialchars($row['ground_name']); ?></h3>

                <p><?= htmlspecialchars($row['location']); ?></p>

                <p>
                    ৳<?= htmlspecialchars($row['price']); ?>
                    |
                    <?= htmlspecialchars($row['available_time']); ?>
                </p>

            </a>

        <?php } ?>

    </div>

</div>

<footer>
    &copy; <?= date('Y'); ?> Indoor Football Management System
</footer>

</body>
</html>
