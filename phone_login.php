<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Verification System - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>

        body {
            margin: 0;
            overflow: hidden;
            position: relative;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: 'Istok Web', sans-serif;
            background: url('img/Blue Backround Enhanced.png') no-repeat center center fixed;
            background-size: cover;
        }

        .logo {
            position: absolute;
            top: 27px;
            left: 39px;
            width: 88px;
            height: 84px;
            z-index: 2;
        }

        .cpc-text {
            position: absolute;
            top: 6%;
            left: 125px;
            transform: translateY(-50%);
            font-size: 15px;
            color: #f0f0f0;
            margin: 20px;
            z-index: 2;
            font-family: 'Istok Web', sans-serif;
        }

        .login-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .login-container img {
            width: auto;
            height: 100px;
            display: block;
            margin: 0 auto 40px;
            margin-bottom: 50px;
        }

        .input-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
            margin-left: 40px;
        }

        .input-container input {
            width: 220px;
            padding: 10px 0;
            padding-left: 40px;
            margin: 0;
            border: none;
            border-bottom: 2px solid #ddd;
            font-size: 16px;
            background: transparent;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .view-password {
            position: absolute;
            right: 10px;
            margin-left: 220px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

        .remember-me-container {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-left: 40px;
            margin-top: 10px;
            font-family: 'Istok Web', sans-serif;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #000522;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Istok Web', sans-serif;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }


        .remember-me-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px; 
            justify-content: flex-end; 
            margin-right: 120px;
        }

        .remember-me-container input[type="checkbox"] {
            width: 15px; 
            height: 20px; 
            margin-right: 10px; 
        }

        .remember-me-container label {
            font-size: 13px; 
            color: #000522; 
            font-family: 'Istok Web', sans-serif;
            cursor: pointer;
        }

        .remember-me-container label:hover {
            color: #0056b3; 
        }
    </style>

</head>

<body>

    <img src="img/CPC LOGO BACKROUND REMOVED.png" alt="logo" class="logo">
    <div class="cpc-text">COLEGIO DE LA PURISIMA CONCEPCION</div>

    <form class="login-container" onsubmit="checkLogin(event)">
        <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Login Form Image">
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fas fa-eye view-password" id="togglePassword"></i>
        </div>
        
        <div class="input-container remember-me-container">
            <input type="checkbox" id="rememberMe" name="rememberMe">
            <label for="rememberMe">Remember Me</label>
        </div>

        <button type="submit">LOGIN</button>
    </form>

    <script>

        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const username = document.getElementById('username');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        document.addEventListener("DOMContentLoaded", function () {
            const savedUsername = getCookie("username");
            const savedPassword = getCookie("password");
            
            if (savedUsername) {
                username.value = savedUsername;
                document.getElementById('rememberMe').checked = true; 
            }
            if (savedPassword) {
                password.value = savedPassword;
            }
        });
        
        function checkLogin(event) {
            event.preventDefault();
            
            const usernameValue = username.value;
            const passwordValue = password.value;
            const rememberMe = document.getElementById('rememberMe').checked;
            
            if (rememberMe) {
                setCookie("username", usernameValue, 7); 
                setCookie("password", passwordValue, 7);
            } else {
                deleteCookie("username"); 
                deleteCookie("password"); 
            }
            
            fetch('admin_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username: usernameValue, password: passwordValue }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        
        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
        }
        
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
        
        function deleteCookie(name) {
            document.cookie = name + '=; Max-Age=-99999999;';
        }

    </script>

</body>

</html>
