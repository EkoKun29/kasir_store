<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            text-align: center;
        }
        .login-logo {
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-logo h2 {
            color: #4CAF50;
            margin-top: 10px;
            font-size: 24px;
        }
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #4CAF50;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #45a049;
        }
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-10px);}
            60% {transform: translateY(-5px);}
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <i class="fas fa-store" style="color:#4CAF50; font-size: 60px; animation: bounce 2s infinite;"></i>
            <h2>Kasir Store</h2>
        </div>

        @if(session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword()">
                    <i id="passwordToggleIcon" class="fas fa-eye-slash"></i>
                </span>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggleIcon.classList.remove('fa-eye-slash');
                passwordToggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                passwordToggleIcon.classList.remove('fa-eye');
                passwordToggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>