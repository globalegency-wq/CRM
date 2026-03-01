<?php
/* =======================================================================
 PHPCRM — Contracts Controller (Final Fix)
=======================================================================
*/

class ContractsController
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function index()
    {
        $query = "SELECT * FROM contracts ORDER BY id DESC";
        $contracts = $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
        require_once __DIR__ . '/../views/contracts/index.php';
    }

    public function create()
    {
        // جلب العملاء والخدمات للفورم
        $customers = $this->conn->query("SELECT id, name,phone FROM phpcrm_customers")->fetch_all(MYSQLI_ASSOC);
        $services = $this->conn->query("SELECT id, service_name, price FROM services")->fetch_all(MYSQLI_ASSOC);
        require_once __DIR__ . '/../views/contracts/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. البيانات الثابتة (تعدلها هنا مرة واحدة فقط)
            // $provider_name = "Global Agency شركة";
            // $manager_name = "أ/ علي ";
            // $tax_number = "123456789";
            // $law_name = "نظام الشركات اليمني";
            // $law_number = "م/50";
            $tax_number = "2021010320";
            $P_tax_number_declare = "من الأمانة";
            $P_tax_number_date = "2024-04-03";
            $Provider_phone = "777778170";


            // 2. جلب اليوم والتاريخ تلقائياً
            $contract_day = $this->getArabicDay(date('l'));
            $contract_date = date('Y-m-d');

            // 3. بيانات الفورم المتغيرة
            $customer_id = $_POST['customer_id'];
            $payment_method = $_POST['payment_method'];
            $total_amount = $_POST['total_amount'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // جلب اسم العميل ورقم سجله من جدول phpcrm_customers
            $stmt = $this->conn->prepare("SELECT name,phone, company_name,company_reg_no,C_tax_number_declare,C_tax_number_date, 
            C_identity_number,C_identity_declare,C_identity_date,company_address FROM phpcrm_customers WHERE id = ?");
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $cust = $stmt->get_result()->fetch_assoc();

            // 4. الحفظ في جدول العقود
            $sql = "INSERT INTO contracts (customer_id, customer_name, customer_tax_number,C_tax_number_declare,C_tax_number_date, 
            C_identity_number,C_identity_declare,C_identity_date,provider_tax_number,P_tax_number_declare,
            P_tax_number_date, Provider_phone,phone,company_name,company_address,contract_date, payment_method, total_amount, start_date, end_date) 
                    VALUES (?, ?, ?, ?, ?, ?,?,?,?,?,? ,?,?, ?,?,?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "isssssssssssssssssss",
                $customer_id,
                $cust['name'],
                $cust['company_reg_no'],
                $cust['C_tax_number_declare'],
                $cust['C_tax_number_date'],
                $cust['C_identity_number'],
                $cust['C_identity_declare'],
                $cust['C_identity_date'],
                $tax_number,
                $P_tax_number_declare,
                $P_tax_number_date,
                $Provider_phone,
                $cust['phone'],
                $cust['company_name'],
                $cust['company_address'],
                $contract_date,
                $payment_method,
                $total_amount,
                $start_date,
                $end_date,

            );

            if ($stmt->execute()) {
                $contract_id = $this->conn->insert_id;

                // 5. حفظ الخدمات في جدول contract_items للطباعة
                if (!empty($_POST['services'])) {
                    foreach ($_POST['services'] as $service_id) {
                        // 1. جلب بيانات الخدمة الحالية من جدول الخدمات
                        // تأكد أن اسم الجدول 'services' أو 'phpcrm_services' حسب ما لديك
                        $s_query = "SELECT service_name, price FROM services WHERE id = ?";
                        $s_stmt = $this->conn->prepare($s_query);
                        $s_stmt->bind_param("i", $service_id);
                        $s_stmt->execute();
                        $service_data = $s_stmt->get_result()->fetch_assoc();

                        if ($service_data) {
                            // 2. حفظ البيانات في جدول contract_items
                            // الحروف "iids" تعني: integer, integer, double (للسعر), string (للاسم) 
                            // أو "iiss" حسب نوع الحقول عندك. الأفضل استخدام "iiss" إذا كان السعر يُخزن كنص
                            $item_sql = "INSERT INTO contract_items (contract_id, service_id, service_name_at_save, service_price_at_save) 
                             VALUES (?, ?, ?, ?)";
                            $i_stmt = $this->conn->prepare($item_sql);

                            // ترتيب المتغيرات: id العقد، id الخدمة، اسم الخدمة، سعر الخدمة
                            $i_stmt->bind_param(
                                "iiss",
                                $contract_id,
                                $service_id,
                                $service_data['service_name'],
                                $service_data['price']
                            );
                            $i_stmt->execute();
                        }
                    }
                }




                header("Location: contracts.php?msg=success");
            } else {
                echo "خطأ: " . $this->conn->error;
            }
            exit();
        }
    }

    // الدالة التي كانت تسبب الخطأ (يجب وجودها داخل الكلاس)
    private function getArabicDay($day)
    {
        $days = [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة'
        ];
        return $days[$day] ?? $day;
    }
    public function edit()
    {
        $id = $_GET['id'] ?? 0;

        // جلب بيانات العقد الأساسية
        $stmt = $this->conn->prepare("SELECT * FROM contracts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $contract = $stmt->get_result()->fetch_assoc();

        if (!$contract) {
            die("العقد غير موجود");
        }

        // جلب الخدمات المتاحة والخدمات المختارة سابقاً لهذا العقد
        $customers = $this->conn->query("SELECT id, name FROM phpcrm_customers")->fetch_all(MYSQLI_ASSOC);
        $services = $this->conn->query("SELECT * FROM services")->fetch_all(MYSQLI_ASSOC);

        // جلب معرفات الخدمات المرتبطة بهذا العقد فقط
        $selected_items = [];
        $res = $this->conn->query("SELECT service_id FROM contract_items WHERE contract_id = $id");
        while ($row = $res->fetch_assoc()) {
            $selected_items[] = $row['service_id'];
        }

        include_once __DIR__ . '/../views/contracts/edit.php';
    }
    public function update()
    {
        $id = $_POST['id'];
        // ... جلب باقي بيانات الـ POST مثل الإجمالي والتواريخ ...

        $sql = "UPDATE contracts SET customer_id=?, total_amount=?, payment_method=?, start_date=?, end_date=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idsssi", $_POST['customer_id'], $_POST['total_amount'], $_POST['payment_method'], $_POST['start_date'], $_POST['end_date'], $id);

        if ($stmt->execute()) {
            // تحديث الخدمات: نقوم بحذف القديم وإضافة الجديد لضمان الدقة
            $this->conn->query("DELETE FROM contract_items WHERE contract_id = $id");

            if (!empty($_POST['services'])) {
                foreach ($_POST['services'] as $s_id) {
                    // جلب بيانات الخدمة من جدول الخدمات
                    $s_data = $this->conn->query("SELECT service_name, price FROM services WHERE id = $s_id")->fetch_assoc();

                    $i_sql = "INSERT INTO contract_items (contract_id, service_id, service_name_at_save, service_price_at_save) VALUES (?, ?, ?, ?)";
                    $i_stmt = $this->conn->prepare($i_sql);
                    $i_stmt->bind_param("iiss", $id, $s_id, $s_data['service_name'], $s_data['price']);
                    $i_stmt->execute();
                }
            }
            header("Location: contracts.php?msg=updated");
            exit();
        }
    }

    public function delete()
    {
        // جلب المعرف من الرابط (URL)
        $id = $_GET['id'] ?? 0;

        if ($id > 0) {
            // 1. حذف الخدمات المرتبطة بهذا العقد من جدول contract_items أولاً
            $delete_items = $this->conn->prepare("DELETE FROM contract_items WHERE contract_id = ?");
            $delete_items->bind_param("i", $id);
            $delete_items->execute();

            // 2. حذف العقد نفسه من جدول contracts
            $delete_contract = $this->conn->prepare("DELETE FROM contracts WHERE id = ?");
            $delete_contract->bind_param("i", $id);

            if ($delete_contract->execute()) {
                // إعادة التوجيه لصفحة العقود مع رسالة نجاح
                header("Location: contracts.php?msg=deleted");
                exit();
            } else {
                die("حدث خطأ أثناء محاولة حذف العقد.");
            }
        }
    }
    // public function print()
    // {
    //     $id = $_GET['id'] ?? 0;

    //     // 1. جلب بيانات العقد والعميل
    //     $sql = "SELECT c.*, cust.phone,cust.company_reg_no,cust.C_tax_number_declare,cust.C_tax_number_date, 
    //         cust.C_identity_number,cust.C_identity_declare,cust.C_identity_date,cust.company_address FROM contracts c 
    //         JOIN phpcrm_customers cust ON c.customer_id = cust.id WHERE c.id = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     $contract = $stmt->get_result()->fetch_assoc();

    //     // 2. جلب الخدمات
    //     $items_query = "SELECT service_name_at_save FROM contract_items WHERE contract_id = ?";
    //     $i_stmt = $this->conn->prepare($items_query);
    //     $i_stmt->bind_param("i", $id);
    //     $i_stmt->execute();
    //     $res = $i_stmt->get_result();
    //     $services = [];
    //     while ($row = $res->fetch_assoc()) {
    //         $services[] = $row['service_name_at_save'];
    //     }
    //     $services_list = implode('، ', $services);
    //     $customer_filename = $contract['customer_name'] . "_" . $contract['id'];

    //     // 3. الهيدر الخاص بالوورد
    //     header("Content-type: application/vnd.ms-word");
    //     header("Content-Disposition: attachment;Filename=" . $customer_filename . ".doc");
    //     header("Pragma: no-cache");
    //     header("Expires: 0");

    //     echo "
    // <html dir='rtl' lang='ar'>
    // <head>
    //     <meta charset='utf-8'>
    //     <style>
    //         body { font-family: 'Arial', sans-serif; padding: 50px; line-height: 1.6; }
    //         .title { text-align: center; font-size: 20pt; font-weight: bold; margin-bottom: 30px; }
    //         .section-title { font-weight: bold; font-size: 14pt; margin-top: 20px; display: block; }
    //         .content-text { font-size: 13pt; margin-bottom: 15px; text-align: justify; }
    //         .signature-table { width: 100%; margin-top: 60px; border: none; }
    //     </style>
    // </head>
    // <body>
    //     <div class='title'>عقد تقديم خدمات تسويق</div>

    //     <p class='content-text'>تم بعون الله تعالى في يوم <b>{$contract['contract_day']}</b> الموافق <b>{$contract['contract_date']}</b> إبرام هذا العقد بين كلٍ من:</p>

    //     <p class='content-text'><b>أولاً: شركة {$contract['provider_company_name']}</b>، سجل تجاري رقم {$contract['provider_tax_number']} ويمثلها السيد/ <b>{$contract['manager_name']}</b> ويشار إليها لاحقاً بـ \"الطرف الأول\".</p>

    //     <p class='content-text'><b>ثانياً: شركة {$contract['customer_name']}</b>، سجل تجاري رقم <b>{$contract['company_reg_no']}</b> ويشار إليها لاحقاً بـ \"الطرف الثاني\".</p>

    //     <div class='section-title'>المادة الأولى: موضوع العقد</div>
    //     <p class='content-text'>يقوم الطرف الأول بتقديم خدمة: <b>{$services_list}</b></p>

    //     <div class='section-title'>المادة الثانية: مدة العقد</div>
    //     <p class='content-text'>مدة هذا العقد شهر واحد تبدأ من تاريخ <b>{$contract['start_date']}</b> وتنتهي في تاريخ <b>{$contract['end_date']}</b>، ويجوز تجديده باتفاق كتابي بين الطرفين.</p>

    //     <div class='section-title'>المادة الثالثة: المقابل المالي</div>
    //     <p class='content-text'>اتفق الطرفان على أن يكون المقابل المالي للخدمات مبلغ وقدره <b>" . number_format($contract['total_amount'], 2) . "</b> دولار، يُدفع <b>{$contract['payment_method']}</b>.</p>

    //     <div class='section-title'>المادة الثامنة: القانون والاختصاص</div>
    //     <p class='content-text'>يخضع هذا العقد للأنظمة المعمول بها في <b>{$contract['law_name']}</b> رقم <b>{$contract['law_number']}</b>، ويكون الاختصاص القضائي للمحاكم المختصة.</p>

    //     <p class='content-text' style='margin-top: 40px;'>حرر هذا العقد من نسختين أصليتين بيد كل طرف نسخة للعمل بموجبها.</p>

    //     <table class='signature-table'>
    //         <tr>
    //             <td style='width: 50%; text-align: right; vertical-align: top;'>
    //                 <b>الطرف الأول:</b><br><br>
    //                 الاسم: ...........................<br>
    //                 التوقيع: ...........................
    //             </td>
    //             <td style='width: 50%; text-align: left; vertical-align: top;'>
    //                 <b>الطرف الثاني:</b><br><br>
    //                 الاسم: ...........................<br>
    //                 التوقيع: ...........................
    //             </td>
    //         </tr>
    //     </table>
    // </body>
    // </html>";
    //     exit();
    // }

    public function print()
    {
        $id = $_GET['id'] ?? 0;
        $sql = "SELECT c.*, cust.phone,cust.company_name,cust.company_reg_no,cust.C_tax_number_declare,cust.C_tax_number_date, 
             cust.C_identity_number,cust.C_identity_declare,cust.C_identity_date,cust.company_address FROM contracts c 
            JOIN phpcrm_customers cust ON c.customer_id = cust.id WHERE c.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $contract = $stmt->get_result()->fetch_assoc();

        // 2. مسار ملف القالب
        $templateFile = $_SERVER['DOCUMENT_ROOT'] . '/PHPCRM-main/public/media/Contract.docx';
        $outputFile = "Contract_" . $contract['customer_name'] . ".docx";

        // 3. قراءة ملف الوورد ومعالجته (طريقة متقدمة للحفاظ على التنسيق)
        if (!file_exists($templateFile)) {
            die("ملف القالب غير موجود في المسار: " . $templateFile);
        }

        // إنشاء ملف مؤقت للعمل عليه
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        copy($templateFile, $tempFile);
$items_query = "SELECT service_name_at_save FROM contract_items WHERE contract_id = ?";
         $i_stmt = $this->conn->prepare($items_query);
       $i_stmt->bind_param("i", $id);
        $i_stmt->execute();
       $res = $i_stmt->get_result();
         $services = [];
    while ($row = $res->fetch_assoc()) {
            $services[] = $row['service_name_at_save'];
        }
   $services_list = implode('، ', $services);
        $zip = new ZipArchive();
        if ($zip->open($tempFile) === TRUE) {
            $xmlContent = $zip->getFromName('word/document.xml');

            // قائمة الاستبدالات
            $replacements = [
                'CD' => $contract['contract_date'],
                'TN' => $contract['provider_tax_number'],
                'PTDA' => $contract['P_tax_number_declare'],
                'PTD' => $contract['P_tax_number_date'],
                'PPH' => $contract['Provider_phone'],
                'CON'=>$contract['company_name'],
                'CRN' => $contract['company_reg_no'],
                'CTDA' => $contract['C_tax_number_declare'],
                'CTD' => $contract['C_tax_number_date'],
                'CN' => $contract['customer_name'], 
                'CIN' => $contract['C_identity_number'],
                'CIDA' => $contract['C_identity_declare'],
                'CID' => $contract['C_identity_date'],
                'CA' => $contract['company_address'],
                'PH' => $contract['phone'],
                'SL'=>$services_list,
                'SD' => $contract['start_date'],
                'ED' => $contract['end_date'],
                'TA'=> number_format($contract['total_amount'], 2),
                
            ];

            // تنفيذ الاستبدال في محتوى XML
            foreach ($replacements as $search => $replace) {
                $xmlContent = str_replace($search, htmlspecialchars($replace), $xmlContent);
            }

            $zip->addFromString('word/document.xml', $xmlContent);
            $zip->close();

            // 4. إرسال الملف الناتج للمتصفح
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="' . $outputFile . '"');
            header('Content-Length: ' . filesize($tempFile));
            readfile($tempFile);
            unlink($tempFile); // حذف الملف المؤقت
            exit();
        } else {
            die("فشل في فتح ملف الوورد.");
        }
    }
}