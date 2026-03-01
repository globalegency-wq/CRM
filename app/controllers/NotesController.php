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
// app/controllers/NotesController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Customer.php';

class NotesController extends Controller
{
    protected $note;
    protected $customerModel;

    public function __construct($conn)
    {
        parent::__construct($conn);
        $this->note          = new Note($conn);
        $this->customerModel = new Customer($conn);

        if (empty($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public function index()
    {
        $notes = $this->note->all();
        include __DIR__ . '/../views/notes/index.php';
    }

    public function create()
    {
        $customers = $this->customerModel->all();
        include __DIR__ . '/../views/notes/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: notes.php');
            exit;
        }

        $customer_id = (int)($_POST['customer_id'] ?? 0);
        $noteText    = trim($_POST['note'] ?? '');

        if ($customer_id <= 0 || $noteText === '') {
            $_SESSION['error'] = 'اسم العميل ونص الملاحظة مطلوب.';
            header('Location: notes.php?a=create');
            exit;
        }

        $data = [
            'customer_id' => $customer_id,
            'note'        => $noteText,
            'added_by'    => $_SESSION['user_id'] ?? null,
        ];

        $this->note->create($data);
        $_SESSION['success'] = 'تم اضافة الملاحظة بنجاح.';
        header('Location: notes.php');
        exit;
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->note->delete($id);
            $_SESSION['success'] = 'تم حذف الملاحظة بنجاح.';
        }
        header('Location: notes.php');
        exit;
    }
}