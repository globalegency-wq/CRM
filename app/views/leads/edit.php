<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">حفظ الصفقة</h2>
                <a href="leads.php" class="btn btn-secondary">الرجوع للقائمة</a>
            </div>

            <?php if (!$lead): ?>
            <div class="alert alert-danger">لا يوجد صفقات.</div>
            <?php else: ?>

            <form method="post" action="leads.php?a=update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($lead['id']); ?>">

                <div class="mb-3">
                    <label class="form-label">اسم الصفقة*</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($lead['name']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الايميل</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($lead['email']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control"
                        value="<?= htmlspecialchars($lead['phone']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الشركة</label>
                    <input type="text" name="company_name" class="form-control"
                        value="<?= htmlspecialchars($lead['company_name']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">مصدر الصفقة</label>
                    <input type="text" name="lead_source" class="form-control"
                        value="<?= htmlspecialchars($lead['lead_source']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <?php
            $statuses = ['جديدة', 'تم التواصل', 'متأهلة', 'مفقودة'];
            foreach ($statuses as $status):
            ?>
                        <option value="<?= $status; ?>" <?= $lead['status'] === $status ? 'selected' : ''; ?>>
                            <?= ucfirst($status); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">تحديث البيانات</button>

            </form>

            <?php endif; ?>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>