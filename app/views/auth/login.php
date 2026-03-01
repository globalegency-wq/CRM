<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Secure CRM Login Panel: <?php echo company_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <style>
    body {
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        /* يضمن أن الجسم يأخذ كامل ارتفاع الشاشة */
        background-color: #f8f9fa;
        /* لون خلفية اختياري */
    }

    .container {
        flex: 1;
        /* يجعل الحاوية تتمدد لتشغل المساحة المتاحة */
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* توسيط عمودي */
        align-items: center;
        /* توسيط أفقي */
    }

    .auth-form {
        width: 100%;
        max-width: 400px;
        /* تحديد عرض النموذج ليكون متناسقاً */
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        /* ظل خفيف لإبراز الفورم */
    }

    .logo-top {
        margin-bottom: 20px;
        max-width: 200px;
    }

    footer {
        /* التأكد من بقاء الفوتر في الأسفل */
        margin-top: auto;
    }
    </style>
</head>

<body>

    <div class="container">

        <!-- Logo Top -->
        <img src="<?php echo company_logo_home; ?>" alt="<?php echo company_name; ?>" class="logo-top">

        <!-- Login Form -->
        <div id="login-form" class="auth-form" style="text-align:center">

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php
                    switch ($error) {
                        case 'missing_fields':
                            echo "Please fill in all required fields.";
                            break;
                        case 'invalid_email':
                            echo "Invalid email address.";
                            break;
                        case 'password_mismatch':
                            echo "Passwords do not match.";
                            break;
                        case 'db_error':
                            echo "Database error occurred. Try again.";
                            break;
                        case 'invalid_login':
                            echo "Invalid email or password.";
                            break;
                        case 'invalid_csrf':
                            echo "Security token expired. Please try again.";
                            break;
                        default:
                            echo "An unknown error occurred.";
                    }
                    ?>
            </div>
            <?php endif; ?>

            <h4 class="form-title" style="text-align:center">تسجيل الدخول لحسابك</h4>

            <form action="index.php?a=doLogin" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <input type="text" name="website" class="honeypot">

                <div class="mb-3" style="text-align:right">
                    <label class="form-label">الايميل</label>
                    <input type="email" name="email" class="form-control" style="text-align:right" required>
                </div>

                <div class="mb-3" style="text-align:right">
                    <label class="form-label">كلمة السر</label>
                    <input type="password" name="password" class="form-control" style="text-align:right" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
            </form>
        </div>

    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>