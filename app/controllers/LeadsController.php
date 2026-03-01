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
// app/controllers/LeadsController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Lead.php';

class LeadsController extends Controller
{
    protected $lead;

    public function __construct($conn)
    {
        parent::__construct($conn);
        $this->lead = new Lead($conn);

        if (empty($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public function index()
    {
        $leads = $this->lead->all();
        include __DIR__ . '/../views/leads/index.php';
    }

    public function create()
    {
        $lead = null;
        include __DIR__ . '/../views/leads/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: leads.php');
            exit;
        }

        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            $_SESSION['error'] = 'الاسم مطلوب.';
            header('Location: leads.php?a=create');
            exit;
        }

        $data = [
            'name'         => $name,
            'email'        => $_POST['email'] ?? null,
            'phone'        => $_POST['phone'] ?? null,
            'company_name' => $_POST['company_name'] ?? null,
            'lead_source'  => $_POST['lead_source'] ?? null,
            'status'       => $_POST['status'] ?? 'new',
        ];

        $this->lead->create($data);

        $_SESSION['success'] = 'تم اضافة الفقة بنجاح.';
        header('Location: leads.php');
        exit;
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: leads.php');
            exit;
        }

        $lead = $this->lead->find($id);
        if (!$lead) {
            $_SESSION['error'] = 'الصفقة غير موجودة.';
            header('Location: leads.php');
            exit;
        }

        include __DIR__ . '/../views/leads/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: leads.php');
            exit;
        }

        $id   = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if ($id <= 0 || $name === '') {
            $_SESSION['error'] = 'بيانات غير صحيحة.';
            header('Location: leads.php');
            exit;
        }

        $data = [
            'name'         => $name,
            'email'        => $_POST['email'] ?? null,
            'phone'        => $_POST['phone'] ?? null,
            'company_name' => $_POST['company_name'] ?? null,
            'lead_source'  => $_POST['lead_source'] ?? null,
            'status'       => $_POST['status'] ?? 'new',
        ];

        $this->lead->update($id, $data);

        $_SESSION['success'] = 'تم تحديث بيانات الصفقة بنجاح.';
        header('Location: leads.php');
        exit;
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->lead->delete($id);
            $_SESSION['success'] = 'تم حذف الصفقة بنجاح.';
        }
        header('Location: leads.php');
        exit;
    }
}