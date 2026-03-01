<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">
        <?php include_once __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">
            <h2 class="mb-4">تعديل العقد رقم: <?= htmlspecialchars($contract['id']) ?></h2>

            <form method="POST" action="contracts.php?a=update">
                <input type="hidden" name="id" value="<?= $contract['id'] ?>">

                <div class="card mb-4">
                    <div class="card-header bg-light text-primary"><strong>بيانات العميل</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">العميل</label>
                                <select name="customer_id" id="customer_select" class="form-select" required>
                                    <?php if (!empty($customers)): ?>
                                    <?php foreach($customers as $customer): ?>
                                    <option value="<?= $customer['id'] ?>"
                                        <?= ($customer['id'] == $contract['customer_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($customer['name'] ?? '') ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light text-primary"><strong>الخدمات المطلوبة</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr class="table-light">
                                        <th width="50">اختيار</th>
                                        <th>اسم الخدمة</th>
                                        <th>السعر</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($services)): ?>
                                    <?php foreach($services as $service): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="services[]" value="<?= $service['id'] ?>"
                                                data-price="<?= $service['price'] ?>" class="service-check"
                                                onchange="calculateTotal()"
                                                <?php // تحديد الخدمات المختارة سابقاً بناءً على مصفوفة selected_items من الـ Controller
                               if(isset($selected_items) && in_array($service['id'], $selected_items)) echo 'checked'; ?>>
                                        </td>
                                        <td><?= htmlspecialchars($service['service_name']) ?></td>
                                        <td><?= number_format($service['price'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-4 mt-3">
                                <label class="form-label fw-bold">إجمالي مبلغ العقد</label>
                                <input type="number" name="total_amount" id="total_amount"
                                    class="form-control fw-bold text-primary" step="0.01"
                                    value="<?= $contract['total_amount'] ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light text-primary"><strong>تفاصيل إضافية</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">طريقة الدفع</label>
                                <select name="payment_method" class="form-select">
                                    <option value="كاش" <?= ($contract['payment_method'] == 'كاش') ? 'selected' : '' ?>>
                                        كاش</option>
                                    <option value="تحويل بنكي"
                                        <?= ($contract['payment_method'] == 'تحويل بنكي') ? 'selected' : '' ?>>تحويل
                                        بنكي</option>
                                    <option value="أقساط"
                                        <?= ($contract['payment_method'] == 'أقساط') ? 'selected' : '' ?>>
                                        أقساط
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاريخ البداية</label>
                                <input type="date" name="start_date" class="form-control" required
                                    value="<?= $contract['start_date'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاريخ النهاية</label>
                                <input type="date" name="end_date" class="form-control" required
                                    value="<?= $contract['end_date'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <button type="submit" class="btn btn-primary px-5">تحديث البيانات</button>
                    <a href="contracts.php" class="btn btn-secondary ms-2">إلغاء</a>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
// وظيفة حساب المجموع عند تغيير الاختيارات
function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.service-check:checked').forEach(cb => {
        total += parseFloat(cb.getAttribute('data-price'));
    });
    document.getElementById('total_amount').value = total.toFixed(2);
}

// تشغيل الحساب لمرة واحدة عند تحميل الصفحة للتأكد من دقة المبلغ الظاهر
window.onload = calculateTotal;
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>