# Security Implementation Setup Guide

## Overview

This PHP application has been enhanced with comprehensive security features including:

✅ **SQL Injection Prevention** - Using PDO prepared statements
✅ **Password Hashing** - Using PHP's password_hash() with bcrypt
✅ **Environment Variables** - Database credentials in .env file
✅ **CSRF Protection** - Token-based protection for forms
✅ **Rate Limiting** - Prevents brute force attacks
✅ **Input Validation & Sanitization** - All user input is validated
✅ **Session Security** - Secure session configuration
✅ **Security Headers** - XSS, CSRF, and other attack prevention
✅ **Access Control** - Protected pages require authentication

## Setup Instructions

### 1. Database Setup

```sql
-- Run the database_update.sql script to update your database schema
mysql -u root -p reservation < database_update.sql
```

### 2. Environment Configuration

```bash
# Copy the .env file and update with your database credentials
# Make sure .env is not accessible via web (protected by .htaccess)
```

### 3. Password Migration (One-time)

```bash
# If you have existing users with plain text passwords, run:
php migrate_passwords.php
# Then DELETE this file for security
rm migrate_passwords.php
```

### 4. Directory Permissions

```bash
# Create logs directory with proper permissions
mkdir -p logs
chmod 755 logs
chmod 644 logs/*.log
```

### 5. File Security

- Delete `migrate_passwords.php` after running it
- Move `database_update.sql` outside web root after use
- Ensure `.env` file is not web accessible

## Security Features Implemented

### 1. Database Security

- **PDO with Prepared Statements**: Prevents SQL injection
- **Environment Variables**: Database credentials stored securely
- **Connection Security**: Proper error handling and connection management

### 2. Authentication Security

- **Password Hashing**: Using bcrypt algorithm
- **Session Security**: HTTPOnly, Secure, SameSite cookies
- **Rate Limiting**: 5 attempts per 5 minutes per IP
- **Session Regeneration**: New session ID on login

### 3. Input Security

- **CSRF Protection**: Tokens for all forms
- **Input Validation**: Email format, password strength
- **Input Sanitization**: HTML entity encoding
- **XSS Prevention**: htmlspecialchars() on all output

### 4. Access Control

- **Authentication Required**: Protected pages check login status
- **Session Timeout**: Configurable session lifetime
- **Secure Logout**: Proper session destruction

### 5. HTTP Security

- **Security Headers**: X-Frame-Options, XSS-Protection, etc.
- **Content Security Policy**: Restricts resource loading
- **Cache Control**: Prevents caching of sensitive pages

## File Structure

```
/
├── config/
│   └── database.php          # Database connection class
├── includes/
│   ├── security.php          # Security utilities
│   ├── security_headers.php  # HTTP security headers
│   ├── user.php             # User management class
│   └── rate_limiter.php     # Rate limiting functionality
├── logs/                    # Application logs (create manually)
├── .env                     # Environment variables
├── .htaccess               # Apache security rules
└── *.php                   # Application files
```

## Test Users

After running the database update, you can test with:

- **Email**: test@example.com
- **Password**: Password123

- **Email**: admin@example.com
- **Password**: Admin123

## Production Deployment

### Essential Security Checklist

- [ ] Use HTTPS/SSL certificate
- [ ] Set strong passwords for test accounts
- [ ] Update .env with production database credentials
- [ ] Set error reporting to 0 in production
- [ ] Enable Apache mod_security if available
- [ ] Regular security updates
- [ ] Monitor logs for suspicious activity
- [ ] Backup database regularly
- [ ] Use strong session secret key

### Optional Enhancements

- [ ] Implement 2FA (Two-Factor Authentication)
- [ ] Add login audit trail
- [ ] Implement account lockout after failed attempts
- [ ] Add password reset functionality
- [ ] Email verification for registration
- [ ] Admin panel for user management

## Security Testing

1. **SQL Injection**: Try entering `' OR '1'='1` in login forms
2. **XSS**: Try entering `<script>alert('xss')</script>` in forms
3. **CSRF**: Submit forms without CSRF tokens
4. **Rate Limiting**: Try multiple failed login attempts
5. **Session Security**: Check for session hijacking vulnerabilities

## Monitoring & Logs

- Application logs: `logs/error.log`
- Rate limiting logs: `logs/rate_limit.json`
- Failed login attempts are logged with IP addresses

## Support

For questions or issues with this security implementation, check:

1. PHP error logs
2. Apache error logs
3. Application logs in `/logs/` directory

Remember to keep your PHP version updated and follow security best practices!
