<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Webpage ni Gar</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/pricing/">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

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

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="pricing.css" rel="stylesheet">
</head>

<body>

    <div class="container py-3">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <?php include 'header.php'; ?>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis" id="greeting">Login Page na to wag kang ano</h1>
                <p class="fs-5 text-body-secondary" id="time">GoodMorning bitches</p>
            </div>
        </header>

        <main class="d-flex align-items-center justify-content-center min-vh-10">
            <div class="row text-center justify-content-center w-100">
                <div class="col-12 col-md-6 col-lg-4">
                    <form id="loginForm" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" required>
                            <div id="usernameHelp" class="form-text">Login with your username.</div>
                            <div class="invalid-feedback">Please enter your username.</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" required>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </main>



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

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

<footer class="pt-4 my-md-5 pt-md-5 border-top">
    <?php include 'footer.php'; ?>
</footer>
</div>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript to update greeting and time
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
            greeting = 'Good Night';
        }

        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const monthName = months[now.getMonth()];
        const dateString = `${monthName} ${now.getDate()}, ${now.getFullYear()}`;
        const timeString = `${dateString} ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;



        greetingElement.textContent = greeting;
        timeElement.textContent = timeString;
    }

    // Initial call to set greeting and time
    updateGreetingAndTime();
    // Update greeting and time every second
    setInterval(updateGreetingAndTime, 1000);
</script>
</body>

</html>