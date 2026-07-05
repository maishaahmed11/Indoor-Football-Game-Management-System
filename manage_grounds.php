<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}


if (isset($_POST['add_ground'])) {

    $ground_name = trim($_POST['ground_name']);
    $location = trim($_POST['location']);
    $price = (int) $_POST['price'];
    $available_time = trim($_POST['available_time']);

    
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $_SESSION['error'] = "Image upload failed!";
        header("Location: manage_grounds.php");
        exit();
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image = "ground_" . time() . "." . $ext;

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

    $stmt = $conn->prepare("
        INSERT INTO grounds (ground_name, location, price, available_time, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssiss", $ground_name, $location, $price, $available_time, $image);
    $stmt->execute();

    $_SESSION['success'] = "Ground added successfully!";
    header("Location: manage_grounds.php");
    exit();
}



if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM grounds WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['success'] = "Ground deleted!";
    header("Location: manage_grounds.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Grounds</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}


.header{
    background:#111827;
    color:white;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}


.container{
    width:90%;
    margin:20px auto;
}


.form-box{
    background:white;
    padding:20px;
    border-radius:8px;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #ddd;
    border-radius:6px;
}

.btn{
    background:#2563eb;
    color:white;
    padding:10px 15px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.grounds{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.card{
    background:white;
    border-radius:8px;
    overflow:hidden;
}

.card img{
    width:100%;
    height:300px;
    object-fit:cover;
    display:block;
}
.card-content{
    padding:12px;
}

.actions a{
    display:inline-block;
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    color:white;
    font-size:13px;
}

.edit{ background:#10b981; }
.delete{ background:#ef4444; }
</style>
</head>

<body>

<div class="header">
    <h3>Manage Grounds</h3>
    <a href="admin_home.php" style="color:white;text-decoration:none;">Dashboard</a>
</div>


<div class="container">


<?php if(isset($_SESSION['success'])): ?>
    <p style="color:green;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </p>
<?php endif; ?>


<?php if(isset($_SESSION['error'])): ?>
    <p style="color:red;">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </p>
<?php endif; ?>


<div class="form-box">
    <h3>Add Ground</h3>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="ground_name" placeholder="Ground Name" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="text" name="available_time" placeholder="Time" required>
        <input type="file" name="image" required>

        <button class="btn" name="add_ground">Add Ground</button>
    </form>
</div>


<div class="grounds">

<?php
$result = $conn->query("SELECT * FROM grounds ORDER BY id DESC");

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>

<div class="card">
    <img src="uploads/<?= htmlspecialchars($row['image']); ?>">

    <div class="card-content">
        <h4><?= htmlspecialchars($row['ground_name']); ?></h4>
        <p><?= htmlspecialchars($row['location']); ?></p>
        <p>৳<?= $row['price']; ?></p>
        <p><?= $row['available_time']; ?></p>

        <div class="actions">
            <a class="edit" href="edit_ground.php?id=<?= $row['id']; ?>">Edit</a>
            <a class="delete" href="?delete=<?= $row['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </div>
    </div>
</div>

<?php endwhile; else: ?>
    <p>No grounds found.</p>
<?php endif; ?>

</div>
</div>

</body>
</html>