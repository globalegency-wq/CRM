<?php include __DIR__ . '/../../includes/header.php'; ?>

<style>
/* تنسيق رأس الجدول ليتطابق مع لون هيدر الصفحة الأزرق */
.table-custom-header {
    background-color: #007bff !important;

}

th {

    text-align: center;
}

te .tbadge-service {
    background-color: #e3f2fd;
    color: #0d47a1;
    border: 1px solid #bbdefb;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.85rem;
}
</style>

<div class="container-fluid" style="direction: rtl">
    <div class="row">
        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                تم حذف العقد والخدمات المرتبطة به بنجاح.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">قائمة العقود</h2>
                <a href="contracts.php?a=create" class="btn btn-primary">إضافة عقد جديد</a>
            </div>

            <div class="table-responsive">

                <table class="table-custom-header table table-bordered table-hover align-middle ">
                    <thead>
                        <tr class="table-custom-header">
                            <th>ID</th>
                            <th>العميل</th>
                            <th>الخدمات المختارة</th>
                            <th>الإجمالي</th>
                            <th>طريقة الدفع</th>
                            <th>تاريخ بدايةالعقد</th>
                            <th>تاريخ نهايةالعقد</th>

                            <th width="180">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($contracts)): ?>
                        <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><?= htmlspecialchars($contract['id']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($contract['customer_name']) ?></td>

                            <td>
                                <?php
                                        $c_id = $contract['id'];
                                        // التأكد من جلب الحقل النصي 'service_name_at_save'
                                        $items = $this->conn->query("SELECT service_name_at_save FROM contract_items WHERE contract_id = $c_id");
                                        if ($items && $items->num_rows > 0) {
                                            while ($row = $items->fetch_assoc()) {
                                                echo '<span class="badge-service me-1 d-inline-block mb-1">' . htmlspecialchars($row['service_name_at_save']) . '</span>';
                                            }
                                        } else {
                                            echo '<small class="text-muted">لا توجد خدمات</small>';
                                        }
                                        ?>
                            </td>

                            <td class="text-primary fw-bold"><?= number_format($contract['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($contract['payment_method']) ?></td>

                            <td><?= htmlspecialchars($contract['start_date']) ?></td>
                            <td><?= htmlspecialchars($contract['end_date']) ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="contracts.php?a=print&id=<?= $contract['id'] ?>"
                                        class="btn btn-sm btn-success">
                                        Word
                                    </a>

                                    <a href="contracts.php?a=edit&id=<?= $contract['id'] ?>"
                                        class="btn btn-sm btn-warning text-white">
                                        تعديل
                                    </a>

                                    <a href="contracts.php?a=delete&id=<?= $contract['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('هل أنت متأكد من حذف العقد؟');">
                                        حذف
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">لا توجد عقود مسجلة.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>