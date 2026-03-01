<?php include __DIR__ . '/../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
/* تنسيق يتناسب مع Bootstrap 5 */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}
</style>
<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">
            <h2 class="mb-4">إنشاء عقد جديد</h2>

            <form method="POST" action="contracts.php?a=store">

                <div class="card mb-4">
                    <div class="card-header bg-light"><strong>بيانات العميل</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">اختر العميل (يمكنك كتابة الاسم للبحث)</label>
                                <select name="customer_id" id="customer_select" class="form-select" required>
                                    <option value="" disabled selected>-- ابحث عن عميل بالاسم --</option>
                                    <?php if (!empty($customers)): ?>
                                    <?php foreach ($customers as $customer): ?>
                                    <option value="<?= htmlspecialchars($customer['id']) ?>">
                                        <?= htmlspecialchars($customer['name']) ?> -
                                        <?= htmlspecialchars($customer['phone'] ?? '') ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light"><strong>الخدمات المطلوبة</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">اختيار</th>
                                        <th>اسم الخدمة</th>
                                        <th>السعر</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($services)): ?>
                                    <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="services[]"
                                                value="<?= htmlspecialchars($service['id']) ?>"
                                                data-price="<?= htmlspecialchars($service['price']) ?>"
                                                class="service-check" onchange="calculateTotal()">
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
                                    class="form-control fw-bold text-primary" step="0.01" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light"><strong>تفاصيل العقد</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">طريقة الدفع</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="كاش">كاش</option>
                                    <option value="تحويل بنكي">تحويل بنكي</option>
                                    <option value="على دفعتين">على دفعتين</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاريخ البداية</label>
                                <input type="date" name="start_date" class="form-control" required
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاريخ النهاية</label>
                                <input type="date" name="end_date" value="<?php echo date('Y-m-d'); ?>"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5 text-center">
                    <button type="submit" class="btn btn-primary px-5">حفظ العقد</button>
                    <a href="contracts.php" class="btn btn-secondary ms-2">إلغاء</a>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
// حساب المجموع التلقائي للخدمات المختارة
function calculateTotal() {
    let total = 0;
    const checkboxes = document.querySelectorAll('.service-check:checked');
    checkboxes.forEach(cb => {
        total += parseFloat(cb.getAttribute('data-price'));
    });
    document.getElementById('total_amount').value = total.toFixed(2);
}

$(document).ready(function() {
    $('#customer_select').select2({
        placeholder: "اكتب اسم العميل للبحث...",
        allowClear: true,
        width: '100%',
        dir: "rtl", // لدعم الكتابة من اليمين لليمين
        language: {
            noResults: function() {
                return "لا توجد نتائج مطابقة";
            },
            searching: function() {
                return "جاري البحث...";
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>