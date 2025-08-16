# FixLanka Deployment Guide

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation Steps](#installation-steps)
3. [Configuration](#configuration)
4. [Database Setup](#database-setup)
5. [AI Model Setup](#ai-model-setup)
6. [Email Configuration](#email-configuration)
7. [Security Configuration](#security-configuration)
8. [Testing](#testing)
9. [Troubleshooting](#troubleshooting)

## Prerequisites

### System Requirements
- **Operating System**: Windows 10/11, Linux (Ubuntu 18.04+), macOS 10.14+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 7.4 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **Python**: 3.7+ (for AI components)
- **Storage**: Minimum 10GB available space
- **RAM**: Minimum 4GB (8GB recommended)

### Required Software
1. **XAMPP** (for Windows) or **LAMP Stack** (for Linux)
2. **Python 3.7+** with pip
3. **Git** (for version control)
4. **Composer** (PHP dependency manager)

## Installation Steps

### Step 1: Download and Extract
```bash
# Clone the repository
git clone https://github.com/your-username/FixLanka.git
cd FixLanka

# Or download and extract ZIP file
# Extract to your web server directory
```

### Step 2: Web Server Setup

#### For XAMPP (Windows)
1. Download and install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services
3. Copy FixLanka folder to `C:\xampp\htdocs\`
4. Access via `http://localhost/FixLanka/`

#### For LAMP Stack (Linux)
```bash
# Install Apache
sudo apt update
sudo apt install apache2

# Install PHP and extensions
sudo apt install php php-mysql php-gd php-mbstring php-curl php-zip

# Install MySQL
sudo apt install mysql-server

# Copy files to web directory
sudo cp -r FixLanka /var/www/html/
sudo chown -R www-data:www-data /var/www/html/FixLanka
```

### Step 3: Database Setup

#### Create Database
```sql
-- Connect to MySQL
mysql -u root -p

-- Create database
CREATE DATABASE fixlanka CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (optional)
CREATE USER 'fixlanka_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON fixlanka.* TO 'fixlanka_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Import Schema
```bash
# Import database schema
mysql -u root -p fixlanka < fixlanka_database.sql
```

### Step 4: Configuration

#### Database Connection
Edit `Includes/dbconnect.php`:
```php
<?php
$host = 'localhost';
$dbuser = 'root';  // or 'fixlanka_user'
$dbpass = '';      // or 'your_password'
$dbname = 'fixlanka';

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
```

#### File Permissions
```bash
# Set proper permissions for uploads directory
chmod -R 755 uploads/
chmod -R 755 Includes/citizen/uploads/
chmod -R 755 Includes/admin/uploads/

# Make sure web server can write to uploads
chown -R www-data:www-data uploads/  # For Linux
```

### Step 5: Python Dependencies

#### Install Python Packages
```bash
# Navigate to AI directory
cd AI/

# Install required packages
pip install pandas scikit-learn joblib numpy

# Or create requirements.txt and install
echo "pandas>=1.3.0
scikit-learn>=1.0.0
joblib>=1.1.0
numpy>=1.21.0" > requirements.txt

pip install -r requirements.txt
```

#### Train AI Model
```bash
# Train the machine learning model
python train_model.py

# Verify model files are created
ls -la *.pkl
```

### Step 6: Email Configuration

#### PHPMailer Setup
1. PHPMailer is already included in the project
2. Configure email settings in relevant files

#### Gmail SMTP Configuration
Edit email configuration files:
```php
// Example configuration for Gmail
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

## Configuration

### Environment Variables
Create `.env` file in root directory:
```env
# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=fixlanka

# Email Configuration
SMTP_HOST=smtp.gmail.com
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
SMTP_PORT=587

# Application Settings
APP_URL=http://localhost/FixLanka
UPLOAD_MAX_SIZE=5242880
ALLOWED_FILE_TYPES=jpg,jpeg,png,gif
```

### Security Settings
1. **Change default passwords**
2. **Enable HTTPS** (for production)
3. **Configure firewall rules**
4. **Set up SSL certificates**

### Performance Optimization
1. **Enable PHP OPcache**
2. **Configure MySQL query cache**
3. **Enable Apache compression**
4. **Set up CDN for static assets**

## Database Setup

### Initial Data
```sql
-- Insert default departments
INSERT INTO departments (dept_name, description, contact_email) VALUES
('CEB', 'Ceylon Electricity Board', 'ceb@fixlanka.com'),
('RDA', 'Road Development Authority', 'rda@fixlanka.com'),
('SLTB', 'Sri Lanka Transport Board', 'sltb@fixlanka.com'),
('NWSDB', 'National Water Supply and Drainage Board', 'nwsdb@fixlanka.com'),
('Police', 'Sri Lanka Police', 'police@fixlanka.com'),
('DMC', 'Disaster Management Centre', 'dmc@fixlanka.com'),
('MOH', 'Ministry of Health', 'moh@fixlanka.com'),
('SLR', 'Sri Lanka Railways', 'slr@fixlanka.com');

-- Create admin user
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@fixlanka.com', '$2y$10$...', 'admin');
```

### Database Backup
```bash
# Create backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p fixlanka > backup_$DATE.sql

# Restore from backup
mysql -u root -p fixlanka < backup_20241217_143000.sql
```

## AI Model Setup

### Model Training
```python
# Train model with custom data
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
import joblib

# Load your training data
data = pd.read_csv("complaints_data.csv")

# Train model
vectorizer = TfidfVectorizer()
X = vectorizer.fit_transform(data['description'])
model = MultinomialNB()
model.fit(X, data['category'])

# Save model
joblib.dump(model, "complaint_model.pkl")
joblib.dump(vectorizer, "vectorizer.pkl")
```

### Model Testing
```python
# Test prediction
import joblib

model = joblib.load("complaint_model.pkl")
vectorizer = joblib.load("vectorizer.pkl")

test_complaint = "Street light not working"
X_test = vectorizer.transform([test_complaint])
prediction = model.predict(X_test)
print(f"Predicted department: {prediction[0]}")
```

## Email Configuration

### Gmail Setup
1. Enable 2-factor authentication
2. Generate app password
3. Configure PHPMailer settings

### Email Templates
Create email templates for:
- Password reset
- Complaint notifications
- Status updates

## Security Configuration

### File Security
```apache
# .htaccess configuration
<Files "*.php">
    Order Deny,Allow
    Deny from all
    Allow from 127.0.0.1
</Files>

<Files "index.php">
    Order Allow,Deny
    Allow from all
</Files>
```

### Database Security
```sql
-- Create restricted user
CREATE USER 'fixlanka_app'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON fixlanka.* TO 'fixlanka_app'@'localhost';
REVOKE DROP, CREATE, ALTER ON fixlanka.* FROM 'fixlanka_app'@'localhost';
```

### SSL Configuration
```apache
# Apache SSL configuration
<VirtualHost *:443>
    ServerName fixlanka.com
    DocumentRoot /var/www/html/FixLanka
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    Redirect permanent / http://fixlanka.com/
</VirtualHost>
```

## Testing

### Functional Testing
1. **User Registration**: Test citizen registration
2. **Complaint Submission**: Submit test complaints
3. **AI Prediction**: Verify department prediction
4. **Status Updates**: Test complaint workflow
5. **Review System**: Test review submission

### Performance Testing
```bash
# Apache Benchmark
ab -n 1000 -c 10 http://localhost/FixLanka/

# Database performance
mysqlslap --concurrency=50 --iterations=10 --create-schema=fixlanka
```

### Security Testing
1. **SQL Injection**: Test input fields
2. **XSS**: Test comment fields
3. **File Upload**: Test malicious files
4. **Authentication**: Test login bypass

## Troubleshooting

### Common Issues

#### Database Connection Error
```bash
# Check MySQL service
sudo systemctl status mysql

# Check connection
mysql -u root -p -h localhost

# Verify database exists
SHOW DATABASES;
```

#### File Upload Issues
```bash
# Check upload directory permissions
ls -la uploads/

# Check PHP upload settings
php -i | grep upload

# Increase upload limits in php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

#### AI Model Issues
```bash
# Check Python installation
python3 --version

# Check required packages
pip list | grep -E "(pandas|scikit-learn|joblib)"

# Test model prediction
python3 AI/predict.py "test complaint"
```

#### Email Issues
```bash
# Check PHPMailer configuration
# Verify SMTP settings
# Test email sending
```

### Log Files
```bash
# Apache error logs
tail -f /var/log/apache2/error.log

# PHP error logs
tail -f /var/log/php_errors.log

# MySQL logs
tail -f /var/log/mysql/error.log
```

### Performance Monitoring
```bash
# Monitor system resources
htop
iotop
nethogs

# Monitor web server
apache2ctl status
```

## Production Deployment

### Server Setup
1. **Use dedicated server** or VPS
2. **Configure firewall** (UFW)
3. **Set up SSL certificates** (Let's Encrypt)
4. **Configure backup system**
5. **Set up monitoring** (Nagios, Zabbix)

### Load Balancing
```nginx
# Nginx load balancer configuration
upstream fixlanka_backend {
    server 127.0.0.1:8080;
    server 127.0.0.1:8081;
}

server {
    listen 80;
    server_name fixlanka.com;
    
    location / {
        proxy_pass http://fixlanka_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

### Backup Strategy
```bash
#!/bin/bash
# Automated backup script
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/fixlanka"

# Database backup
mysqldump -u root -p fixlanka > $BACKUP_DIR/db_$DATE.sql

# File backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz uploads/

# Clean old backups (keep 30 days)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

---

**Deployment Guide Version**: 1.0  
**Last Updated**: December 2024  
**Maintained By**: FixLanka Development Team 