<?php
/**
 * سكربت النسخ الاحتياطي لقاعدة بيانات CRM
 */

// --- الإعدادات الأساسية (عدلها حسب جهازك) ---
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'phpcrm_free';

// --- المسارات (عدلها حسب ترتيب مجلداتك) ---
$BACKUP_DIR = 'F:/CRMBackups/';                           // مجلد النسخ الاحتياطي في القرص F
$mysqldump_path = 'C:/xampp/mysql/bin/mysqldump.exe';    // مسار أداة mysqldump في XAMPP

// -------------------------------------------------

// 1. التأكد من وجود مجلد النسخ الاحتياطي، وإنشاءه إذا ما كان موجود
if (!file_exists($BACKUP_DIR)) {
    mkdir($BACKUP_DIR, 0777, true);
    echo "تم إنشاء مجلد النسخ الاحتياطي: $BACKUP_DIR\n";
}

// 2. تجهيز اسم الملف (مثال: phpcrm_free_backup_2026-02-23.sql)
$date = date('Y-m-d');
$filename = $DB_NAME . '_backup_' . $date . '.sql';
$full_path = $BACKUP_DIR . $filename;

// 3. بناء أمر التصدير بشكل صحيح
$command = "\"$mysqldump_path\" --user=$DB_USER --password=$DB_PASS --host=$DB_HOST $DB_NAME > \"$full_path\" 2>&1";

// 4. تنفيذ الأمر
$output = [];
$return_var = 0;
exec($command, $output, $return_var);

// 5. التحقق من النتيجة
if ($return_var === 0) {
    echo "✅ تم إنشاء النسخة الاحتياطية بنجاح:\n";
    echo "📁 المسار: $full_path\n";

    // 6. (اختياري) حذف النسخ الأقدم من 30 يوم
    $files = glob($BACKUP_DIR . '*.sql');
    $now = time();
    foreach ($files as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) > 30 * 24 * 60 * 60) { // 30 يوم
                unlink($file);
                echo "🗑️ تم حذف نسخة قديمة: " . basename($file) . "\n";
            }
        }
    }
} else {
    echo "❌ حدث خطأ أثناء إنشاء النسخة الاحتياطية!\n";
    echo "تفاصيل الخطأ:\n";
    print_r($output);

    // سجل الخطأ في ملف log
    $log = "[" . date('Y-m-d H:i:s') . "] فشل الباك أب: " . implode("\n", $output) . "\n";
    file_put_contents($BACKUP_DIR . 'error_log.txt', $log, FILE_APPEND);
}

// 7. إظهار حجم الملف إذا تم بنجاح
if (file_exists($full_path)) {
    $size = filesize($full_path);
    echo "📊 حجم الملف: " . round($size / 1024 / 1024, 2) . " MB\n";
}

?>