<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">اضافة ملاحظة / نشاط</h2>
                <a href="notes.php" class="btn btn-secondary">الرجوع الى القائمة</a>
            </div>

            <form method="post" action="notes.php?a=store">

                <div class="mb-3">
                    <label class="form-label">اسم العميل *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value=""> اختار العميل</option>
                        <?php foreach ($customers as $cust): ?>
                        <option value="<?= $cust['id']; ?>">
                            <?= htmlspecialchars($cust['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">الملاحظة *</label>
                    <textarea name="note" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">حفظ الملاحظة</button>

            </form>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>