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
// app/controllers/TasksController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/Customer.php';

class TasksController extends Controller
{
    protected $task;
    protected $customerModel;

    public function __construct($conn)
    {
        parent::__construct($conn);
        $this->task          = new Task($conn);
        $this->customerModel = new Customer($conn);

        if (empty($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public function index()
    {
        $tasks = $this->task->all();
        include __DIR__ . '/../views/tasks/index.php';
    }

    public function create()
    {
        $task = null;
        $customers = $this->customerModel->all();
        include __DIR__ . '/../views/tasks/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: tasks.php');
            exit;
        }

        $title       = trim($_POST['title'] ?? '');
        $customer_id = (int)($_POST['customer_id'] ?? 0);
        $followup    = trim($_POST['followup_date'] ?? '');

        if ($title === '' || $customer_id <= 0 || $followup === '') {
            $_SESSION['error'] = 'اسم العميل والعنوان وتاريخ المتابعة مطلوب.';
            header('Location: tasks.php?a=create');
            exit;
        }

        $data = [
            'customer_id'   => $customer_id,
            'title'         => $title,
            'description'   => $_POST['description'] ?? null,
            'followup_date' => $followup,
            'status'        => $_POST['status'] ?? 'معلقة',
        ];

        $this->task->create($data);
        $_SESSION['success'] = 'تم اضافة المهمة بنجاح.';
        header('Location: tasks.php');
        exit;
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: tasks.php');
            exit;
        }

        $task = $this->task->find($id);
        if (!$task) {
            $_SESSION['error'] = 'المهمة غير موجودة.';
            header('Location: tasks.php');
            exit;
        }

        $customers = $this->customerModel->all();
        include __DIR__ . '/../views/tasks/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: tasks.php');
            exit;
        }

        $id          = (int)($_POST['id'] ?? 0);
        $title       = trim($_POST['title'] ?? '');
        $customer_id = (int)($_POST['customer_id'] ?? 0);
        $followup    = trim($_POST['followup_date'] ?? '');

        if ($id <= 0 || $title === '' || $customer_id <= 0 || $followup === '') {
            $_SESSION['error'] = 'بيانات المهمة غير صحيحة.';
            header('Location: tasks.php');
            exit;
        }

        $data = [
            'customer_id'   => $customer_id,
            'title'         => $title,
            'description'   => $_POST['description'] ?? null,
            'followup_date' => $followup,
            'status'        => $_POST['status'] ?? 'معلقة',
        ];

        $this->task->update($id, $data);
        $_SESSION['success'] = 'تم تحديث بيانات المهمة بنجاح.';
        header('Location: tasks.php');
        exit;
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->task->delete($id);
            $_SESSION['success'] = 'تم حذف المهمة بنجاح.';
        }
        header('Location: tasks.php');
        exit;
    }
}