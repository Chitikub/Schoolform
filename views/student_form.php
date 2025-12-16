<div class="card card-premium h-100">
    <div class="card-header card-header-premium">
        <h5 class="card-title text-primary mb-0 fw-bold">
            <?php echo $editStudent ? '✏️ แก้ไขข้อมูลนักศึกษา' : '👤 เพิ่มนักศึกษาใหม่'; ?>
        </h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="index.php?page=student">
            <input type="hidden" name="action" value="<?php echo $editStudent ? 'edit' : 'add'; ?>">

            <?php if ($editStudent): ?>
                <input type="hidden" name="original_student_id" value="<?php echo $editStudent['student_id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">รหัสนักศึกษา</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-primary"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" name="student_id" class="form-control form-control-lg bg-light" required
                        placeholder="เช่น 6642300XX" maxlength="20"
                        value="<?php echo $editStudent ? htmlspecialchars($editStudent['student_id']) : ''; ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-secondary">ชื่อ - นามสกุล</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-primary"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" name="student_name" class="form-control form-control-lg bg-light" required
                        placeholder="ระบุชื่อ-นามสกุล..." maxlength="100"
                        value="<?php echo $editStudent ? htmlspecialchars($editStudent['student_name']) : ''; ?>">
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-save me-2"></i> <?php echo $editStudent ? 'บันทึกการแก้ไข' : 'ยืนยันการเพิ่มข้อมูล'; ?>
                </button>
                <?php if ($editStudent): ?>
                    <a href="index.php?page=student" class="btn btn-outline-secondary btn-lg">ยกเลิก</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>