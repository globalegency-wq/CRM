<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">اضافة مهمة</h2>
                <a href="tasks.php" class="btn btn-secondary">الرجوع للقائمة</a>
            </div>

            <form method="post" action="tasks.php?a=store">

                <div class="mb-3">
                    <label class="form-label">اسم العميل *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">اختيار العميل</option>
                        <?php foreach ($customers as $cust): ?>
                        <option value="<?= $cust['id']; ?>">
                            <?= htmlspecialchars($cust['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">العنوان *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">تاريخ المهمة *</label>
                    <input type="datetime-local" value="<?php echo date('Y-m-d\TH:i');?>" name=" followup_date"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="معلقة">معلقة</option>
                        <option value="منجزة">منجزة</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">حفظ المهمة</button>

            </form>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>