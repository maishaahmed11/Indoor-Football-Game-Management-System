<?php
session_start();

if (isset($_SESSION['email']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_home.php");
        exit();
    } else {
        header("Location: user_home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Indoor Football</title>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
    scroll-behavior:smooth;
}

body{
    background:#000;
    color:white;
}



.hero{
    width:100%;
    min-height:100vh;

    background:
    linear-gradient(rgba(0,0,0,0.65),rgba(0,0,0,0.65)),
    url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1974&auto=format&fit=crop')
    no-repeat center center/cover;
}



.nav{
    width:100%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 60px;
    position:fixed;
    top:0;
    left:0;
    z-index:999;
    background:rgba(0,0,0,0.4);
    backdrop-filter:blur(8px);
}

.logo{
    font-size:28px;
    font-weight:bold;
    color:#00c3ff;
}

.nav-links{
    display:flex;
    gap:25px;
}

.nav-links a{
    color:white;
    text-decoration:none;
    transition:0.3s;
}

.nav-links a:hover{
    color:#00c3ff;
}



.hero-content{
    min-height:100vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:20px;
}

.hero-content h1{
    font-size:65px;
    margin-bottom:20px;
}

.hero-content p{
    max-width:700px;
    line-height:1.8;
    color:#ddd;
    margin-bottom:30px;
}



.btn{
    display:inline-block;
    padding:14px 32px;
    border-radius:30px;
    background:#00c3ff;
    color:white;
    text-decoration:none;
    transition:0.3s;
}

.btn:hover{
    background:#0099cc;
}



section{
    padding:100px 10%;
    text-align:center;
}

section h2{
    font-size:40px;
    color:#00c3ff;
    margin-bottom:20px;
}

section p{
    color:#ccc;
    line-height:1.8;
}



.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
    margin-top:40px;
}

.card{
    background:#111;
    padding:30px;
    border-radius:12px;
    transition:0.3s;
    cursor:pointer;
}

.card:hover{
    transform:translateY(-8px);
    background:#1b1b1b;
}

.card h3{
    margin-bottom:12px;
}



#contact p{
    margin-top:10px;
}



.footer{
    background:#0a0a0a;
    padding:25px 10%;
    text-align:center;
    border-top:1px solid #222;
}

.footer p{
    color:#aaa;
    font-size:14px;
}



.container{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.7);

    display:flex;
    justify-content:center;
    align-items:center;

    opacity:0;
    visibility:hidden;

    transition:0.3s;
    z-index:9999;
}

.container.active{
    opacity:1;
    visibility:visible;
}

.form-box{
    width:400px;
    background:white;
    color:black;
    padding:30px;
    border-radius:12px;
    position:relative;
    animation:popup 0.3s ease;
}

@keyframes popup{
    from{
        transform:scale(0.8);
        opacity:0;
    }

    to{
        transform:scale(1);
        opacity:1;
    }
}

.form-box h2{
    text-align:center;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:none;
    background:#eee;
    border-radius:6px;
    outline:none;
}

.main-btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#00c3ff;
    color:white;
    cursor:pointer;
    font-size:15px;
}

.main-btn:hover{
    background:#0099cc;
}

.switch-btn{
    background:none;
    border:none;
    color:#00c3ff;
    margin-top:15px;
    cursor:pointer;
    width:100%;
}

/* FORM */

#login-form,
#register-form{
    display:none;
}

#login-form.active,
#register-form.active{
    display:block;
}



@media(max-width:768px){

    .nav{
        flex-direction:column;
        gap:15px;
        padding:20px;
    }

    .nav-links{
        flex-wrap:wrap;
        justify-content:center;
    }

    .hero-content h1{
        font-size:38px;
    }

    .hero-content p{
        font-size:15px;
    }

    .form-box{
        width:90%;
    }

    section h2{
        font-size:30px;
    }
}

</style>
</head>

<body>


