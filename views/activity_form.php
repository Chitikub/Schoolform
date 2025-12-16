<div class="card h-100">
    <div class="card-body p-4">
        <h5 class="card-title text-primary mb-4 fw-bold">
            <?php echo $editActivity ? '✏️ แก้ไขข้อมูล' : '📝 บันทึกกิจกรรมใหม่'; ?>
        </h5>

        <form method="POST" action="index.php">
            <input type="hidden" name="action" value="<?php echo $editActivity ? 'edit' : 'add'; ?>">
            <?php if ($editActivity): ?>
                <input type="hidden" name="id" value="<?php echo $editActivity['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">รหัสนักศึกษา - ชื่อสกุล</label>
                <select name="student_code" class="form-select bg-light border-0" required>
                    <option value="">-- เลือกนักศึกษา --</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_code']; ?>"
                            <?php echo ($editActivity && $editActivity['student_code'] == $student['student_code']) ? 'selected' : ''; ?>>
                            <?php echo $student['student_code'] . ' - ' . $student['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">ชื่อกิจกรรม</label>
                <input type="text" name="activity_name" class="form-control bg-light border-0" required
                    placeholder="ระบุกิจกรรมที่ทำ..."
                    value="<?php echo $editActivity ? htmlspecialchars($editActivity['activity_name']) : ''; ?>">
            </div>

            <div class="row g-2 mb-4">
                <div class="col-7">
                    <label class="form-label text-muted small fw-bold">วันที่</label>
                    <input type="date" name="activity_date" class="form-control bg-light border-0" required
                        value="<?php echo $editActivity ? $editActivity['activity_date'] : date('Y-m-d'); ?>">
                </div>
                <div class="col-5">
                    <label class="form-label text-muted small fw-bold">ชั่วโมง</label>
                    <input type="number" name="hours" class="form-control bg-light border-0" required min="1" placeholder="0"
                        value="<?php echo $editActivity ? $editActivity['hours'] : ''; ?>">
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <?php echo $editActivity ? 'บันทึกการแก้ไข' : 'ยืนยันการบันทึก'; ?>
                </button>
                <?php if ($editActivity): ?>
                    <a href="index.php" class="btn btn-light text-muted">ยกเลิก</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>