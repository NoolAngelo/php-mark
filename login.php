<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .card-header-login {
            background-color: #712cf9;
            color: #ffffff;
        }

        .card-header-login h3 {
            margin: 0;
        }
    </style>
</head>

<body>
    <?php
    require_once 'includes/security.php';
    session_start();
    
    // Check if user is already logged in
    require_once 'includes/user.php';
    $user = new User();
    if ($user->isLoggedIn()) {
        header("Location: welcome.php");
        exit();
    }
    
    // Get error message if any
    $error_message = '';
    if (isset($_SESSION['login_error'])) {
        $error_message = $_SESSION['login_error'];
        unset($_SESSION['login_error']);
    }
    
    // Generate CSRF token
    $csrf_token = Security::generateCSRFToken();
    ?>
    
    <div class="container py-3">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <?php include 'header.php'; ?>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis" id="greeting">Login Page</h1>
                <p class="fs-5 text-body-secondary" id="time">Welcome! Please log in.</p>
            </div>
        </header>

        <main class="d-flex align-items-center justify-content-center min-vh-100">
            <div class="container py-3">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header card-header-login">
                                <h3>Login</h3>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($error_message)): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($error_message); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form id="loginForm" action="process_login.php" method="POST" novalidate>
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <div class="invalid-feedback">
                                            Please enter your password.
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-bd-primary btn-block">Login</button>
                                </form>
                                <div class="mt-3">
                                    <button onclick="goBack()" class="btn btn-secondary">Go Back</button>
                                    <a href="registration.php" class="btn btn-link">Don't have an account? Register</a>
                                </div>
                                <script>
                                    function goBack() {
                                        window.history.back();
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



        </main>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        <script>
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                var form = event.target;
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        </script>
        <script>
            function updateGreetingAndTime() {
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const seconds = now.getSeconds();
                const greetingElement = document.getElementById('greeting');
                const timeElement = document.getElementById('time');

                let greeting;
                if (hours >= 0 && hours < 12) {
                    greeting = 'Good Morning';
                } else if (hours >= 12 && hours < 18) {
                    greeting = 'Good Afternoon';
                } else {
                    greeting = 'Good Evening';
                }

                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                const monthName = months[now.getMonth()];
                const dateString = `${monthName} ${now.getDate()}, ${now.getFullYear()}`;
                const timeString = `${dateString} ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                greetingElement.textContent = greeting;
                timeElement.textContent = timeString;
            }

            updateGreetingAndTime();
            setInterval(updateGreetingAndTime, 1000);
        </script>
    </div>
    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>