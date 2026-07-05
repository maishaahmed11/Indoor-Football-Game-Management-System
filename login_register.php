<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {



    $namePattern = "/^[A-Za-z ]{3,50}$/";

    $emailPattern =
        "/^(?![0-9])[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";

    $passwordPattern =
        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    

    if (isset($_POST['register'])) {

        $name = trim($_POST['name']);
        $email = strtolower(trim($_POST['email']));
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $role = "user";

        
        if (!preg_match($namePattern, $name)) {
            $_SESSION['register_error'] = "Name must contain only letters and spaces.";
            header("Location: index.php");
            exit();
        }

        if (!preg_match($emailPattern, $email)) {
            $_SESSION['register_error'] = "Invalid email.";
            header("Location: index.php");
            exit();
        }

        if (!preg_match($passwordPattern, $password)) {
            $_SESSION['register_error'] = "Weak password.";
            header("Location: index.php");
            exit();
        }

        if ($password !== $confirm_password) {
            $_SESSION['register_error'] = "Passwords do not match.";
            header("Location: index.php");
            exit();
        }

     
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $_SESSION['register_error'] = "Email already exists.";
            header("Location: index.php");
            exit();
        }
        $checkStmt->close();

        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );

        $insertStmt->bind_param(
            "ssss",
            $name,
            $email,
            $hashedPassword,
            $role
        );

        if ($insertStmt->execute()) {

            $_SESSION['user_id'] = $insertStmt->insert_id;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            header("Location: user_home.php");
            exit();

        } else {
            $_SESSION['register_error'] = "Registration failed.";
            header("Location: index.php");
            exit();
        }
    }

 

    if (isset($_POST['login'])) {

        $email = strtolower(trim($_POST['email']));
        $password = trim($_POST['password']);

        if (!preg_match($emailPattern, $email)) {
            $_SESSION['login_error'] = "Invalid email format.";
            header("Location: index.php");
            exit();
        }

        $loginStmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?"
        );

        $loginStmt->bind_param("s", $email);
        $loginStmt->execute();
        $result = $loginStmt->get_result();

        if ($result && $result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                header(
                    $user['role'] === "admin"
                        ? "Location: admin_home.php"
                        : "Location: user_home.php"
                );
                exit();
            }
        }

        $_SESSION['login_error'] = "Incorrect email or password.";
        header("Location: index.php");
        exit();
    }

if (isset($_POST['forgot_password'])) {

    $forgotEmail =
    strtolower(trim($_POST['forgot_email']));

    $stmt = $conn->prepare(
        "SELECT id FROM users WHERE email=?"
    );

    $stmt->bind_param("s",$forgotEmail);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $token =
        bin2hex(random_bytes(32));

        $expiry =
        date(
            'Y-m-d H:i:s',
            strtotime('+1 hour')
        );

        $update =
        $conn->prepare(
        "UPDATE users
         SET reset_token=?,
             reset_token_expiry=?
         WHERE email=?"
        );

        $update->bind_param(
            "sss",
            $token,
            $expiry,
            $forgotEmail
        );

        $update->execute();

        header(
        "Location: reset_password.php?token="
        .$token
        );

        exit();

    }else{

        $_SESSION['forgot_error']
        = "Email not found";

        header(
        "Location: forgot_password.php"
        );

        exit();
    }
}
}
?>