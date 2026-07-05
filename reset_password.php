<?php

session_start();
require_once 'config.php';

if (!isset($_GET['token'])) {
    die("Invalid Request");
}

$token = trim($_GET['token']);

/* ================= CHECK TOKEN ================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("
        <h3 style='color:#ff4d4d;text-align:center;'>Token Not Found</h3>
        <p style='color:#aaa;text-align:center;'>Invalid or expired link</p>
    ");
}

$user = $result->fetch_assoc();

/* ================= RESET PASSWORD ================= */
if (isset($_POST['reset_password'])) {

    $newPassword = trim($_POST['password']);

    if (strlen($newPassword) < 8) {
        $_SESSION['reset_error'] = "Password must be at least 8 characters.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    $update = $conn->prepare("
        UPDATE users 
        SET password = ?, reset_token = NULL, reset_token_expiry = NULL 
        WHERE id = ?
    ");

    $update->bind_param("si", $hash, $user['id']);

    if ($update->execute()) {

        /* ✅ success message for login page */
        $_SESSION['reset_success'] = "Password updated successfully. Please login.";

        header("Location: index.php");
        exit();

    } else {

        $_SESSION['reset_error'] = "Failed to update password.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

    <style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;

    /* FOOTBALL BACKGROUND */
    background:
        linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
        url('images/pexels-franco-monsalvo-252430633-32285155.jpg')
        no-repeat center center/cover;

    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #fff;
}

/* GLASS CONTAINER */
.container {
    width: 360px;
    padding: 30px;
    border-radius: 14px;

    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);

    border: 1px solid rgba(255, 255, 255, 0.2);

    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* TITLE */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #00e60c65;
    letter-spacing: 1px;
}

/* INPUT (PASSWORD FIELD) */
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;

    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.3);

    outline: none;

    background: rgba(0,0,0,0.35);
    color: #fff;

    transition: 0.3s;
}

input[type="password"]::placeholder {
    color: rgba(255,255,255,0.7);
}

input[type="password"]:focus {
    border-color: #08e60070;
    box-shadow: 0 0 8px rgba(0, 230, 118, 0.4);
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;

    border: none;
    border-radius: 10px;

    background: linear-gradient(135deg, #3ae60063, #00c81b6d);
    color: #000;

    font-size: 15px;
    font-weight: bold;

    cursor: pointer;
    margin-top: 10px;

    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 230, 118, 0.3);
}

/* MESSAGE */
.msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 14px;
}

.error {
    color: #ff5252;
}

.success {
    color: #00e676;
}

/* NOTE */
.note {
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    text-align: center;
    margin-top: 10px;
    line-height: 1.5;
}
</style>
</head>

<body>

<div class="container">

    <h2>Reset Password</h2>

    <?php
    if (isset($_SESSION['reset_error'])) {
        echo "<div class='msg error'>".$_SESSION['reset_error']."</div>";
        unset($_SESSION['reset_error']);
    }
    ?>

    <form method="POST">
        <input 
            type="password" 
            name="password" 
            placeholder="Enter new password"
            required
        >

        <button type="submit" name="reset_password">
            Update Password
        </button>

        <div class="note">
            Minimum 8 characters required
        </div>
    </form>

</div>

</body>
</html>