<div class="hero">

    
    <div class="nav">

        <div class="logo">
            FOOTBALL .
        </div>

        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#features">Features</a>
            <a href="#services">Services</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>

    </div>

    
    <div class="hero-content" id="home">

        <h1>INDOOR FOOTBALL MANAGEMENT ⚽</h1>

        <p>
            Experience the ultimate indoor football environment.
            Book matches, manage scores, connect with players,
            and enjoy the best football experience.
        </p>

        <a href="#" class="btn" onclick="openRegister()">
            Get Started
        </a>

    </div>

</div>


<section id="about">

    <h2>About Us</h2>

    <p>
        The Indoor Football Management System helps users book indoor football grounds,
        join tournaments, manage matches and track live football activities easily.
    </p>

</section>

<section id="features">

    <h2>Why Choose Us</h2>

    <div class="cards">

        <div class="card">
            <h3>⚽ Premium Ground</h3>
            <p>High quality indoor football turf for best gameplay experience.</p>
        </div>

        <div class="card">
            <h3>📅 Easy Booking</h3>
            <p>Book football slots online in just a few clicks.</p>
        </div>

        <div class="card">
            <h3>🏆 Tournaments</h3>
            <p>Join exciting indoor football tournaments and competitions.</p>
        </div>

    </div>

</section>


<section id="services">

    <h2>Our Services</h2>

    <div class="cards">

        <div class="card" onclick="showLoginMessage()">
            <h3>⚽ Match Booking</h3>
            <p>Book football slots online anytime.</p>
        </div>

        <div class="card" onclick="showLoginMessage()">
            <h3>🏆 Tournament</h3>
            <p>Participate in exciting tournaments.</p>
        </div>

        <div class="card" onclick="showLoginMessage()">
            <h3>📊 Live Scores</h3>
            <p>Track match scores and rankings.</p>
        </div>

    </div>

</section>

<section id="contact">

    <h2>Contact Us</h2>

    <p>Email: team_error.official@gmail.com</p>
    <p>Phone: 017XXXXXXXX</p>
    <p>Sylhet, Bangladesh</p>

</section>

<div class="footer">

    <p>
        © 2026 Indoor Football Management System | All Rights Reserved
    </p>

</div>


<div class="container" id="formContainer">

    <div class="form-box">

        
        <form id="register-form"
              action="login_register.php"
              method="POST">
              <?php
if (isset($_SESSION['register_error'])) {
    echo "<p style='color:red;text-align:center;margin-bottom:10px;'>"
        . $_SESSION['register_error'] .
        "</p>";
    unset($_SESSION['register_error']);
}

if (isset($_SESSION['register_success'])) {
    echo "<p style='color:lightgreen;text-align:center;margin-bottom:10px;'>"
        . $_SESSION['register_success'] .
        "</p>";
    unset($_SESSION['register_success']);
}
?>

            <h2>Register</h2>

<input type="text"
       id="name"
       name="name"
       placeholder="Full Name"
       required>
<div id="nameError" style="color:red;font-size:13px;margin-bottom:10px;"></div>

<input type="email"
       id="email"
       name="email"
       placeholder="Email"
       required>
<div id="emailError" style="color:red;font-size:13px;margin-bottom:10px;"></div>

<input type="password"
       id="password"
       name="password"
       placeholder="Password"
       required>
<div id="passwordError" style="color:red;font-size:13px;margin-bottom:10px;"></div>

<input type="password"
       id="confirm_password"
       name="confirm_password"
       placeholder="Confirm Password"
       required>
<div id="confirmError" style="color:red;font-size:13px;margin-bottom:10px;"></div>

            <button type="submit"
                    name="register"
                    class="main-btn">

                Register

            </button>

            <button type="button"
                    class="switch-btn"
                    onclick="openLogin()">

                Already have an account? Login

            </button>

        </form>

        
        <form id="login-form"
              action="login_register.php"
              method="POST">
              <?php
