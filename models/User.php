<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    // อ่านข้อมูลทั้งหมด (READ)
    public function getAllStudents()
    {
        $sql = "SELECT * FROM students";
        return $this->conn->query($sql);
    }

    // อ่านข้อมูลนักศึกษาตามรหัส (READ)
    public function getStudentById($student_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // สร้างนักศึกษาใหม่ (CREATE)
    public function createStudent($student_id, $student_name)
    {
        $stmt = $this->conn->prepare("INSERT INTO students (student_id, student_name) VALUES (?, ?)");
        $stmt->bind_param("ss", $student_id, $student_name);
        return $stmt->execute();
    }

    // อัพเดตนักศึกษา (UPDATE)
    public function updateStudent($student_id, $student_name)
    {
        $stmt = $this->conn->prepare("UPDATE students SET student_name = ? WHERE student_id = ?");
        $stmt->bind_param("ss", $student_name, $student_id);
        return $stmt->execute();
    }

    // ลบนักศึกษา (DELETE)
    public function deleteStudent($student_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        return $stmt->execute();
    }
}
