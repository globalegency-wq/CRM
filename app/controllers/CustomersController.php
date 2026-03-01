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
// app/controllers/CustomersController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Customer.php';

class CustomersController extends Controller
{
    protected $customer;

    public function __construct($conn)
    {
        parent::__construct($conn);
        $this->customer = new Customer($conn);

        // Simple auth check
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    // List customers (GET)
    public function index()
    {
        $customers = $this->customer->all();
        // old view uses $users, so keep alias for backward compatibility
        $users = $customers;

        $successMsg = $_SESSION['customer_success'] ?? '';
        $errorMsg   = $_SESSION['customer_error'] ?? '';
        unset($_SESSION['customer_success'], $_SESSION['customer_error']);

        $this->view('customers/index', [
            'customers'  => $customers,
            'users'      => $users,
            'successMsg' => $successMsg,
            'errorMsg'   => $errorMsg,
        ]);
    }

    // Show add form (GET)
    public function create()
    {
        $errorMsg   = $_SESSION['add_customer_error']   ?? '';
        $successMsg = $_SESSION['add_customer_success'] ?? '';
        $old        = $_SESSION['add_customer_old']     ?? [];
        unset($_SESSION['add_customer_error'], $_SESSION['add_customer_success'], $_SESSION['add_customer_old']);

        $this->view('customers/create', [
            'errorMsg'   => $errorMsg,
            'successMsg' => $successMsg,
            'old'        => $old,
        ]);
    }

    // Handle form submit (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('customers.php?a=create');
        }

        $data = [
            'name'             => trim($_POST['name'] ?? ''),
            'email'            => trim($_POST['email'] ?? ''),
            'phone'            => trim($_POST['phone'] ?? ''),
             'company_name'     => trim($_POST['company_name'] ?? ''),
            'company_reg_no'   => trim($_POST['company_reg_no'] ?? ''),
             'C_tax_number_declare'  => trim($_POST['C_tax_number_declare'] ?? ''),
            'C_tax_number_date'  => trim($_POST['C_tax_number_date'] ?? ''),
            //  'P_tax_number_declare'  => trim($_POST['P_tax_number_declare'] ?? ''),
            // 'P_tax_number_date'  => trim($_POST['P_tax_number_date'] ?? ''),
            // 'Provider_phone'  => trim($_POST['Provider_phone'] ?? ''),
    
            // 'Customer_actor'  => trim($_POST['Customer_actor'] ?? ''),
             'C_identity_number'  => trim($_POST['C_identity_number'] ?? ''),
             'C_identity_declare'  => trim($_POST['C_identity_declare'] ?? ''),
             'C_identity_date'  => trim($_POST['C_identity_date'] ?? ''),
            // 'C_address'  => trim($_POST['C_address'] ?? ''),
            // 'C_phone'  => trim($_POST['C_phone'] ?? ''),
           
            'company_address'  => trim($_POST['company_address'] ?? ''),
           
           // 'country'          => trim($_POST['country'] ?? ''),
            'role'             => trim($_POST['role'] ?? ''),
            'request'             => trim($_POST['request'] ?? ''),
          //  'employees'        => trim($_POST['employees'] ?? ''),
            'user_status'      => trim($_POST['user_status'] ?? 'نشط'),
        ];

        $_SESSION['add_customer_old'] = $data;

        // Validation
        if (
            !$data['name'] || 
           //!$data['email'] ||
            !$data['phone'] || 
            
            // !$data['P_tax_number_declare']||
            // !$data['P_tax_number_date']||
            // !$data['Customer_actor']||
      
            // !$data['C_address']||
            // !$data['C_phone']||
 
            !$data['company_name'] ||
          // !$data['company_reg_no'] ||
         //    !$data['C_tax_number_declare']||
         //   !$data['C_tax_number_date']||
             //     !$data['C_identity_number']||
          //   !$data['C_identity_declare']||
           //  !$data['C_identity_date']||
            !$data['company_address']  ||
            !$data['role']||
            !$data['request']
        )
         {
            $_SESSION['add_customer_error'] = 'كل الحقول مطلوبة.';
            $this->redirect('customers.php?a=create');
        }
        

