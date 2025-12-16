<?php
require_once __DIR__ . '/../config/database.php';

class Activity
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        // ดึงข้อมูลทั้งหมด รวมถึง start_time และ end_time
        $sql = "SELECT a.*, s.student_name 
                FROM activities a 
                LEFT JOIN students s ON a.student_id = s.student_id 
                ORDER BY a.activity_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // แก้ไข: เพิ่ม $start_time และ $end_time ในพารามิเตอร์และการบันทึก
    public function create($student_id, $activity_name, $activity_date, $start_time, $end_time, $hours)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO activities (student_id, activity_name, activity_date, start_time, end_time, hours) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$student_id, $activity_name, $activity_date, $start_time, $end_time, $hours]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error adding activity: ' . $e->getMessage());
        }
    }

    // แก้ไข: เพิ่ม $start_time และ $end_time ในพารามิเตอร์และการอัปเดต
    public function update($id, $student_id, $activity_name, $activity_date, $start_time, $end_time, $hours)
    {
        try {
            $stmt = $this->db->prepare("UPDATE activities SET student_id = ?, activity_name = ?, activity_date = ?, start_time = ?, end_time = ?, hours = ? WHERE id = ?");
            $stmt->execute([$student_id, $activity_name, $activity_date, $start_time, $end_time, $hours, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception('Error updating activity: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM activities WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception('Error deleting activity: ' . $e->getMessage());
        }
    }
}
