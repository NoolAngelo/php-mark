<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Secure Registration Form">
  <title>Registration - Secure Portal</title>
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

    .card-header-register {
        background-color: #712cf9;
        color: #ffffff;
    }

    .card-header-register h3 {
        margin: 0;
    }

    .password-requirements {
        font-size: 0.875rem;
        color: #6c757d;
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
    
    // Get error or success message if any
    $error_message = '';
    $success_message = '';
    
    if (isset($_SESSION['registration_error'])) {
        $error_message = $_SESSION['registration_error'];
        unset($_SESSION['registration_error']);
    }
    
    if (isset($_SESSION['registration_success'])) {
        $success_message = $_SESSION['registration_success'];
        unset($_SESSION['registration_success']);
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
        <h1 class="display-4 fw-normal text-body-emphasis">Registration</h1>
        <p class="fs-5 text-body-secondary">Create your secure account</p>
      </div>
    </header>

    <main class="d-flex align-items-center justify-content-center">
      <div class="container py-3">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header card-header-register">
                <h3>Create Account</h3>
              </div>
              <div class="card-body">
                <?php if (!empty($error_message)): ?>
                  <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                  </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                  <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                  </div>
                <?php endif; ?>
                
                <form id="registrationForm" action="process_registration.php" method="POST" novalidate>
                  <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                  
                  <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">
                      Please enter your full name.
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                      Please enter a valid email address.
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="password-requirements">
                      Password must be at least 8 characters with uppercase, lowercase, and number.
                    </div>
                    <div class="invalid-feedback">
                      Please enter a valid password.
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <div class="invalid-feedback">
                      Passwords do not match.
                    </div>
                  </div>
                  
                  <button type="submit" class="btn btn-bd-primary btn-block w-100">Register</button>
                </form>
                
                <div class="mt-3 text-center">
                  <a href="login.php" class="btn btn-link">Already have an account? Login</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer class="pt-4 my-md-5 pt-md-5 border-top">
      <?php include 'footer.php'; ?>
    </footer>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
      var form = event.target;
      var password = document.getElementById('password').value;
      var confirmPassword = document.getElementById('confirm_password').value;
      
      // Password validation
      var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/;
      if (!passwordRegex.test(password)) {
        document.getElementById('password').setCustomValidity('Password must be at least 8 characters with uppercase, lowercase, and number');
      } else {
        document.getElementById('password').setCustomValidity('');
      }
      
      // Confirm password validation
      if (password !== confirmPassword) {
        document.getElementById('confirm_password').setCustomValidity('Passwords do not match');
      } else {
        document.getElementById('confirm_password').setCustomValidity('');
      }
      
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      }
      
      form.classList.add('was-validated');
    });
    
    // Real-time password matching
    document.getElementById('confirm_password').addEventListener('input', function() {
      var password = document.getElementById('password').value;
      var confirmPassword = this.value;
      
      if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
      } else {
        this.setCustomValidity('');
      }
    });
  </script>
</body>
</html>
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
        <h1 class="display-4 fw-normal text-body-emphasis">Register kana Gar!</h1>
        <p class="fs-5 text-body-secondary">Home page ito ni Gar wag kang ano par.</p>
      </div>
    </header>
    <main>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card mt-4">
              <div class="card-header bg-primary text-white">
                <h2>Register</h2>
              </div>
              <div class="card-body">
                <form method="post" action="register.php">
                  <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" required>
                  </div>
                  <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                      <option value="m">Male</option>
                      <option value="f">Female</option>
                      <option value="o">Other</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                  </div>
                  <br>
                  <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </main>
    <footer class="pt-4 my-md-5 pt-md-5 border-top">
      <?php include 'footer.php'; ?>
    </footer>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>