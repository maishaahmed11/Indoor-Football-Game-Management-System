<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}


$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>

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

.header a{
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
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

th, td{
    padding:15px;
    text-align:left;
    border-bottom:1px solid #eee;
}

th{
    background:#f1f5f9;
    color:#111827;
}


.badge{
    padding:6px 10px;
    border-radius:6px;
    font-size:13px;
    color:white;
}

.admin{ background:#10b981; }
.user{ background:#3b82f6; }


.delete{
    background:#ef4444;
    color:white;
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
}


@media(max-width:768px){

    table, th, td{
        font-size:13px;
    }

    th, td{
        padding:10px;
    }

    .header{
        flex-direction:column;
        gap:10px;
        text-align:center;
    }
}

</style>
</head>

<body>


<div class="header">

    <h2>👥 Manage Users</h2>

    <a href="admin_home.php">Dashboard</a>

</div>


<div class="container">

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>

    <td>
        <?php if($row['role'] == 'admin'){ ?>
            <span class="badge admin">Admin</span>
        <?php } else { ?>
            <span class="badge user">User</span>
        <?php } ?>
    </td>

    <td>
        <a class="delete"
        href="delete_user.php?id=<?= $row['id'] ?>"
        onclick="return confirm('Delete this user?')">
            Delete
        </a>
    </td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>