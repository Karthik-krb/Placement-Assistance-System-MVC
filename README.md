# Placement Assistance System (PAS)

## Project Overview
A web-based Placement Assistance System built using PHP with MVC (Model-View-Controller) architecture and OOP principles.

## Technology Stack
- **Backend**: PHP (OOP with MVC pattern)
- **Database**: MySQL (PDO)
- **Frontend**: HTML5, CSS3, Bootstrap 5.3.3, JavaScript
- **Server**: XAMPP (Apache + MySQL)

---

## Project Structure

```
PAS/
├── app/
│   ├── config/
│   │   └── database.php          # Database connection singleton class
│   ├── controllers/
│   │   ├── homecontroller.php    # Home page controller
│   │   ├── AdminAuthController.php
│   │   ├── CandidateAuthController.php
│   │   └── CompanyAuthController.php
│   ├── core/
│   │   └── app.php               # Main application router/dispatcher
│   ├── models/
│   │   ├── admin.php             # Admin model with authentication
│   │   ├── candidate.php         # Candidate model with authentication
│   │   └── company.php           # Company model with authentication
│   └── views/
│       ├── home.php              # Landing page
│       └── auth/
│           ├── adminlogin.php    # Admin login form
│           ├── candidatelogin.php # Candidate login form
│           └── company.php       # Company login form
├── config/
│   └── config.php                # Application configuration
├── public/                       # Document root (public-facing files)
│   ├── index.php                 # Application entry point
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   └── main.js
│   ├── home/                     # Homepage images
│   │   ├── image_1.jpeg
│   │   ├── image_2.jpeg
│   │   └── image_3.jpeg
│   └── assets/                   # Icons and other assets
└── error.php                     # Error handling page
```

---

## Database Setup

### 1. Create Database
```sql
CREATE DATABASE placement_data;
USE placement_data;
```

### 2. Create Tables

#### Admin Table
```sql
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Candidate Table
```sql
CREATE TABLE candidate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Company Table
```sql
CREATE TABLE company (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Insert Sample Data (with hashed passwords)
```sql
-- Password for all: 'password123'
INSERT INTO admin (name, email, password) VALUES 
('Admin User', 'admin@pas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO candidate (name, email, password, phone) VALUES 
('John Doe', 'candidate@pas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567890');

INSERT INTO company (name, email, password, phone) VALUES 
('Tech Corp', 'company@pas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210');
```

---

## Configuration

### Database Configuration
Update `/config/config.php` with your database credentials:
```php
'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'dbname' => 'placement_data',
    'user' => 'root',
    'pass' => 'your_password_here',
],
```

---

## URL Routes (MVC Structure)

### Main Routes
| Method | URL | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | `/PAS/public/` | HomeController@index | Landing page |
| GET | `/PAS/public/auth/admin/login` | AdminAuthController@showLogin | Admin login form |
| POST | `/PAS/public/auth/admin/login` | AdminAuthController@login | Process admin login |
| GET | `/PAS/public/auth/candidate/login` | CandidateAuthController@showLogin | Candidate login form |
| POST | `/PAS/public/auth/candidate/login` | CandidateAuthController@login | Process candidate login |
| GET | `/PAS/public/auth/company/login` | CompanyAuthController@showLogin | Company login form |
| POST | `/PAS/public/auth/company/login` | CompanyAuthController@login | Process company login |

---

## How to Run the Project

### 1. Start XAMPP
- Open XAMPP Control Panel
- Start **Apache** and **MySQL** services

### 2. Access the Application
Open your browser and navigate to:
```
http://localhost/PAS/public/
```

### 3. Login Credentials (if you used sample data)
- **Email**: admin@pas.com / candidate@pas.com / company@pas.com
- **Password**: password123

---

## MVC Architecture Explained

### Model (app/models/)
- Handles database operations
- Contains business logic
- Examples: `Admin`, `Candidate`, `Company` models
- Each model has an `authenticate()` method for login

### View (app/views/)
- Presentation layer (HTML/CSS)
- Displays data to users
- No business logic
- Examples: `home.php`, `adminlogin.php`

### Controller (app/controllers/)
- Handles user requests
- Coordinates between Model and View
- Examples: `AdminAuthController`, `HomeController`
- Methods: `showLogin()`, `login()`, `index()`

### Router (app/core/app.php)
- Parses incoming URLs
- Maps routes to controllers
- Handles 404 errors

---

## Key Features Implemented

✅ **MVC Architecture** - Clean separation of concerns
✅ **OOP Principles** - Classes, inheritance, encapsulation
✅ **Database Singleton Pattern** - Single database connection instance
✅ **PDO with Prepared Statements** - SQL injection prevention
✅ **Password Hashing** - Using `password_hash()` and `password_verify()`
✅ **Session Management** - User authentication tracking
✅ **Error Handling** - User-friendly error messages
✅ **Form Validation** - Email and password validation
✅ **Responsive Design** - Bootstrap 5 framework

---

## Common Issues & Solutions

### CSS Not Loading
- ✅ Fixed: Use absolute paths `/PAS/public/css/styles.css`
- Ensure Apache document root is configured correctly

### Database Connection Fails
- Check MySQL is running in XAMPP
- Verify credentials in `config/config.php`
- Ensure database `placement_data` exists

### Login Not Working
- ✅ Fixed: Form field names match controller expectations (`email`, `password`)
- Check if user exists in database with correct hashed password

### 404 Errors on Routes
- ✅ Fixed: All routes use proper MVC structure
- Access via `/PAS/public/` not `/PAS/`

---

## Next Steps / To-Do

- [ ] Add registration functionality for candidates and companies
- [ ] Create admin dashboard
- [ ] Create candidate dashboard (view jobs, apply)
- [ ] Create company dashboard (post jobs, view applications)
- [ ] Add job listing and application features
- [ ] Implement "Forgot Password" functionality
- [ ] Add profile management
- [ ] Implement logout functionality
- [ ] Add input sanitization
- [ ] Create middleware for authentication checks

---

## File Naming Conventions

- **Controllers**: PascalCase with "Controller" suffix (e.g., `AdminAuthController.php`)
- **Models**: PascalCase (e.g., `Admin.php`, `Candidate.php`)
- **Views**: lowercase (e.g., `adminlogin.php`, `home.php`)
- **Config**: lowercase (e.g., `database.php`, `config.php`)

---

## Security Notes

⚠️ **Important**: This is a development setup. For production:
- Turn off error display in `public/index.php`
- Use environment variables for sensitive data
- Implement CSRF protection
- Add rate limiting for login attempts
- Use HTTPS
- Validate and sanitize all user inputs
- Implement proper session security
- Add logging for security events

---

## Contact & Support

For issues or questions about this project structure, refer to the MVC pattern documentation or PHP OOP best practices.
