<?php
require_once 'config/database.php';

class AuditLogger {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function logLogin($userId, $email, $ipAddress, $userAgent, $status) {
        try {
            $query = "INSERT INTO login_audit (user_id, email, ip_address, user_agent, login_status) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId, $email, $ipAddress, $userAgent, $status]);
        } catch (Exception $e) {
            error_log("Audit logging failed: " . $e->getMessage());
        }
    }
    
    public function getRecentFailedAttempts($email, $hours = 24) {
        try {
            $query = "SELECT COUNT(*) as count FROM login_audit 
                      WHERE email = ? AND login_status = 'failed' 
                      AND login_time > DATE_SUB(NOW(), INTERVAL ? HOUR)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email, $hours]);
            return $stmt->fetch()['count'];
        } catch (Exception $e) {
            error_log("Failed to get audit data: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getLoginHistory($userId, $limit = 10) {
        try {
            $query = "SELECT ip_address, user_agent, login_status, login_time 
                      FROM login_audit 
                      WHERE user_id = ? 
                      ORDER BY login_time DESC 
                      LIMIT ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Failed to get login history: " . $e->getMessage());
            return [];
        }
    }
}
?>