if (isset($_SESSION['login_error'])) {
    echo "<p style='color:red;text-align:center;margin-bottom:10px;'>"
        . $_SESSION['login_error'] .
        "</p>";
    unset($_SESSION['login_error']);
}
?>

            <h2>Login</h2>

            <input type="email"
                   id="loginEmail"
                   name="email"
                   placeholder="Email"
                   required>
            <div id="loginEmailError"
                 style="color:red;font-size:13px;margin-bottom:10px;">
            </div>
            <input type="password"
                   id="loginPassword"
                   name="password"
                   placeholder="Password"
                   required>
            <div id="loginPasswordError"
                 style="color:red;font-size:13px;margin-bottom:10px;">
            </div>
            <div style="text-align:center; margin-bottom:15px;">

         <a href="forgot_password.php"
         style="color:#00c3ff; text-decoration:none;">

          Forgot Password?

    </a>

</div>

            <button type="submit"
                    name="login"
                    class="main-btn">

                Login

            </button>

            <button type="button"
                    class="switch-btn"
                    onclick="openRegister()">

                Don't have an account? Register

            </button>

        </form>

    </div>

</div>

<script>


function openLogin(){

    document.getElementById("formContainer").classList.add("active");
    document.getElementById("login-form").classList.add("active");
    document.getElementById("register-form").classList.remove("active");
}


function openRegister(){

    document.getElementById("formContainer").classList.add("active");
    document.getElementById("register-form").classList.add("active");
    document.getElementById("login-form").classList.remove("active");
}

function showLoginMessage(){

    alert("Please login first to access this feature.");
    openLogin();
}


function openForgot(){

    alert("Forgot Password feature coming soon.");
}


window.onclick = function(e){

    let container = document.getElementById("formContainer");

    if(e.target == container){

        container.classList.remove("active");
        document.getElementById("login-form").classList.remove("active");
        document.getElementById("register-form").classList.remove("active");
    }
};



const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password");

/* NAME */
if(nameInput){

nameInput.addEventListener("input", function(){

    let pattern = /^[A-Za-z ]{3,50}$/;

    if(!pattern.test(this.value)){
        document.getElementById("nameError").innerHTML =
        "Name must contain only letters and spaces.";
    }
    else{
        document.getElementById("nameError").innerHTML = "";
    }
});
}


if(emailInput){

emailInput.addEventListener("input", function(){

    let pattern =
    /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

    if(!pattern.test(this.value)){
        document.getElementById("emailError").innerHTML =
        "Invalid email format.";
    }
    else{
        document.getElementById("emailError").innerHTML = "";
    }
});
}


if(passwordInput){

passwordInput.addEventListener("input", function(){

    let pattern =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

    if(!pattern.test(this.value)){
        document.getElementById("passwordError").innerHTML =
        "Minimum 8 characters with uppercase, lowercase, number and special character.";
    }
    else{
        document.getElementById("passwordError").innerHTML = "";
    }
});
}


if(confirmInput){

confirmInput.addEventListener("input", function(){

    if(this.value !== passwordInput.value){
        document.getElementById("confirmError").innerHTML =
        "Passwords do not match.";
    }
    else{
        document.getElementById("confirmError").innerHTML = "";
    }
});
}



const loginEmail =
document.getElementById("loginEmail");

const loginPassword =
document.getElementById("loginPassword");

if(loginEmail){

loginEmail.addEventListener("input", function(){

    let pattern =
    /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

    if(!pattern.test(this.value)){
        document.getElementById("loginEmailError").innerHTML =
        "Invalid email format.";
    }
    else{
        document.getElementById("loginEmailError").innerHTML = "";
    }
});
}

if(loginPassword){

loginPassword.addEventListener("input", function(){

    if(this.value.length < 8){
        document.getElementById("loginPasswordError").innerHTML =
        "Password must be at least 8 characters.";
    }
    else{
        document.getElementById("loginPasswordError").innerHTML = "";
    }
});
}

</script>

</body>
</html>