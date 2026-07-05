<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}


$id = (int) $_GET['id'];


$result = $conn->query("SELECT * FROM grounds WHERE id=$id");
$ground = $result->fetch_assoc();

if (!$ground) {
    die("Ground not found!");
}


if (isset($_POST['update_ground'])) {

    $name = trim($_POST['ground_name']);
    $location = trim($_POST['location']);
    $price = (int) $_POST['price'];
    $time = trim($_POST['available_time']);

    
    if (!empty($_FILES['image']['name'])) {

        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

        $stmt = $conn->prepare("
            UPDATE grounds 
            SET ground_name=?, location=?, price=?, available_time=?, image=?
            WHERE id=?
        ");
        $stmt->bind_param("ssissi", $name, $location, $price, $time, $image, $id);

    } else {

        $stmt = $conn->prepare("
            UPDATE grounds 
            SET ground_name=?, location=?, price=?, available_time=?
            WHERE id=?
        ");
        $stmt->bind_param("ssisi", $name, $location, $price, $time, $id);
    }

    $stmt->execute();

    header("Location: manage_grounds.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Ground</title>
</head>
<body>

<h2>Edit Ground</h2>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="ground_name" value="<?= $ground['ground_name']; ?>" required><br><br>

    <input type="text" name="location" value="<?= $ground['location']; ?>" required><br><br>

    <input type="number" name="price" value="<?= $ground['price']; ?>" required><br><br>

    <input type="text" name="available_time" value="<?= $ground['available_time']; ?>" required><br><br>

    <p>Current Image:</p>
    <img src="uploads/<?= $ground['image']; ?>" width="120"><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit" name="update_ground">Update</button>

</form>

</body>
</html>