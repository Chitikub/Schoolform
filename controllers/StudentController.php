<?php
// File: controllers/StudentController.php

require_once __DIR__ . '/../models/Student.php';

class StudentController
{ // <--- ตรวจสอบชื่อคลาสนี้ให้ตรง
    private $studentModel;
    private $message = '';
    private $messageType = '';

    public function __construct()
    {
        $this->studentModel = new Student();
    }

    public function index()
    {
        $editStudent = null;

        if (isset($_GET['edit'])) {
            $editStudent = $this->studentModel->getById($_GET['edit']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        }

        $students = $this->studentModel->getAll();

        $this->loadView('layout', [
            'title' => 'จัดการข้อมูลนักศึกษา',
            'content' => $this->renderStudentManagement($students, $editStudent)
        ]);
    }

    private function handlePost()
    {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $this->addStudent();
                    break;
                case 'edit':
                    $this->editStudent();
                    break;
                case 'delete':
                    $this->deleteStudent();
                    break;
            }
        }
    }

    private function addStudent()
    {
        // ตรวจสอบข้อมูลพื้นฐาน
        if (empty(trim($_POST['student_id'])) || empty(trim($_POST['student_name']))) {
            $this->setMessage('กรุณากรอกรหัสนักศึกษาและชื่อ-นามสกุลให้ครบถ้วน', 'error');
            return;
        }

        try {
            $this->studentModel->create(
                trim($_POST['student_id']),
                trim($_POST['student_name'])
            );
            $this->setMessage('เพิ่มนักศึกษาเรียบร้อยแล้ว!', 'success');
        } catch (Exception $e) {
            $this->setMessage($e->getMessage(), 'error');
        }
    }

    private function editStudent()
    {
        // ตรวจสอบข้อมูลพื้นฐาน
        if (empty(trim($_POST['student_id'])) || empty(trim($_POST['student_name']))) {
            $this->setMessage('กรุณากรอกรหัสนักศึกษาและชื่อ-นามสกุลให้ครบถ้วน', 'error');
            return;
        }

        try {
            $this->studentModel->update(
                $_POST['original_student_id'],
                trim($_POST['student_id']),
                trim($_POST['student_name'])
            );
            $this->setMessage('แก้ไขข้อมูลเรียบร้อยแล้ว!', 'success');
            header('Location: index.php?page=student');
            exit;
        } catch (Exception $e) {
            $this->setMessage($e->getMessage(), 'error');
        }
    }

    private function deleteStudent()
    {
        try {
            $this->studentModel->delete($_POST['student_id']);
            $this->setMessage('ลบข้อมูลเรียบร้อยแล้ว!', 'success');
        } catch (Exception $e) {
            $this->setMessage($e->getMessage(), 'error');
        }
    }

    private function setMessage($message, $type)
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    private function renderStudentManagement($students, $editStudent)
    {
        ob_start();
        // ตรวจสอบว่าไฟล์ views เหล่านี้มีอยู่จริงในโฟลเดอร์ views
        include __DIR__ . '/../views/student_index.php';
        return ob_get_clean();
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../views/$view.php";
    }
}
