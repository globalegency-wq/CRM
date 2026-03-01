<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">
            <h2 class="mb-4">تعديل عميل</h2>

            <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
            <?php endif; ?>

            <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
            <?php endif; ?>

            <form method="POST" action="customers.php?a=update">
                <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">الاسم الكامل للعميل</label>
                            <input type="text" class="form-control" name="name" required
                                value="<?= htmlspecialchars($customer['name']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">المنصب</label>
                            <input type="text" class="form-control" name="role" required
                                value="<?= htmlspecialchars($customer['role']) ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">الايميل</label>
                            <input type="email" class="form-control" name="email"
                                value="<?= htmlspecialchars($customer['email']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">الهاتف</label>
                            <input type="text" class="form-control" name="phone" required
                                value="<?= htmlspecialchars($customer['phone']) ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">اسم الشركة</label>
                            <input type="text" class="form-control" name="company_name" required
                                value="<?= htmlspecialchars($customer['company_name']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">عنوان الشركة</label>
                            <input type="text" class="form-control" name="company_address" rows="3" required
                                value="<?= htmlspecialchars($customer['company_address']) ?>">
                        </div>
                    </div>
                </div>


                <div class=" row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="company_reg_no" class="form-label">الرقم التجاري</label>
                            <input type="text" class="form-control" id="company_reg_no" name="company_reg_no"
                                value="<?= htmlspecialchars($customer['company_reg_no']) ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_tax_number_declare" class="form-label">صادر من</label>
                            <input type="text" class="form-control" id="C_tax_number_declare"
                                name="C_tax_number_declare"
                                value="<?= htmlspecialchars($customer['C_tax_number_declare']) ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_tax_number_date" class="form-label">بتاريخ</label>
                            <input type="date" class="form-control" id="C_tax_number_date" name="C_tax_number_date"
                                value="<?= htmlspecialchars($customer['C_tax_number_date']) ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_identity_number" class="form-label">رقم البطاقة الشخصية</label>
                            <input type="text" class="form-control" id="C_identity_number" name="C_identity_number"
                                value="<?= htmlspecialchars($customer['C_identity_number']) ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_identity_declare" class="form-label">صادرة من</label>
                            <input type="text" class="form-control" id="C_identity_declare" name="C_identity_declare"
                                value="<?= htmlspecialchars($customer['C_identity_declare']) ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_identity_date" class="form-label">بتاريخ</label>
                            <input type="date" class="form-control" id="C_identity_date" name="C_identity_date"
                                value="<?= htmlspecialchars($customer['C_identity_date']) ?>">
                        </div>
                    </div>
                </div>



                <div class="mb-3">
                    <label class="form-label">الاستفسار</label>
                    <textarea class="form-control" name="request" rows="3"
                        required><?= htmlspecialchars($customer['request']) ?></textarea>
                </div>


                <!-- <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" name="country" required
                        value="<?= htmlspecialchars($customer['country']) ?>">
                </div> -->



                <!-- <div class="mb-3">
                    <label class="form-label">Number of Employees</label>
                    <select name="employees" class="form-select" required>
                        <option value="1-10" <?= $customer['employees'] == '1-10' ? 'selected' : '' ?>>1-10</option>
                        <option value="11-50" <?= $customer['employees'] == '11-50' ? 'selected' : '' ?>>11-50</option>
                        <option value="51-200" <?= $customer['employees'] == '51-200' ? 'selected' : '' ?>>51-200</option>
                        <option value="201-500" <?= $customer['employees'] == '201-500' ? 'selected' : '' ?>>201-500</option>
                        <option value="500+" <?= $customer['employees'] == '500+' ? 'selected' : '' ?>>500+</option>
                    </select>
                </div> -->

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="user_status" class="form-select" required>
                        <option value="نشط" <?= $customer['user_status'] == 'نشط' ? 'selected' : '' ?>>نشط
                        </option>
                        <option value="خامل" <?= $customer['user_status'] == 'خامل' ? 'selected' : '' ?>>خامل
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                <a href="customers.php?a=index" class="btn btn-secondary ms-2">الغاء</a>
            </form>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>

</html>