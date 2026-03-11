<?php include __DIR__ . '/../../includes/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid" style="direction: rtl" ;>
    <div class=" row">
        <!-- <main class="col-md-10 ms-sm-auto content-area px-md-4">
        </main> -->

        <?php include __DIR__ . '/../../includes/left.php'; ?>

        <main class="col-md-10 ms-sm-auto content-area">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="m-0">لوحة التحكم</h2>
            </div>

            <!-- Top Stats -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">اجمالي الصفقات</h6>
                            <h3><?= (int) $totalLeads; ?></h3>
                            <a href="leads.php" class="small">عرض كل الصفقات</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">اجمالي العملاء</h6>
                            <h3><?= (int) $totalCustomers; ?></h3>
                            <a href="customers.php" class="small">عرض كل العملاء</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">المهام المعلقة</h6>
                            <h3><?= (int) $pendingTasks; ?></h3>
                            <a href="tasks.php" class="small">عرض كل المهام</a>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width: 400px; margin: auto;">
                <canvas id="statusChart"></canvas>
            </div>

            <div class="row">


                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-right">أكثر 5 خدمات طلباً</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 250px;">
                                <canvas id="requestsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            // إعداد المخطط الدائري
            new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: ['نشط', 'خامل'],
                    datasets: [{
                        data: [<?= (int)$activeCount ?>, <?= (int)$inactiveCount ?>],
                        backgroundColor: ['#28a745', '#dc3545']
                    }]
                },
                options: {
                    maintainAspectRatio: false
                } // ضروري لمنع التضخم
            });

            // إعداد مخطط الأعمدة
            new Chart(document.getElementById('requestsChart'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode($reqLabels) ?>,
                    datasets: [{
                        label: 'عدد الطلبات',
                        data: <?= json_encode($reqData) ?>,
                        backgroundColor: '#4e73df'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false
                }
            });
            </script>

            <!-- Today's Follow-ups -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            متابعة اليوم
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>الرقم</th>
                                            <th>العميل</th>
                                            <th>العنوان</th>
                                            <th>تاريخ المتابعة</th>
                                            <th>الحالة</th>
                                            <th width="120">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($todayTasks)): ?>
                                        <?php foreach ($todayTasks as $task): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($task['id']); ?></td>
                                            <td><?= htmlspecialchars($task['customer_name']); ?></td>
                                            <td><?= htmlspecialchars($task['title']); ?></td>
                                            <td><?= htmlspecialchars($task['followup_date']); ?></td>
                                            <td><?= htmlspecialchars(ucfirst($task['status'])); ?></td>
                                            <td>
                                                <a href="tasks.php?a=edit&id=<?= $task['id']; ?>"
                                                    class="btn btn-sm btn-warning">تعديل</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center p-3">لا يوجد اي جدول
                                                لمتابعته
                                                اليوم.
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Leads & Customers -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            آخر الصفقات
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الايميل</th>
                                            <th>الهاتف</th>
                                            <th>تاريخ الانشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recentLeads)): ?>
                                        <?php foreach ($recentLeads as $lead): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($lead['name']); ?></td>
                                            <td><?= htmlspecialchars($lead['email']); ?></td>
                                            <td><?= htmlspecialchars($lead['phone']); ?></td>
                                            <td><?= htmlspecialchars($lead['created_at']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center p-3">لا يوجد صفقات .</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            آخر العملاء
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الايميل</th>
                                            <th>الهاتف</th>
                                            <th>تاريخ الانشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recentCustomers)): ?>
                                        <?php foreach ($recentCustomers as $cust): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($cust['name']); ?></td>
                                            <td><?= htmlspecialchars($cust['email']); ?></td>
                                            <td><?= htmlspecialchars($cust['phone']); ?></td>
                                            <td><?= htmlspecialchars($cust['created_at']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center p-3">لا يوجد عملاء.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>