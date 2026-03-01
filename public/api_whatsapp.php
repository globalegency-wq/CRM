<?php
// api_whatsapp.php

// 1. الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "phpcrm_free");

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : 'عميل واتساب';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $incomingMessage = isset($_POST['message']) ? trim($_POST['message']) : '';
    $status = "محتمل";

    if (empty($phone)) { exit("رقم مفقود"); }

    // --- فلتر استبعاد رسالة الترحيب الخاصة بك ---
    $welcomeMessagePart = "سعدنا بتواصلك مع BLUESTAR PHARMA"; // نص مميز من رسالتك لاستبعاده
    
    // إذا كانت الرسالة تحتوي على نص الترحيب الخاص بك، اجعل الاستفسار فارغاً أو تجاهله
    if (mb_stripos($incomingMessage, $welcomeMessagePart) !== false) {
        $incomingMessage = ""; // تفريغ الرسالة لأنها رسالة ترحيب من طرفنا
    }

    // --- منطق الكلمات المفتاحية ---
    $keywords = ['السعر', 'كم', 'ايش', 'اشتي', 'هل', 'بكم', 'معلومات', 'اين', 'خدمات', 'ماهي', 'وقت', 'تفاصيل', 'موقعكم', 'حجز'];
    
    $foundRequest = ""; 

    // 1. البحث عن الكلمات المفتاحية أولاً
    foreach ($keywords as $word) {
        if (!empty($incomingMessage) && mb_stripos($incomingMessage, $word) !== false) {
            $foundRequest = $incomingMessage; 
            break; 
        }
    }

    // 2. إذا لم تكن من الكلمات المفتاحية وليست رسالة الترحيب المستبعدة، احفظها كأول رسالة
    if (empty($foundRequest) && !empty($incomingMessage)) {
        $foundRequest = $incomingMessage;
    }

    // التحقق من عدم تكرار الرقم قبل الحفظ
    $check_stmt = $conn->prepare("SELECT id FROM phpcrm_customers WHERE phone = ?");
    $check_stmt->bind_param("s", $phone);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows == 0) {
        // لا نحفظ العميل إلا إذا كان لديه استفسار حقيقي (ليس رسالة الترحيب)
        if (!empty($foundRequest)) {
            $sql = "INSERT INTO phpcrm_customers (name, phone, user_status, request, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $phone, $status, $foundRequest);
            $stmt->execute();
            echo "✅ تم حفظ العميل الجديد باستفساره: " . $foundRequest;
            $stmt->close();
        } else {
            echo "⏭️ تم تخطي رسالة الترحيب التلقائية.";
        }
    } else {
        echo "العميل موجود مسبقاً";
    }
    $check_stmt->close();
}
$conn->close();
?>
<!-- 
// api_whatsapp.php

// 1. الاتصال بقاعدة البيانات
// $conn = new mysqli("localhost", "root", "", "phpcrm_free");

// if ($conn->connect_error) {
//     die("فشل الاتصال: " . $conn->connect_error);
// }

// $conn->set_charset("utf8mb4");

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $name = isset($_POST['name']) ? trim($_POST['name']) : 'عميل واتساب';
//     $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
//     $incomingMessage = isset($_POST['message']) ? trim($_POST['message']) : '';
//     $status = "محتمل";

//     if (empty($phone)) {
//         echo "رقم الهاتف مفقود";
//         exit;
//     }

//     // --- المنطق الجديد: فحص الكلمات المفتاحية أولاً ---
//     $keywords = [
//         'السعر',
//         'كم',
//         'ايش',
//         'اشتي',
//         'هل',
//         'بكم',
//         'معلومات',
//         'اين',
//         'خدمات',
//         'ماهي',
//         'وقت',
//         'تفاصيل',
//         'موقعكم',
//         'حجز',
//         'مكانكم'
//     ];

//     $foundRequest = "";

//     // الخطوة 1: ابحث عن الكلمات المفتاحية في الرسالة
//     foreach ($keywords as $word) {
//         if (mb_stripos($incomingMessage, $word) !== false) {
//             $foundRequest = $incomingMessage; // إذا وجدنا كلمة، نحفظ الرسالة كاستفسار
//             break;
//         }
//     }

