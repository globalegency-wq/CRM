<?php
/* 
=======================================================================
 PHPCRM — Open Source CRM Software
 Version: 8.1
 License: MIT Open Source License
 Developed & Maintained By: PHPCRM (https://www.phpcrm.com)
 
 Description:
 PHPCRM is an open-source Customer Relationship Management system 
 designed for businesses of all sizes to manage Leads, Customers, 
 Follow-ups, Tasks, Notes and Sales Operations with simplicity and speed.

 Features Included in Open Source Edition:
 - Customer Management
 - Lead Management
 - Tasks / Follow-ups
 - Notes & Activity Tracking
 - Dashboard with KPIs
 - Change Password & Secure Login

 This software is open for modification and extension. 
 You are free to customize, improve and commercially use it without fees
 as long as copyright notice remains preserved.

 Community Contribution:
 We welcome developers to contribute improvements, fixes and new modules.
 Visit our website for documentation, updates and support.

 Website: https://www.phpcrm.com
 Last Update: 29-11-2025
=======================================================================
*/	

class AuthController
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // GET /index.php  or /index.php?a=login
    public function login()
    {
        // simple CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $error = $_GET['error'] ?? null;

        include __DIR__ . '/../views/auth/login.php';
    }

    // POST /index.php?a=doLogin
    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        // CSRF check
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            header('Location: index.php?error=invalid_csrf');
            exit;
        }

        // honeypot check
        if (!empty($_POST['website'])) {
            // bot
            header('Location: index.php?error=unknown');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            header('Location: index.php?error=missing_fields');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?error=invalid_email');
            exit;
        }

        // yahan apna users table ka query lagao
        $sql  = "SELECT id, password_hash FROM phpcrm_users WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            header('Location: index.php?error=db_error');
            exit;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            header('Location: index.php?error=invalid_login');
            exit;
        }

        // login success
        $_SESSION['user_id'] = $user['id'];

        header('Location: dashboard.php');
        exit;
    }

    // optional: logout action
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }
	

	
}
