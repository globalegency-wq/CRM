<?php
session_start();
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/ContractsController.php';

$action = $_GET['a'] ?? 'index';
$controller = new ContractsController($conn);

$id = $_GET['id'] ?? 0;

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    
    case 'edit':
        $controller->edit(); // لفتح صفحة التعديل
        break;
    case 'update':
        $controller->update(); // لحفظ التعديلات الجديدة
        break;
    // ---------------------------------------

    case 'delete':
        $controller->delete();
        break;
    case 'print':
        $controller->print();
        break;
    default:
        $controller->index();
        break;
}

if (!method_exists($controller, $action)) {
    $action = 'index';
}
$controller->$action();