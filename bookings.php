<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT b.*, u.name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    ORDER BY b.id DESC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment History</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f6fb;
}

.header{
    background:#111827;
    color:white;
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h2{ margin:0; }

.back{
    background:#2563eb;
    color:white;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
}

.container{
    width:95%;
    max-width:1100px;
    margin:30px auto;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
}

th, td{
    padding:15px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#f1f5f9;
}

.badge{
    padding:6px 10px;
    border-radius:6px;
    color:white;
    font-size:13px;
}

.pending{ background:#f59e0b; }
.paid{ background:#10b981; }
.failed{ background:#ef4444; }

</style>
</head>

<body>

<div class="header">
    <h2>Payment History</h2>
    <a class="back" href="admin_home.php">Dashboard</a>
</div>

<div class="container">

<table>

<tr>
    <th>User</th>
    <th>Date</th>
    <th>Time</th>
    <th>Method</th>
    <th>Payment Status</th>
    <th>Booking Status</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['booking_date']) ?></td>
    <td><?= htmlspecialchars($row['booking_time']) ?></td>
    <td><?= htmlspecialchars($row['payment_method']) ?></td>

    

    
    <td>
        <span class="badge <?= strtolower($row['payment_status']) ?>">
            <?= htmlspecialchars($row['payment_status']) ?>
        </span>
    </td>

    
    <td>
        <?= htmlspecialchars($row['status']) ?>
    </td>
    
</tr>

<?php } ?>

</table>

</div>

</body>
</html>