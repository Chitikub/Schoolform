<?php
// File: controllers/ActivityController.php

require_once __DIR__ . '/../models/Activity.php';
require_once __DIR__ . '/../models/Student.php';

class ActivityController
{
    private $activityModel;
    private $studentModel;
    private $message = '';
    private $messageType = '';

    public function __construct()
    {
        $this->activityModel = new Activity();
        $this->studentModel = new Student();
    }

    // ************************************************************
    // ******************* NEW AJAX HANDLER ***********************
    // ************************************************************
    public function getStudentDetails()
    {
        // ตรวจสอบว่ามีการส่ง student_id มาหรือไม่
        if (isset($_GET['student_id'])) {
            $student_id = trim($_GET['student_id']);
            $student = $this->studentModel->getById($student_id);

            header('Content-Type: application/json');
            if ($student) {
                // ส่งข้อมูลนักศึกษาในรูปแบบ JSON
                echo json_encode(['success' => true, 'student' => $student]);
            } else {
                // ส่ง Error ถ้าหารหัสนักศึกษาไม่พบ
                echo json_encode(['success' => false, 'message' => 'ไม่พบรหัสนักศึกษา: ' . $student_id]);
            }
            exit;
        }
    }
    // ************************************************************

    // ฟังก์ชัน: รายการกิจกรรม 5 ตัวอย่าง
    private function getActivitiesList()
    {
        return [
            'กิจกรรมจิตอาสาทำความสะอาด',
            'อบรมเชิงปฏิบัติการพัฒนาทักษะดิจิทัล',
            'เข้าร่วมประชุมวิชาการ/สัมมนา',
            'โครงการอนุรักษ์สิ่งแวดล้อมและพลังงาน',
            'กิจกรรมกีฬาและนันทนาการเพื่อสุขภาพ',
            'อื่นๆ (ระบุเอง)'
        ];
    }

    public function index()
    {
        // ************************************************************
        // ******************* ตรวจสอบ AJAX Request ก่อน *******************
        // ************************************************************
        if (isset($_GET['action']) && $_GET['action'] === 'get_student_details') {
            $this->getStudentDetails();
            return;
        }
        // ************************************************************


        $editActivity = null;
        $activitiesList = $this->getActivitiesList();

        if (isset($_GET['edit'])) {
            $editActivity = $this->activityModel->getById($_GET['edit']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        }

        $activities = $this->activityModel->getAll();
        $students = $this->studentModel->getAll();

        $this->loadView('layout', [
            'title' => 'ระบบบันทึกกิจกรรมนักศึกษา',
            'content' => $this->renderActivityManagement($activities, $students, $editActivity, $activitiesList)
        ]);
    }

    private function handlePost()
    {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $this->addActivity();
                    break;
                case 'edit':
                    $this->editActivity();
                    break;
                case 'delete':
                    $this->deleteActivity();
                    break;
            }
        }
    }

    private function addActivity()
    {
        // รับค่าเวลาเริ่มต้น สิ้นสุด และชั่วโมงจาก Hidden Input
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $hours = (int)$_POST['hours'];

        // ตรวจสอบว่ามี student_id ที่ถูกต้องถูกส่งมา
        if (empty($_POST['student_id_final'])) {
            $this->setMessage('กรุณาระบุรหัสนักศึกษาหรือสแกน QR Code ก่อนบันทึก', 'error');
            return;
        }

        try {
            $this->activityModel->create(
                $_POST['student_id_final'], // ใช้ student_id ที่ถูกยืนยันแล้ว
                trim($_POST['activity_name']),
                $_POST['activity_date'],
                $start_time,
                $end_time,
                $hours
            );
            $this->setMessage('บันทึกกิจกรรมเรียบร้อยแล้ว! (' . $hours . ' ชั่วโมง)', 'success');
        } catch (Exception $e) {
            $this->setMessage($e->getMessage(), 'error');
        }
    }

    private function editActivity()
    {
        // รับค่าเวลาเริ่มต้น สิ้นสุด และชั่วโมงจาก Hidden Input
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $hours = (int)$_POST['hours'];

        try {
            $this->activityModel->update(
                $_POST['id'],
                $_POST['student_id'],
                trim($_POST['activity_name']),
                $_POST['activity_date'],
                $start_time,
                $end_time,
                $hours
            );
            $this->setMessage('แก้ไขข้อมูลเรียบร้อยแล้ว! (' . $hours . ' ชั่วโมง)', 'success');
            header('Location: index.php?page=activity');
            exit;
        } catch (Exception $e) {
            $this->setMessage($e->getMessage(), 'error');
        }
    }

    private function deleteActivity()
    {
        try {
            $this->activityModel->delete($_POST['id']);
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

    private function renderActivityManagement($activities, $students, $editActivity, $activitiesList)
    {
        ob_start();
?>
        <?php if ($this->message): ?>
            <div class="alert alert-<?php echo $this->messageType === 'success' ? 'success' : 'danger'; ?> mb-4" role="alert">
                <?php echo htmlspecialchars($this->message); ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-5">
                <?php
                include __DIR__ . '/../views/activity_form.php';
                ?>
            </div>
            <div class="col-md-7">
                <?php include __DIR__ . '/../views/activity_list.php'; ?>
            </div>
        </div>
<?php
        return ob_get_clean();
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../views/$view.php";
    }
}
