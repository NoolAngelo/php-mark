<?php
require_once 'config/database.php';
require_once 'includes/security.php';

class User {
    private $conn;
    private $table_name = "members";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        try {
            // Validate input
            if (!Security::validateEmail($email)) {
                throw new Exception("Invalid email format");
            }

            if (empty($password)) {
                throw new Exception("Password is required");
            }

            // Prepare statement to prevent SQL injection
            $query = "SELECT id, email, password, name FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email]);

            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                
                // Verify password
                if (Security::verifyPassword($password, $row['password'])) {
                    // Start session securely
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    Security::regenerateSession();
                    
                    // Store user info in session
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['login_time'] = time();
                    
                    return true;
                } else {
                    throw new Exception("Invalid credentials");
                }
            } else {
                throw new Exception("Invalid credentials");
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            throw $e;
        }
    }

    public function register($name, $email, $password) {
        try {
            // Validate input
            if (empty($name)) {
                throw new Exception("Name is required");
            }

            if (!Security::validateEmail($email)) {
                throw new Exception("Invalid email format");
            }

            if (!Security::validatePassword($password)) {
                throw new Exception("Password must be at least 8 characters with uppercase, lowercase, and number");
            }

            // Check if email already exists
            $query = "SELECT id FROM " . $this->table_name . " WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                throw new Exception("Email already exists");
            }

            // Hash password
            $hashed_password = Security::hashPassword($password);

            // Insert new user
            $query = "INSERT INTO " . $this->table_name . " (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($query);
            
            if ($stmt->execute([$name, $email, $hashed_password])) {
                return true;
            } else {
                throw new Exception("Registration failed");
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            throw $e;
        }
    }

    public function logout() {
        Security::destroySession();
    }

    public function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if session exists and is valid
        if (isset($_SESSION['user_id']) && isset($_SESSION['login_time'])) {
            $session_lifetime = $_ENV['SESSION_LIFETIME'] ?? 3600; // 1 hour default
            
            if ((time() - $_SESSION['login_time']) < $session_lifetime) {
                return true;
            } else {
                // Session expired
                $this->logout();
                return false;
            }
        }
        
        return false;
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['email'],
                'name' => $_SESSION['name']
            ];
        }
        return null;
    }

    public function updateProfile($name) {
        try {
            if (!$this->isLoggedIn()) {
                throw new Exception("Not logged in");
            }

            $query = "UPDATE " . $this->table_name . " SET name = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            
            if ($stmt->execute([$name, $_SESSION['user_id']])) {
                $_SESSION['name'] = $name; // Update session
                return true;
            } else {
                throw new Exception("Profile update failed");
            }
        } catch (Exception $e) {
            error_log("Profile update error: " . $e->getMessage());
            throw $e;
        }
    }

    public function changePassword($currentPassword, $newPassword) {
        try {
            if (!$this->isLoggedIn()) {
                throw new Exception("Not logged in");
            }

            // Get current password hash
            $query = "SELECT password FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$_SESSION['user_id']]);
            
            if ($stmt->rowCount() != 1) {
                throw new Exception("User not found");
            }
            
            $row = $stmt->fetch();
            
            // Verify current password
            if (!Security::verifyPassword($currentPassword, $row['password'])) {
                throw new Exception("Current password is incorrect");
            }

            // Update with new password
            $hashedPassword = Security::hashPassword($newPassword);
            $updateQuery = "UPDATE " . $this->table_name . " SET password = ?, updated_at = NOW() WHERE id = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            
            if ($updateStmt->execute([$hashedPassword, $_SESSION['user_id']])) {
                return true;
            } else {
                throw new Exception("Password change failed");
            }
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            throw $e;
        }
    }
}
?>
