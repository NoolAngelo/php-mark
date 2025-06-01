# 🔐 Secure PHP Web Application

A modern, security-focused PHP web application demonstrating enterprise-level security practices and best practices for web development.

![Security Status](https://img.shields.io/badge/Security-Enterprise%20Grade-green)
![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)
![Database](https://img.shields.io/badge/Database-MySQL%2FMariaDB-orange)
![License](https://img.shields.io/badge/License-MIT-yellow)

## 🛡️ Security Features

### ✅ **SQL Injection Prevention**

- **PDO Prepared Statements** - All database queries use parameterized statements
- **Input Sanitization** - Multi-layer input validation and sanitization
- **Database Abstraction** - Secure database connection class with error handling

### ✅ **Authentication & Authorization**

- **Bcrypt Password Hashing** - Industry-standard password encryption
- **Session Security** - HTTPOnly, Secure, SameSite cookie configuration
- **Role-Based Access Control** - Admin and user permission levels
- **Session Timeout** - Configurable session expiration

### ✅ **Attack Prevention**

- **CSRF Protection** - Token-based cross-site request forgery prevention
- **Rate Limiting** - Brute force attack prevention (5 attempts per 5 minutes)
- **XSS Protection** - Input sanitization and Content Security Policy headers
- **Clickjacking Protection** - X-Frame-Options headers

### ✅ **Security Headers**

- **Content Security Policy (CSP)** - Prevents code injection attacks
- **HTTP Security Headers** - X-XSS-Protection, X-Content-Type-Options
- **Cache Control** - Prevents sensitive data caching
- **Referrer Policy** - Controls referrer information leakage

### ✅ **Monitoring & Logging**

- **Audit Trail** - Login attempt tracking with IP addresses
- **Error Logging** - Comprehensive error logging system
- **Security Event Monitoring** - Failed login attempts and suspicious activity tracking

## 🏗️ Architecture

### **MVC-Inspired Structure**

```
├── config/
│   └── database.php          # Database connection class
├── includes/
│   ├── security.php          # Security utilities & CSRF protection
│   ├── security_headers.php  # HTTP security headers
│   ├── user.php             # User authentication & management
│   ├── rate_limiter.php     # Brute force protection
│   └── audit_logger.php     # Security event logging
├── css/
│   ├── bootstrap.min.css    # Bootstrap framework
│   └── datatables.css       # DataTables styling
├── js/
│   ├── bootstrap.bundle.min.js
│   ├── jquery.min.js
│   └── dataTables.js
├── img/
│   └── pic edited 400x400.png
├── logs/                    # Application & security logs
├── .env                     # Environment variables
├── .htaccess               # Apache security configuration
└── *.php                   # Application pages
```

### **Core Components**

#### 🔐 **Security Layer**

- `Security` class - CSRF tokens, password hashing, input validation
- `RateLimiter` class - Configurable rate limiting with file-based storage
- `AuditLogger` class - Security event tracking and logging

#### 👤 **User Management**

- `User` class - Authentication, registration, profile management
- `Database` class - Secure PDO connection with environment variables
- Role-based access control with admin privileges

#### 🌐 **Web Interface**

- Responsive Bootstrap 5 design
- Dynamic navigation based on authentication status
- CSRF-protected forms with client-side validation
- DataTables integration for enhanced user experience

## 🚀 Quick Start

### **Prerequisites**

- PHP 8.0+ with PDO extension
- MySQL/MariaDB 5.7+
- Apache/Nginx web server
- Composer (optional, for dependencies)

### **Installation**

1. **Clone the repository**

   ```bash
   git clone https://github.com/noolangelo/secure-php-app.git
   cd secure-php-app
   ```

2. **Configure environment**

   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Setup database**

   ```bash
   mysql -u root -p < database_update.sql
   ```

4. **Set permissions**

   ```bash
   chmod 755 logs/
   chmod 644 .env
   ```

5. **Access the application**
   ```
   http://localhost/your-project-name
   ```

### **Test Accounts**

```
👤 Regular User:
Email: test@example.com
Password: Password123

👨‍💼 Administrator:
Email: admin@example.com
Password: Admin123
```

## 📱 Features

### **User Authentication**

- ✅ Secure registration with password validation
- ✅ Login with rate limiting and audit logging
- ✅ Password change functionality
- ✅ Session management with configurable timeout

### **User Management**

- ✅ User dashboard with security status
- ✅ Profile management and updates
- ✅ Member directory with search functionality
- ✅ Admin panel for user oversight

### **Security Monitoring**

- ✅ Login attempt tracking
- ✅ Security status dashboard
- ✅ Rate limiting status display
- ✅ Audit trail viewing (admin only)

### **Admin Features**

- ✅ User management interface
- ✅ Security monitoring dashboard
- ✅ Login audit trail
- ✅ System status overview

## 🔧 Configuration

### **Environment Variables (.env)**

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=reservation
DB_USER=root
DB_PASS=your_password

# Security Settings
SESSION_LIFETIME=3600
RATE_LIMIT_ATTEMPTS=5
RATE_LIMIT_WINDOW=300
```

### **Security Settings**

- **Rate Limiting**: 5 attempts per 5 minutes (configurable)
- **Session Timeout**: 1 hour (configurable)
- **Password Requirements**: 8+ characters, uppercase, lowercase, number
- **CSRF Token Lifetime**: Session-based

### **Apache Configuration (.htaccess)**

```apache
# Environment protection
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Security headers
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
```

## 🧪 Security Testing

### **Implemented Protections**

- ✅ **SQL Injection** - All queries use prepared statements
- ✅ **XSS (Cross-Site Scripting)** - Input sanitization + CSP headers
- ✅ **CSRF** - Token validation on all forms
- ✅ **Session Hijacking** - Secure session configuration
- ✅ **Brute Force** - Rate limiting with IP tracking
- ✅ **Information Disclosure** - Error handling and logging
- ✅ **Clickjacking** - X-Frame-Options headers

### **Security Checklist**

- [x] Input validation and sanitization
- [x] Parameterized database queries
- [x] Secure password hashing (bcrypt)
- [x] CSRF token implementation
- [x] Rate limiting for login attempts
- [x] Secure session configuration
- [x] HTTP security headers
- [x] Error handling and logging
- [x] Environment variable protection

## 📊 Database Schema

### **Enhanced Members Table**

```sql
CREATE TABLE members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);
```

### **Audit Logging**

```sql
CREATE TABLE login_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255),
    ip_address VARCHAR(45),
    success BOOLEAN,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_time (email, attempted_at),
    INDEX idx_ip_time (ip_address, attempted_at)
);
```

## 🛠️ Development

### **Adding New Features**

1. Follow the existing security patterns
2. Use the Security class for input validation
3. Implement CSRF protection for forms
4. Add appropriate logging for security events
5. Update tests and documentation

### **Security Best Practices**

- Always use prepared statements for database queries
- Validate and sanitize all user input
- Implement proper error handling
- Use HTTPS in production
- Regular security updates
- Monitor logs for suspicious activity

## 🚀 Production Deployment

### **Security Checklist**

- [ ] Enable HTTPS with valid SSL certificate
- [ ] Update default passwords for test accounts
- [ ] Set `display_errors = 0` in PHP configuration
- [ ] Configure proper file permissions (644 for files, 755 for directories)
- [ ] Enable web server security modules (mod_security)
- [ ] Set up regular database backups
- [ ] Monitor application logs
- [ ] Implement log rotation

### **Performance Optimization**

- [ ] Enable PHP OpCache
- [ ] Configure database query optimization
- [ ] Implement CDN for static assets
- [ ] Enable GZIP compression
- [ ] Set appropriate cache headers

## 📈 Monitoring

### **Log Files**

- `logs/error.log` - PHP errors and application issues
- `logs/security.log` - Security events and audit trail
- `logs/rate_limit.json` - Rate limiting data

### **Health Checks**

- Database connectivity
- Session functionality
- Security headers presence
- Log file accessibility

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/security-enhancement`)
3. Commit your changes (`git commit -am 'Add new security feature'`)
4. Push to the branch (`git push origin feature/security-enhancement`)
5. Create a Pull Request

### **Security Contributions**

- Report security vulnerabilities privately
- Follow responsible disclosure practices
- Include proof-of-concept when appropriate
- Suggest remediation steps

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Bootstrap** - Responsive UI framework
- **DataTables** - Enhanced table functionality
- **PHP Security Best Practices** - OWASP guidelines
- **Modern PHP Development** - PSR standards compliance

## 📞 Support

For security issues or questions:

- Create an issue in the GitHub repository
- Follow responsible disclosure for security vulnerabilities
- Check the [SECURITY.md](SECURITY.md) file for security policies

---

**⚡ Built with security-first principles and modern PHP best practices**
