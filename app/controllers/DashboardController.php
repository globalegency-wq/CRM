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
// app/controllers/DashboardController.php

class DashboardController
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;

        // Simple auth check
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    protected function getSingleInt(string $sql): int
    {
        $result = $this->conn->query($sql);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_row();
        return isset($row[0]) ? (int)$row[0] : 0;
    }

    public function index()
    {
        // Top stats
        // جلب عدد العملاء النشطين
// جلب عدد العملاء النشطين - استخدمنا conn بدلاً من db
    $activeCount = 0;
    $resActive = $this->conn->query("SELECT COUNT(*) as total FROM phpcrm_customers WHERE user_status = 'نشط'");
    if ($resActive) {
        $activeCount = $resActive->fetch_assoc()['total'];
    }

    // جلب عدد العملاء الخاملين
    $inactiveCount = 0;
    $resInactive = $this->conn->query("SELECT COUNT(*) as total FROM phpcrm_customers WHERE user_status = 'خامل'");
    if ($resInactive) {
        $inactiveCount = $resInactive->fetch_assoc()['total'];
    }
        $totalCustomers = $this->getSingleInt("SELECT COUNT(*) FROM phpcrm_customers");
        $totalLeads     = $this->getSingleInt("SELECT COUNT(*) FROM phpcrm_leads");
        $pendingTasks   = $this->getSingleInt("SELECT COUNT(*) FROM phpcrm_tasks WHERE status = 'pending'");

        // Today's tasks
        $todayTasks = [];
        $sqlToday = "
            SELECT t.*, c.name AS customer_name
            FROM phpcrm_tasks t
            LEFT JOIN phpcrm_customers c ON c.id = t.customer_id
            WHERE DATE(t.followup_date) = CURDATE()
            ORDER BY t.followup_date ASC
        ";
        if ($res = $this->conn->query($sqlToday)) {
            while ($row = $res->fetch_assoc()) {
                $todayTasks[] = $row;
            }
            $res->free();
        }

        // Recent leads
        $recentLeads = [];
        $sqlLeads = "
            SELECT id, name, email, phone, company_name, created_at
            FROM phpcrm_leads
            ORDER BY id DESC
            LIMIT 5
        ";
        if ($res = $this->conn->query($sqlLeads)) {
            while ($row = $res->fetch_assoc()) {
                $recentLeads[] = $row;
            }
            $res->free();
        }

        // Recent customers
        $recentCustomers = [];
        $sqlCustomers = "
            SELECT id, name, email, phone, company_name, created_at
            FROM phpcrm_customers
            ORDER BY id DESC
            LIMIT 5
        ";
        if ($res = $this->conn->query($sqlCustomers)) {
            while ($row = $res->fetch_assoc()) {
                $recentCustomers[] = $row;
            }
            $res->free();
        }
// جلب أكثر 5 طلبات تكراراً
$topRequests = [];
$sqlRequests = "SELECT request, COUNT(*) as count FROM phpcrm_customers GROUP BY request ORDER BY count DESC LIMIT 5";
if ($resReq = $this->conn->query($sqlRequests)) {
    while ($row = $resReq->fetch_assoc()) {
        $topRequests[] = $row;
    }
    $resReq->free();
}

// تجهيز البيانات للمخطط (Labels and Data)
$reqLabels = array_column($topRequests, 'request');
$reqData = array_column($topRequests, 'count');

// تمرير البيانات لملف العرض
include __DIR__ . '/../views/dashboard/index.php';
        // Include view
    }
}