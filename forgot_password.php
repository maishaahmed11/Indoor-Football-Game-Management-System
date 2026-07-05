<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;

            
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00e6085b;
            letter-spacing: 1px;
        }

        
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;

            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.3);

            outline: none;

            background: rgba(255,255,255,0.15);
            color: #fff;

            transition: 0.3s;
        }

        input[type="email"]::placeholder {
            color: rgba(255,255,255,0.7);
        }

        input[type="email"]:focus {
            border-color: #1be60058;
            box-shadow: 0 0 8px rgba(0, 230, 118, 0.4);
        }

        
        button {
            width: 100%;
            padding: 12px;

            border: none;
            border-radius: 10px;

            background: linear-gradient(135deg, #00e60f69, #00c80a71);
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

        .msg {
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        .error {
            color: #ff5252;
        }

        .success {
            color: #2300e600;
        }

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

    <h2>Forgot Password</h2>

    <?php
    if(isset($_SESSION['forgot_error'])){
        echo "<div class='msg error'>".$_SESSION['forgot_error']."</div>";
        unset($_SESSION['forgot_error']);
    }

    if(isset($_SESSION['forgot_success'])){
        echo "<div class='msg success'>".$_SESSION['forgot_success']."</div>";
        unset($_SESSION['forgot_success']);
    }
    ?>

    <form action="login_register.php" method="POST">

        <input type="email"
               name="forgot_email"
               placeholder="Enter your email"
               required>

        <button type="submit" name="forgot_password">
            Send Reset Link
        </button>

        <div class="note">
             A secure reset link will be sent to your email
        </div>

    </form>

</div>

</body>
</html>