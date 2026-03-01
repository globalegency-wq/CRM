<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">المهمة/المتابعة</h2>
                <a href="tasks.php?a=create" class="btn btn-primary">اضافة مهمة</a>
            </div>

            <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>العميل</th>
                            <th>العنوان</th>
                            <th>تاريخ المهمة</th>
                            <th>الحالة</th>
                            <th width="140">الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['id']); ?></td>
                            <td><?= htmlspecialchars($task['customer_name']); ?></td>
                            <td><?= htmlspecialchars($task['title']); ?></td>
                            <td><?= htmlspecialchars($task['followup_date']); ?></td>
                            <td><?= htmlspecialchars(ucfirst($task['status'])); ?></td>
                            <td>
                                <a href="tasks.php?a=edit&id=<?= $task['id']; ?>"
                                    class="btn btn-sm btn-warning">تعديل</a>
                                <a href="tasks.php?a=delete&id=<?= $task['id']; ?>"
                                    onclick="return confirm('هل تريد بالتأكيد حذف هذه المهمة?');"
                                    class="btn btn-sm btn-danger">حذف</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">لا يوجد مهام/متابعة.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>