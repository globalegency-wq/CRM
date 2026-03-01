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
// app/models/Customer.php
class Customer
{
    protected $conn;
    protected $table = 'phpcrm_customers';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

// public function all(): array
// {
//     // استقبال البيانات من الرابط (GET) وتنظيفها
//     $search = isset($_GET['search']) ? $this->conn->real_escape_string(trim($_GET['search'])) : '';
//     $date_from = isset($_GET['date_from']) ? $this->conn->real_escape_string($_GET['date_from']) : '';
//     $date_to = isset($_GET['date_to']) ? $this->conn->real_escape_string($_GET['date_to']) : '';

//     // الاستعلام الأساسي واختيار الحقول المطلوبة
//     $sql = "SELECT id, customer_code, name, email, phone, company_name, request, user_status, created_at 
//             FROM {$this->table} WHERE 1=1";

//     // تفعيل البحث الشامل (AND لضمان دمج الشروط)
//     if ($search !== '') {
//         $sql .= " AND (name LIKE '%$search%' 
//                     OR phone LIKE '%$search%' 
//                     OR request LIKE '%$search%' 
//                     OR company_name LIKE '%$search%')";
//     }

//     // فلترة التاريخ (من وإلى)
//     if ($date_from !== '') {
//         $sql .= " AND DATE(created_at) >= '$date_from'";
//     }
//     if ($date_to !== '') {
//         $sql .= " AND DATE(created_at) <= '$date_to'";
//     }

//     $sql .= " ORDER BY created_at DESC";

//     $result = $this->conn->query($sql);
//     return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
// }

public function all(): array
{
    // استقبال البيانات وتنظيفها
    $search = isset($_GET['search']) ? $this->conn->real_escape_string(trim($_GET['search'])) : '';
    $date_from = isset($_GET['date_from']) ? $this->conn->real_escape_string($_GET['date_from']) : '';
    $date_to = isset($_GET['date_to']) ? $this->conn->real_escape_string($_GET['date_to']) : '';

    $sql = "SELECT id, customer_code, name, email, phone, company_name, request, user_status, created_at 
            FROM {$this->table} WHERE 1=1";

    // إذا تم الضغط على زر البحث (وجود قيمة في search)
    if ($search !== '') {
        $sql .= " AND (name LIKE '%$search%' 
                    OR phone LIKE '%$search%' 
                    OR request LIKE '%$search%')";
        // ملاحظة: هنا تجاهلنا شروط التاريخ تماماً بناءً على طلبك
    } 
    // إذا لم يكن هناك بحث، نطبق الفلترة بالتاريخ
    else {
        if ($date_from !== '') {
            $sql .= " AND DATE(created_at) >= '$date_from'";
        }
        if ($date_to !== '') {
            $sql .= " AND DATE(created_at) <= '$date_to'";
        }
    }

    $sql .= " ORDER BY created_at DESC";

    $result = $this->conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
            (customer_code, name, email,
                     phone,company_name,company_reg_no,C_tax_number_declare,C_tax_number_date, 
                     C_identity_number,C_identity_declare,C_identity_date,
                     company_address,role, request,user_status, created_at)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'sssssssssssssss',
            $data['customer_code'],
            $data['name'],
            $data['email'],
            $data['phone'],
          $data['company_name'],
            $data['company_reg_no'],
            $data['C_tax_number_declare'],
            $data['C_tax_number_date'],
            // $data['P_tax_number_declare'],
            // $data['P_tax_number_date'],
            // $data['Customer_actor'],
            $data['C_identity_number'],
             $data['C_identity_declare'],
             $data['C_identity_date'],
            // $data['C_address'],
            // $data['C_phone'],
            
            $data['company_address'],
           // $data['country'],
            $data['role'],
            $data['request'],
          //  $data['employees'],
            $data['user_status']
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
            SET name=?, email=?, phone=?, 
            company_name=?, company_reg_no=?,C_tax_number_declare=?,C_tax_number_date=?, 
            C_identity_number=?,C_identity_declare=?,C_identity_date=?,company_address=?,
                country=?, role=?,request=?, employees=?, user_status=?, updated_at=NOW()
            WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'ssssssssssssssssi',
            $data['name'],
            $data['email'],
            $data['phone'],
             $data['company_name'],
            $data['company_reg_no'],
            $data['C_tax_number_declare'],
            $data['C_tax_number_date'],
            // $data['P_tax_number_declare'],
            // $data['P_tax_number_date'],
            // $data['Customer_actor'],
             $data['C_identity_number'],
             $data['C_identity_declare'],
            $data['C_identity_date'],
            // $data['C_address'],
            // $data['C_phone'],
           
            $data['company_address'],
            $data['country'],
            $data['role'],
            $data['request'],
            $data['employees'],
            $data['user_status'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

}