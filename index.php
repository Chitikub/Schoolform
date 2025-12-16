<?php
// File: index.php

$page = isset($_GET['page']) ? $_GET['page'] : 'activity';

// ************************************************************
// ******************* NEW AJAX ROUTE *************************
// ************************************************************
if (isset($_GET['action']) && $_GET['action'] === 'get_student_details') {
    require_once __DIR__ . '/controllers/ActivityController.php';
    $controller = new ActivityController();
    $controller->getStudentDetails();
    exit; // สำคัญ: ต้องหยุดการทำงานหลังจากส่ง JSON
}
// ************************************************************


switch ($page) {
    case 'student':
        require_once __DIR__ . '/controllers/StudentController.php';
        $controller = new StudentController();
        $controller->index();
        break;

    case 'activity':
    default:
        require_once __DIR__ . '/controllers/ActivityController.php';
        $controller = new ActivityController();
        $controller->index();
        break;
}
