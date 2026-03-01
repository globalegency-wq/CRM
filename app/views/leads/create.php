<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">اضافة صفقة جديدة</h2>
                <a href="leads.php" class="btn btn-secondary">الرجوع للقائمة</a>
            </div>

            <form method="post" action="leads.php?a=store">

                <div class="mb-3">
                    <label class="form-label">اسم الصفقة*</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الايميل</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الشركة</label>
                    <input type="text" name="company_name" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">مصدر الصفقة</label>
                    <input type="text" name="lead_source" class="form-control"
                        placeholder="صفحات التواصل الاجتماعي, مكالمة, إحالة, etc.">
                </div>

                <div class="mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="جديدة">جديدة</option>
                        <option value="تم التواصل">تم التواصل</option>
                        <option value="متأهلة">متأهلة</option>
                        <option value="مفقودة">مفقودة</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">حفظ الصفقة</button>

            </form>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>