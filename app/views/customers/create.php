<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="container-fluid" style="direction: rtl">
    <div class="row">

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">
            <h2 class="mb-4">اضافة عميل جديد</h2>

            <?php if ($successMsg): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMsg) ?>
            </div>
            <?php endif; ?>

            <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
            <?php endif; ?>

            <form method="POST" action="customers.php?a=store">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم العميل الكامل</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="<?= htmlspecialchars($name ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">المنصب</label>
                            <input type="text" class="form-control" id="role" name="role" required
                                value="<?= htmlspecialchars($role ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">الايميل</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($email ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" required
                                value="<?= htmlspecialchars($phone ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">اسم الشركة</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required
                                value="<?= htmlspecialchars($company_name ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_address" class="form-label">عنوان الشركة</label>
                            <input type="text" class="form-control" id="company_address" name="company_address" required
                                value="<?= htmlspecialchars($company_address ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class=" row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="company_reg_no" class="form-label">الرقم التجاري</label>
                            <input type="text" class="form-control" id="company_reg_no" name="company_reg_no"
                                value="<?= htmlspecialchars($company_reg_no ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_tax_number_declare" class="form-label">صادر من</label>
                            <input type="text" class="form-control" id="C_tax_number_declare"
                                name="C_tax_number_declare"
                                value="<?= htmlspecialchars($C_tax_number_declare ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_tax_number_date" class="form-label">بتاريخ</label>
                            <input type="date" class="form-control" id="C_tax_number_date" name="C_tax_number_date"
                                value="<?= htmlspecialchars($C_tax_number_date ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_identity_number" class="form-label">رقم البطاقة الشخصية</label>
                            <input type="text" class="form-control" id="C_identity_number" name="C_identity_number"
                                value="<?= htmlspecialchars($C_identity_number ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_identity_declare" class="form-label">صادرة من</label>
                            <input type="text" class="form-control" id="C_identity_declare" name="C_identity_declare"
                                value="<?= htmlspecialchars($C_identity_declare ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="C_identity_date" class="form-label">بتاريخ</label>
                            <input type="date" class="form-control" id="C_identity_date" name="C_identity_date"
                                value="<?= htmlspecialchars($C_identity_date ?? '') ?>">
                        </div>
                    </div>
                </div>



                <div class="mb-3">
                    <label for="request" class="form-label"> الاستفسار</label>
                    <textarea class="form-control" id="request" name="request" rows="3"
                        required><?= htmlspecialchars($request ?? '') ?></textarea>
                </div>

                <!-- <div class="mb-3">
                        <label for="country" class="form-label">الدولة</label>
                        <input type="text" class="form-control" id="country" name="country" required
                            value="<?= htmlspecialchars($country ?? '') ?>">
                    </div> -->


                <!-- <div class="mb-3">
                        <label for="employees" class="form-label">Number of Employees</label>
                        <select name="employees" class="form-select" required>
                            <option value="" disabled selected>Select Number of Employees</option>
                            <option value="1-10">1-10</option>
                            <option value="11-50">11-50</option>
                            <option value="51-200">51-200</option>
                            <option value="201-500">201-500</option>
                            <option value="500+">500+</option>
                        </select>
                    </div> -->

                <div class="mb-3">
                    <label for="status" class="form-label">الحالة</label>
                    <select name="user_status" id="user_status" class="form-select" required>
                        <option value="نشط">نشط</option>
                        <option value="خامل">خامل</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">اضافة عميل</button>
                <a href="customers.php" class="btn btn-secondary ms-2">الغاء</a>
            </form>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>