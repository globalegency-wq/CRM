<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">تعديل المهمة /المتابعة</h2>
                <a href="tasks.php" class="btn btn-secondary">الرجوع للقائمة</a>
            </div>

            <?php if (!$task): ?>
            <div class="alert alert-danger">لا يوجد مهام .</div>
            <?php else: ?>

            <form method="post" action="tasks.php?a=update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']); ?>">

                <div class="mb-3">
                    <label class="form-label">اسم العميل *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">اختار العميل</option>
                        <?php foreach ($customers as $cust): ?>
                        <option value="<?= $cust['id']; ?>"
                            <?= ($task['customer_id'] == $cust['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cust['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">العنوان *</label>
                    <input type="text" name="title" class="form-control"
                        value="<?= htmlspecialchars($task['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control"
                        rows="3"><?= htmlspecialchars($task['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">وقت وتاريخ المهمة *</label>
                    <input type="datetime-local" name="followup_date" class="form-control"
                        value="<?= str_replace(' ', 'T', htmlspecialchars($task['followup_date'])); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="معلقة" <?= $task['status'] === 'معلقة' ? 'selected' : ''; ?>>معلقة</option>
                        <option value="منجزة" <?= $task['status'] === 'منجزة' ? 'selected' : ''; ?>>منجزة
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">تحديث المهمة</button>

            </form>

            <?php endif; ?>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>