<nav class="col-md-2 sidebar">
    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="dashboard.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">لوحة
                التحكم</a>
        </li>

        <li class="nav-item mb-2">
            <a href="leads.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'leads.php' ? 'active' : '' ?>">الصفقات</a>
        </li>

        <li class="nav-item mb-2">
            <a href="customers.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : '' ?>">العملاء</a>
        </li>

        <li class="nav-item mb-2">
            <a href="contracts.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contracts.php' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i> العقود
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="tasks.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'tasks.php' ? 'active' : '' ?>">مهام/متابعة</a>
        </li>

        <li class="nav-item mb-2">
            <a href="notes.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'notes.php' ? 'active' : '' ?>">ملاحظات/انشطة</a>
        </li>

        <li class="nav-item mb-2">
            <a href="change_password.php"
                class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'change_password.php' ? 'active' : '' ?>">تغيير
                كلمة السر
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="index.php?a=logout" class="nav-link text-danger">تسجيل الخروج</a>
        </li>

    </ul>
</nav>