        // if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        //     $_SESSION['add_customer_error'] = 'صيغة الايميل غير صحيحة.';
        //     $this->redirect('customers.php?a=create');
        // }


        // if ($this->customer->emailExists($data['email'])) {
        //     $_SESSION['add_customer_error'] = 'الايميل موجود من قبل.';
        //     $this->redirect('customers.php?a=create');
        // }

        $insert = [
            'customer_code'  => 'CUST-' . str_pad((string)rand(1,9999), 4, '0', STR_PAD_LEFT),
            'name'           => $data['name'],
            'email'          => $data['email'],
           'phone'          => $data['phone'],
            'company_name'   => $data['company_name'],
            'company_reg_no' => $data['company_reg_no'],
              'C_tax_number_declare'  =>$data['C_tax_number_declare'],
            'C_tax_number_date'  =>$data['C_tax_number_date'],
            //  'P_tax_number_declare' =>$data['P_tax_number_declare'],
            // 'P_tax_number_date'  =>$data['P_tax_number_date'],
            // 'Provider_phone'  =>$data['Provider_phone'],
          
            // 'Customer_actor'  =>$data['Customer_actor'],
            'C_identity_number'  =>$data['C_identity_number'],
             'C_identity_declare' =>$data['C_identity_declare'],
            'C_identity_date'  =>$data['C_identity_date'],
            // 'C_address'  =>$data['C_address'],
            // 'C_phone'  =>$data['C_phone'],
           
            'company_address'=> $data['company_address'],
            
           // 'country'        => $data['country'],
            'role'           => $data['role'],
            'request'           => $data['request'],
           // 'employees'      => $data['employees'],
            'user_status'    => $data['user_status'],
        ];

        if ($this->customer->create($insert)) {
            $_SESSION['add_customer_success'] = 'تم اضافة العميل بنجاح.';
            $_SESSION['add_customer_old'] = [];
            $this->redirect('customers.php?a=create');
        }

        $_SESSION['add_customer_error'] = 'خطأ في عملية اضافة العميل.';
        $this->redirect('customers.php?a=create');
    }
//     public function store()
// {
//     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//         $this->redirect('customers.php?a=create');
//     }

//     $data = [
//         'name'                 => trim($_POST['name'] ?? ''),
//         'email'                => trim($_POST['email'] ?? ''),
//         'phone'                => trim($_POST['phone'] ?? ''),
//         'company_name'         => trim($_POST['company_name'] ?? ''),
//         'company_reg_no'       => trim($_POST['company_reg_no'] ?? ''),
//         'C_tax_number_declare' => trim($_POST['C_tax_number_declare'] ?? ''),
//         'C_tax_number_date'    => trim($_POST['C_tax_number_date'] ?? ''),
//         'C_identity_number'    => trim($_POST['C_identity_number'] ?? ''),
//         'C_identity_declare'   => trim($_POST['C_identity_declare'] ?? ''),
//         'C_identity_date'      => trim($_POST['C_identity_date'] ?? ''),  
//         'company_address'      => trim($_POST['company_address'] ?? ''),
//         'role'                 => trim($_POST['role'] ?? ''),
//         'request'              => trim($_POST['request'] ?? ''),
//         'user_status'          => trim($_POST['user_status'] ?? 'نشط'),
//     ];

//     $_SESSION['add_customer_old'] = $data;

//     // مصفوفة بالحقول الإلزامية فقط
//     $required_fields = [
//         'name', 'email', 'phone', 'company_name', 'company_reg_no', 
//         'C_tax_number_declare', 'C_tax_number_date', 'C_identity_number', 
//         'C_identity_declare', 'C_identity_date', 'company_address', 'role', 'request'
//     ];

