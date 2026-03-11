<?php include __DIR__ . '/../../includes/header.php';


?>
<style>
/* تنسيقات لإصلاح قص الصفحة وعرض الأعمدة */
.content-area {
    overflow-x: hidden;
    /* لمنع التمرير الأفقي خارج الإطار الرئيسي */
}

.table-responsive-custom {
    width: 100%;
    overflow-x: auto;
    /* يسمح بالتمرير داخل الجدول فقط إذا لزم الأمر */
    background: #fff;
    border-radius: 8px;
}

#customers_table {
    table-layout: fixed;
    /* لتثبيت العرض وتوزيعه بنسب مئوية */
    width: 100%;
    min-width: 1000px;
    /* لضمان عدم تداخل البيانات في الشاشات الصغيرة */
}

/* تحديد عرض الأعمدة بنسب مئوية */
#customers_table th:nth-child(1) {
    width: 5%;
}

/* الرقم */
#customers_table th:nth-child(2) {
    width: 15%;
}

/* الاسم */
#customers_table th:nth-child(3) {
    width: 12%;
}

/* الهاتف */
#customers_table th:nth-child(4) {
    width: 35%;
}

/* الاستفسار - أكبر عرض */
#customers_table th:nth-child(5) {
    width: 8%;
}

/* الحالة */
#customers_table th:nth-child(6) {
    width: 12%;
}

/* تاريخ الاضافة */
#customers_table th:nth-child(7) {
    width: 13%;
}

/* الاجراءات */

.text-wrap-custom {
    white-space: normal;
    word-wrap: break-word;
    font-size: 0.9rem;
    line-height: 1.4;
}

.request-cell {
    word-wrap: break-word;
    /* كسر الكلمات الطويلة */
    overflow-wrap: break-word;
    /* دعم إضافي للمتصفحات الحديثة */
    white-space: normal;
    /* السماح بالالتفاف الطبيعي */
    max-width: 400px;
    /* يمكنك ضبط هذا الرقم حسب الحاجة */
    text-align: right;
}

/* تصغير عمود الإجراءات ليعطي مساحة أكبر للاستفسار */
.action-col {
    width: 120px !important;
}
</style>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <!-- <main class="col-md-10 ms-sm-auto content-area">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <div>
                    <button onclick="exportTableToExcel('customers_table')" class="btn btn-success me-2">تصدير إلى
                        إكسل</button>
                    <a href="customers.php?a=create" class="btn btn-primary">اضافة عميل جديد</a>
                </div>
            </div> -->
        <main class="col-md-10 ms-sm-auto content-area">
            <div class="card mt-4 mb-4 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="customers.php" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">بحث شامل (اسم، هاتف، استفسار):</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="اكتب ما تريد البحث عنه..."
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        </div>
                        <div class="col-md-1 d-flex gap-2">
                            <button type="submit" class="btn btn-secondary flex-grow-1">بحث</button>

                        </div>
                    </form>
                    <form method="GET" action="customers.php" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">من تاريخ:</label>
                            <input type="date" name="date_from" id="date_from" class="form-control"
                                value="<?= isset($_GET['date_from']) ? $_GET['date_from'] : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">إلى تاريخ:</label>
                            <input type="date" name="date_to" id="date_to" class="form-control"
                                value="<?= isset($_GET['date_to']) ? $_GET['date_to'] : '' ?>">
                        </div>

                        <div class="col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-secondary flex-grow-1"> فلترة</button>
                            <a href="customers.php" class="btn btn-outline-danger">مسح الفلتر</a>

                            <button type="button" onclick="exportTableToExcel('customers_table')"
                                class="btn btn-success flex-grow-1">تصدير لإكسل</button>
                            <a href="customers.php?a=create" class="btn btn-primary flex-grow-1">اضافة عميل</a>
                        </div>
                    </form>
                </div>
            </div>




            <?php if ($successMsg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
            <?php endif; ?>

            <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
            <?php endif; ?>


            <table id="customers_table" class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>الرقم</th>
                        <th>الاسم</th>
                        <!-- <th style="width: 90px;">الايميل</th> -->
                        <th>الهاتف</th>
                        <!-- <th>الشركة</th> -->
                        <!-- <th>المنصب</th> -->
                        <th style="width:200px;">الاستفسار</th>
                        <th>الحالة</th>
                        <th>تاريخ الاضافة</th>
                        <th style="width: 60px;">الاجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                    <?php
                            // إعداد رسالة الواتساب الذكية داخل الحلقة لكل عميل
                            $message = " مرحباً معاكم جلوبال اجنسي، حابين نتأكد إذا لا زلت مهتم بالخدمة أو تحتاج اي توضيح";
                            $wa_link = "https://wa.me/" . preg_replace('/[^0-9]/', '', $user['phone']) . "?text=" . urlencode($message);
                            ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <!-- <td style="width: 90px;"><?= htmlspecialchars($user['email']) ?></td> -->
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <!-- <td><?= htmlspecialchars($user['company_name']) ?></td> -->
                        <!-- <td><?= htmlspecialchars($user['role']) ?></td> -->
                        <td class="request-cell"><?= htmlspecialchars($user['request']) ?></td>

                        <td><?php
                                $statusClass = 'secondary';
                                switch ($user['user_status']) {
                                    case 'نشط':
                                        $statusClass = 'success';
                                        break;
                                    case 'خامل':
                                        $statusClass = 'danger';
                                        break;
                                         case 'محتمل':
                                        $statusClass = 'warning';
                                        break;
                                }
                                ?>
                            <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($user['user_status']) ?></span>
                        </td>

                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td style="width: 60px;">
                            <a href="customers.php?a=edit&id=<?= urlencode($user['id']) ?>"
                                class="btn btn-sm btn-primary">تعديل</a>

                            <a href="customers.php?a=delete&id=<?= urlencode($user['id']) ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('هل تريد بالتأكيد من حذف هذا العميل?');">حذف</a>

                            <a style="margin-top: 10px;=" href="<?= $wa_link ?>" target="_blank"
                                class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i> متابعة الآن
                            </a>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">لا يوجد عملاء.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
function exportTableToExcel(tableID, filename = 'قائمة_العملاء.xlsx') {
    var table = document.getElementById(tableID);

    // التحقق من وجود بيانات (باستثناء الهيدر)
    if (table.rows.length <= 2 && table.innerText.includes('لا يوجد عملاء')) {
        alert('لا توجد بيانات لتصديرها!');
        return;
    }

    var wb = XLSX.utils.table_to_book(table, {
        sheet: "العملاء المفلترين",
        raw: true
    });

    XLSX.writeFile(wb, filename);
}
// function exportTableToExcel(tableID, filename = 'قائمة_العملاء.xlsx') {
//     // الحصول على الجدول
//     var table = document.getElementById(tableID);

//     // إنشاء نسخة من الجدول في الذاكرة لتعديلها قبل التصدير
//     // (هذا اختياري إذا كنت تريد حذف عمود "الاجراءات" من ملف الإكسل)
//     var wb = XLSX.utils.table_to_book(table, {
//         sheet: "العملاء",
//         raw: true
//     });

//     // تحميل الملف
//     XLSX.writeFile(wb, filename);
// }
// 
</script>