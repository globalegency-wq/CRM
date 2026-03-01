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
// app/controllers/ChangePasswordController.php

class ChangePasswordController
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;

        // authentication check
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    public function index()
    {
        $success = $_SESSION['success'] ?? null;
        $error   = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        include __DIR__ . '/../views/change_password/index.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: change_password.php");
            exit;
        }

        $current_pass = trim($_POST['current_password'] ?? '');
        $new_pass     = trim($_POST['new_password'] ?? '');
        $confirm_pass = trim($_POST['confirm_password'] ?? '');
        $user_id      = $_SESSION['user_id'];

        if ($current_pass === '' || $new_pass === '' || $confirm_pass === '') {
            $_SESSION['error'] = "كل الحقول مطلوبة.";
            header("Location: change_password.php");
            exit;
        }

        if ($new_pass !== $confirm_pass) {
            $_SESSION['error'] = "كلمة السر غير متطابقة.";
            header("Location: change_password.php");
            exit;
        }

        // get current hash
        $sql  = "SELECT password_hash FROM phpcrm_users WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($current_pass, $user['password_hash'])) {
            $_SESSION['error'] = "كلمة السر الحالية غير صحيحة.";
            header("Location: change_password.php");
            exit;
        }

        // update new password
        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE phpcrm_users SET password_hash = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $new_hash, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "تم تغيير كلمة السر بنجاح.";
        header("Location: change_password.php");
        exit;
    }
}