//     // الخطوة 2: إذا لم نجد أي كلمة مفتاحية، احفظ أول رسالة للعميل
//     if (empty($foundRequest)) {
//         $foundRequest = !empty($incomingMessage) ? $incomingMessage : "تواصل عبر واتساب";
//     }

//     // 2. التحقق من عدم تكرار الرقم
//     $checkSql = "SELECT id FROM phpcrm_customers WHERE phone = ?";
//     $check_stmt = $conn->prepare($checkSql);
//     $check_stmt->bind_param("s", $phone);
//     $check_stmt->execute();
//     $result = $check_stmt->get_result();

//     if ($result->num_rows > 0) {
//         echo "العميل موجود مسبقاً في النظام";
//     } else {
//         // 3. إدراج بيانات العميل الجديد
//         $sql = "INSERT INTO phpcrm_customers (name, phone, user_status, request, created_at) 
//                 VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $phone, $status, $foundRequest);

        if ($stmt->execute()) {
            echo "✅ تم الحفظ. الاستفسار: " . $foundRequest;
        } else {
            echo "❌ خطأ في الحفظ: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
$conn->close(); 



// // api_whatsapp.php

// // 1. الاتصال بقاعدة البيانات
// $conn = new mysqli("localhost", "root", "", "phpcrm_free");

// // التحقق من الاتصال
// if ($conn->connect_error) {
// die("فشل الاتصال: " . $conn->connect_error);
// }

// // ضبط الترميز لدعم اللغة العربية
// $conn->set_charset("utf8mb4");

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// // استقبال البيانات من البوت
// $name = isset($_POST['name']) ? trim($_POST['name']) : 'عميل واتساب';
// $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
// $incomingMessage = isset($_POST['message']) ? trim($_POST['message']) : '';
// $status = "محتمل";

// if (empty($phone)) {
// echo "رقم الهاتف مفقود";
// exit;
// }

// // --- منطق الفحص والحفظ التلقائي ---
// $keywords = [
// 'السعر', 'كم', 'ايش', 'اشتي', 'هل', 'بكم', 'معلومات',
// 'اين', 'خدمات', 'ماهي', 'وقت', 'تفاصيل', 'موقعكم', 'حجز', 'مكانكم'
// ];

// // افتراضياً، سنعتبر الرسالة هي "الطلب/الاستفسار" سواء طابقت الكلمات أم لا
// // لضمان حفظ بيانات العميل من أول رسالة دائماً
// $foundRequest = $incomingMessage;

// // 2. التحقق من عدم تكرار الرقم في النظام
// $checkSql = "SELECT id FROM phpcrm_customers WHERE phone = ?";
// $check_stmt = $conn->prepare($checkSql);
// $check_stmt->bind_param("s", $phone);
// $check_stmt->execute();
// $result = $check_stmt->get_result();

// if ($result->num_rows > 0) {
// echo "العميل موجود مسبقاً في النظام";
// $check_stmt->close();
// } else {
// $check_stmt->close();

// // 3. إدراج البيانات (سيتم حفظ أول رسالة للعميل دائماً في عمود request)
// $sql = "INSERT INTO phpcrm_customers (name, phone, user_status, request, created_at)
// VALUES (?, ?, ?, ?, NOW())";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param("ssss", $name, $phone, $status, $foundRequest);

// if ($stmt->execute()) {
// echo "تم حفظ العميل الجديد بنجاح مع نص الرسالة الأولى";
// } else {
// echo "خطأ أثناء الحفظ: " . $stmt->error;
// }
// $stmt->close();
// }
// }

// $conn->close();

// api_whatsapp.php

// 1. الاتصال بقاعدة البيانات
// $conn = new mysqli("localhost", "root", "", "phpcrm_free");

// // التحقق من الاتصال
// if ($conn->connect_error) {
// die("فشل الاتصال: " . $conn->connect_error);
// }

// // ضبط الترميز لدعم اللغة العربية بشكل صحيح
// $conn->set_charset("utf8mb4");

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// // استقبال البيانات من البوت
// $name = isset($_POST['name']) ? trim($_POST['name']) : 'عميل واتساب';
// $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
// $incomingMessage = isset($_POST['message']) ? trim($_POST['message']) : '';
// $status = "محتمل"; // الحالة الافتراضية للعملاء القادمين من واتساب

// if (empty($phone)) {
// echo "رقم الهاتف مفقود";
// exit;
// }

// // --- منطق فحص الكلمات المفتاحية للاستفسار ---
// $keywords = [
// 'السعر', 'كم', 'ايش', 'اشتي', 'هل', 'بكم', 'معلومات',
// 'اين', 'خدمات', 'ماهي', 'وقت', 'تفاصيل', 'موقع', 'حجز', 'مكان'
// ];

// $foundRequest = ""; // القيمة الافتراضية للاستفسار

// // البحث عن الكلمات المفتاحية داخل الرسالة
// foreach ($keywords as $word) {
// if (mb_stripos($incomingMessage, $word) !== false) {
// // إذا وُجدت كلمة مفتاحية، يتم تخزين نص الرسالة بالكامل في الاستفسار
// $foundRequest = $incomingMessage;
// break;
// }
// }
// // -------------------------------------------

// // 2. التحقق من عدم تكرار الرقم في النظام
// $checkSql = "SELECT id FROM phpcrm_customers WHERE phone = ?";
// $check_stmt = $conn->prepare($checkSql);
// $check_stmt->bind_param("s", $phone);
// $check_stmt->execute();
// $result = $check_stmt->get_result();

// if ($result->num_rows > 0) {
// echo "العميل موجود مسبقاً في النظام";
// $check_stmt->close();
// } else {
// $check_stmt->close();

// // 3. إدراج البيانات في حال كان العميل جديداً
// // يتم حفظ الاسم، الهاتف، الحالة، ونص الرسالة (إذا كانت استفساراً)
// $sql = "INSERT INTO phpcrm_customers (name, phone, user_status, request, created_at)
// VALUES (?, ?, ?, ?, NOW())";

// $stmt = $conn->prepare($sql);

// // ربط المتغيرات (4 نصوص: ssss)
// $stmt->bind_param("ssss", $name, $phone, $status, $foundRequest);

// if ($stmt->execute()) {
// echo "تم حفظ العميل الجديد بنجاح مع الاستفسار";
// } else {
// echo "خطأ أثناء الحفظ: " . $stmt->error;
// }
// $stmt->close();
// }
// }

// $conn->close();




// save.php

// 1. الاتصال بقاعدة البيانات
// $conn = new mysqli("localhost", "root", "", "phpcrm_free");

// // التحقق من الاتصال
// if ($conn->connect_error) {
// die("فشل الاتصال: " . $conn->connect_error);
// }

// // ضبط الترميز لدعم اللغة العربية
// $conn->set_charset("utf8mb4");

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// $name = isset($_POST['name']) ? trim($_POST['name']) : 'عميل غير مسجل';
// $phone = isset($_POST['phone']) ? preg_replace('/\D/', '', $_POST['phone']) : '';
// $status = "محتمل";

// if (empty($phone)) {
// echo "رقم الهاتف مفقود";
// exit;
// }

// // 2. التحقق من عدم تكرار الرقم
// $checkSql = "SELECT id FROM phpcrm_customers WHERE phone = ?";
// $check_stmt = $conn->prepare($checkSql);
// $check_stmt->bind_param("s", $phone);
// $check_stmt->execute();
// $result = $check_stmt->get_result();

// if ($result->num_rows > 0) {
// echo "العميل موجود مسبقاً في النظام";
// // $check_stmt->close();
// } else {
// // $check_stmt->close();

// // 3. إدراج البيانات في حال كان العميل جديداً
// $sql = "INSERT INTO phpcrm_customers (name, phone, user_status) VALUES (?, ?, ?)";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sss", $name, $phone, $status);

// if ($stmt->execute()) {
// echo "✅ تم الحفظ بنجاح للعميل: $name";
// } else {
// echo "❌ خطأ في الإدخال: " . $stmt->error;
// }
// $stmt->close();
// }
// $check_stmt->close();
// } else {
// echo "طريقة إرسال غير صالحة";
// }

// $conn->close(); -->