<?php
class RateLimiter {
    private $maxAttempts;
    private $timeWindow;
    private $storageFile;

    public function __construct($maxAttempts = 5, $timeWindow = 300) { // 5 attempts per 5 minutes
        $this->maxAttempts = $maxAttempts;
        $this->timeWindow = $timeWindow;
        $this->storageFile = __DIR__ . '/../logs/rate_limit.json';
        
        // Ensure logs directory exists
        $logsDir = dirname($this->storageFile);
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
    }

    public function isAllowed($identifier) {
        $attempts = $this->getAttempts($identifier);
        $currentTime = time();
        
        // Clean old attempts
        $attempts = array_filter($attempts, function($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < $this->timeWindow;
        });
        
        return count($attempts) < $this->maxAttempts;
    }

    public function recordAttempt($identifier) {
        $attempts = $this->getAttempts($identifier);
        $attempts[] = time();
        
        // Clean old attempts
        $currentTime = time();
        $attempts = array_filter($attempts, function($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < $this->timeWindow;
        });
        
        $this->saveAttempts($identifier, $attempts);
    }

    public function getRemainingTime($identifier) {
        $attempts = $this->getAttempts($identifier);
        if (empty($attempts)) {
            return 0;
        }
        
        $oldestAttempt = min($attempts);
        $remainingTime = $this->timeWindow - (time() - $oldestAttempt);
        
        return max(0, $remainingTime);
    }

    private function getAttempts($identifier) {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        
        $data = json_decode(file_get_contents($this->storageFile), true);
        return $data[$identifier] ?? [];
    }

    private function saveAttempts($identifier, $attempts) {
        $data = [];
        if (file_exists($this->storageFile)) {
            $data = json_decode(file_get_contents($this->storageFile), true) ?? [];
        }
        
        $data[$identifier] = array_values($attempts);
        file_put_contents($this->storageFile, json_encode($data), LOCK_EX);
    }
}
?>
