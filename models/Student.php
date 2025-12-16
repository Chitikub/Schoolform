<?php
require_once __DIR__ . '/../config/database.php';

class Student
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ดึงข้อมูลทั้งหมด
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM students ORDER BY student_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ดึงข้อมูลตาม ID
    public function getById($student_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // สร้างนักศึกษาใหม่
    public function create($student_id, $student_name)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO students (student_id, student_name) VALUES (?, ?)");
            $stmt->execute([$student_id, $student_name]);
            return true;
        } catch (PDOException $e) {
            // กรณีรหัสนักศึกษาซ้ำ
            if ($e->getCode() == 23000) {
                throw new Exception('รหัสนักศึกษานี้มีอยู่ในระบบแล้ว');
            }
            throw new Exception('เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . $e->getMessage());
        }
    }

    // อัปเดตข้อมูล
    public function update($original_student_id, $student_id, $student_name)
    {
        try {
            $stmt = $this->db->prepare("UPDATE students SET student_id = ?, student_name = ? WHERE student_id = ?");
            $stmt->execute([$student_id, $student_name, $original_student_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception('รหัสนักศึกษาใหม่ที่แก้ไขไปซ้ำกับคนอื่นในระบบ');
            }
            throw new Exception('เกิดข้อผิดพลาดในการแก้ไขข้อมูล: ' . $e->getMessage());
        }
    }

    // ลบข้อมูล (ระวัง: ถ้ามีกิจกรรมผูกอยู่ อาจจะลบไม่ได้ถ้าไม่ได้ตั้ง Cascade ใน DB)
    public function delete($student_id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM students WHERE student_id = ?");
            $stmt->execute([$student_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // กรณีมี Foreign Key ติดอยู่
            if ($e->getCode() == 23000) {
                throw new Exception('ไม่สามารถลบนักศึกษาได้ เนื่องจากมีประวัติกิจกรรมอยู่');
            }
            throw new Exception('เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }
}