//     foreach ($required_fields as $field) {
//         if (empty($data[$field])) {
//             $_SESSION['add_customer_error'] = "الحقل ($field) مطلوب.";
//             $this->redirect('customers.php?a=create');
//             return; // تأكد من إنهاء التنفيذ هنا
//         }
//     }

//     // إذا وصل الكود هنا، يعني أن جميع الحقول ممتلئة
//     $insert = $data;
//     // إضافة الكود العشوائي
//     $insert['customer_code'] = 'CUST-' . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);

//     if ($this->customer->create($insert)) {
//         $_SESSION['add_customer_success'] = 'تم اضافة العميل بنجاح.';
//         $_SESSION['add_customer_old'] = [];
//         $this->redirect('customers.php?a=create');
//     } else {
//         $_SESSION['add_customer_error'] = 'خطأ في عملية اضافة العميل.';
//         $this->redirect('customers.php?a=create');
//     }
// }
	
	
	
public function edit()
{
    $id = $_GET['id'] ?? 0;
    if (!$id) return $this->redirect('customers.php?a=index');

    $customer = $this->customer->find($id);
    if (!$customer) return $this->redirect('customers.php?a=index');

    $errorMsg   = $_SESSION['edit_customer_error'] ?? '';
    $successMsg = $_SESSION['edit_customer_success'] ?? '';
    unset($_SESSION['edit_customer_error'], $_SESSION['edit_customer_success']);

    $this->view('customers/edit', [
        'customer'   => $customer,
        'errorMsg'   => $errorMsg,
        'successMsg' => $successMsg
    ]);
}
	
public function update()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->redirect('customers.php?a=index');
    }

    $id = $_POST['id'] ?? 0;
    if (!$id) return $this->redirect('customers.php?a=index');

    $data = [
        'name'            => trim($_POST['name']),
        'email'           => trim($_POST['email']),
        'phone'           => trim($_POST['phone']),
         'company_name'    => trim($_POST['company_name']),
        'company_reg_no'  => trim($_POST['company_reg_no']),
              'C_tax_number_declare'  => trim($_POST['C_tax_number_declare'] ?? ''),
            'C_tax_number_date'  => trim($_POST['C_tax_number_date'] ?? ''),
        //  'P_tax_number_declare'  => trim($_POST['P_tax_number_declare'] ?? ''),
        //     'P_tax_number_date'  => trim($_POST['P_tax_number_date'] ?? ''),
        //     'Provider_phone'  => trim($_POST['Provider_phone'] ?? ''),
      
            // 'Customer_actor'  => trim($_POST['Customer_actor'] ?? ''),
             'C_identity_number'  => trim($_POST['C_identity_number'] ?? ''),
             'C_identity_declare'  => trim($_POST['C_identity_declare'] ?? ''),
             'C_identity_date'  => trim($_POST['C_identity_date'] ?? ''),
            // 'C_address'  => trim($_POST['C_address'] ?? ''),
            // 'C_phone'  => trim($_POST['C_phone'] ?? ''),
       
        'company_address' => trim($_POST['company_address']),
      //  'country'         => trim($_POST['country']),
        'role'            => trim($_POST['role']),
      //  'employees'       => trim($_POST['employees']),
      'request'           =>  trim($_POST['request']),
        'user_status'          => trim($_POST['user_status']),
    ];

    if ($this->customer->update($id, $data)) {
        $_SESSION['edit_customer_success'] = 'تم تحديث بيانات العميل بنجاح.';
    } else {
        $_SESSION['edit_customer_error'] = 'خطأ في عملية تحديث بيانات العميل.';
    }

    return $this->redirect('customers.php?a=edit&id=' . $id);
}


public function delete()
{
    $id = $_GET['id'] ?? 0;
    if ($id && $this->customer->delete($id)) {
        $_SESSION['customer_success'] = 'تم حذف العميل بنجاح.';
    } else {
        $_SESSION['customer_error'] = 'خطأ في عملية حذف العميل.';
    }
    return $this->redirect('customers.php?a=index');
}

	
	
	
	
	
	
	
	
	
	
	
	
}