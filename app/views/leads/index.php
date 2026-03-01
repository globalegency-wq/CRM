<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">قائمة الصفقات</h2>
                <a href="leads.php?a=create" class="btn btn-primary">اضافة صفقة جديدة</a>
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
                            <th>اسم الصفقة</th>
                            <th>الايميل</th>
                            <th>الهاتف</th>
                            <th>اسم الشركة</th>
                            <th>المصدر</th>
                            <th>الحالة</th>
                            <th>تاريخ الانشاء</th>
                            <th width="140">الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leads)): ?>
                        <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><?= htmlspecialchars($lead['id']); ?></td>
                            <td><?= htmlspecialchars($lead['name']); ?></td>
                            <td><?= htmlspecialchars($lead['email']); ?></td>
                            <td><?= htmlspecialchars($lead['phone']); ?></td>
                            <td><?= htmlspecialchars($lead['company_name']); ?></td>
                            <td><?= htmlspecialchars($lead['lead_source']); ?></td>
                            <td><?= htmlspecialchars(ucfirst($lead['status'])); ?></td>
                            <td><?= htmlspecialchars($lead['created_at']); ?></td>
                            <td>
                                <a href="leads.php?a=edit&id=<?= $lead['id']; ?>"
                                    class="btn btn-sm btn-warning">تعديل</a>
                                <a href="leads.php?a=delete&id=<?= $lead['id']; ?>"
                                    onclick="return confirm('هل انت متأكد من حذف هذه الصفقة?');"
                                    class="btn btn-sm btn-danger">حذف</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">لا يوجد صفقات.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>