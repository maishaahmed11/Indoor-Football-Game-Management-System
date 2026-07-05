<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$name  = $user['name'];
$email = $user['email'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    $update = "UPDATE users 
               SET name='$name', email='$email' 
               WHERE id='$user_id'";

    mysqli_query($conn, $update);

    
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    header("Location: profile.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:url('images/pexels-diego-santacruz-252431696-12616082.jpg') no-repeat center/cover;
    color:white;
}

.overlay{
    background:rgba(0,0,0,0.7);
    min-height:100vh;
}


.navbar{
    display:flex;
    justify-content:space-between;
    padding:20px 40px;
}

.nav-links a{
    color:white;
    margin-left:20px;
    text-decoration:none;
}


.container{
    text-align:center;
    margin-top:60px;
    padding:20px;
}


.card{
    margin:auto;
    width:320px;
    background:rgba(255,255,255,0.1);
    padding:25px;
    border-radius:10px;
    backdrop-filter: blur(10px);
}

.btn{
    margin-top:15px;
    background:#00c3ff;
    border:none;
    padding:10px;
    color:white;
    border-radius:5px;
    cursor:pointer;
    width:100%;
}

input{
    width:100%;
    padding:8px;
    margin:8px 0;
    border:none;
    border-radius:5px;
}


.edit-form{
    display:none;
    margin-top:15px;
}


@media(max-width:500px){
    .card{
        width:90%;
    }
}
</style>

<script>
function toggleEdit(){
    var form = document.getElementById("editForm");
    form.style.display = (form.style.display === "block") ? "none" : "block";
}
</script>

</head>

<body>

<div class="overlay">


<div class="navbar">
    <h2>Indoor Football</h2>

    <div class="nav-links">
        <a href="user_home.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>


<div class="container">

    <h1>Your Profile 👤</h1>

    <div class="card">

        <p><b>Name:</b> <?= htmlspecialchars($name); ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($email); ?></p>

        <button class="btn" onclick="toggleEdit()">Edit Profile</button>

        
        <form method="POST" class="edit-form" id="editForm">

            <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

            <button class="btn" type="submit">Save Changes</button>

        </form>

    </div>

</div>

</div>

</body>
</html>