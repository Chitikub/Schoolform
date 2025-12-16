<div class="card card-premium h-100">
    <div class="card-header card-header-premium">
        <h5 class="card-title text-primary mb-0 fw-bold">
            <?php echo $editActivity ? '✏️ แก้ไขกิจกรรม' : '📝 บันทึกกิจกรรมใหม่'; ?>
        </h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="index.php?page=activity" id="activityForm">
            <input type="hidden" name="action" value="<?php echo $editActivity ? 'edit' : 'add'; ?>">

            <?php if ($editActivity): ?>
                <input type="hidden" name="id" value="<?php echo $editActivity['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-bold text-primary">สแกน/กรอกรหัสนักศึกษา (QR Code)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-primary"><i class="bi bi-qr-code-scan"></i></span>
                    <input type="text" id="scan_student_id" class="form-control form-control-lg bg-warning-subtle fw-bold"
                        placeholder="สแกน QR Code หรือพิมพ์รหัสนักศึกษา" autofocus>
                </div>
            </div>

            <div class="mb-3" id="student_display_section">
                <label class="form-label fw-bold text-secondary">นักศึกษา:</label>
                <div class="d-flex align-items-center bg-success-subtle p-2 rounded border border-success-subtle" style="height: 46px;">
                    <span class="text-success fw-bold me-2"><i class="bi bi-check-circle-fill"></i></span>
                    <p id="student_name_display" class="mb-0 fw-bold text-success-emphasis">
                        กรุณาสแกน QR Code
                    </p>
                </div>
            </div>

            <input type="hidden" name="student_id_final" id="student_id_final" value="">

            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">ชื่อกิจกรรม</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-primary"><i class="bi bi-activity"></i></span>
                    <select name="activity_name" id="activity_name" class="form-select form-select-lg bg-light" required>
                        <option value="">-- เลือกกิจกรรม --</option>
                        <?php foreach ($activitiesList as $actName): ?>
                            <option value="<?php echo htmlspecialchars($actName); ?>"
                                <?php echo ($editActivity && $editActivity['activity_name'] == $actName) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($actName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="text" name="custom_activity_name" id="custom_activity_name" class="form-control mt-2"
                    placeholder="กรุณาระบุกิจกรรม (ถ้าเลือก 'อื่นๆ')" style="display:none;"
                    value="<?php echo ($editActivity && !in_array($editActivity['activity_name'], $activitiesList)) ? htmlspecialchars($editActivity['activity_name']) : ''; ?>">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary">วันที่ทำกิจกรรม</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-primary"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" name="activity_date" class="form-control bg-light" required
                            value="<?php echo $editActivity ? $editActivity['activity_date'] : date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="col-md-7">
                    <label class="form-label fw-bold text-secondary">ช่วงเวลาและชั่วโมง</label>
                    <div class="d-flex align-items-center bg-light p-2 rounded border" style="height: 46px;">
                        <span class="text-primary fw-medium me-2"><i class="bi bi-clock-history"></i></span>
                        <p id="time_display" class="mb-0 fw-bold me-3 text-dark">
                            <?php
                            $start_t = isset($editActivity['start_time']) ? date('H:i', strtotime($editActivity['start_time'])) : '09:00';
                            $end_t = isset($editActivity['end_time']) ? date('H:i', strtotime($editActivity['end_time'])) : '12:00';
                            $hours_t = isset($editActivity['hours']) ? $editActivity['hours'] : '3';
                            echo "{$start_t} - {$end_t}";
                            ?>
                        </p>
                        <span class="badge rounded-pill text-bg-primary fs-6 px-3" id="calculated_hours_display">
                            <?php echo "{$hours_t} ชม."; ?>
                        </span>
                    </div>
                </div>

                <input type="hidden" name="start_time" id="start_time" value="<?php echo $start_t; ?>">
                <input type="hidden" name="end_time" id="end_time" value="<?php echo $end_t; ?>">
                <input type="hidden" name="hours" id="hours_calculated" value="<?php echo $hours_t; ?>">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm" id="submit_button">
                    <i class="bi bi-save-fill me-2"></i> <?php echo $editActivity ? 'บันทึกการแก้ไข' : 'ยืนยันการบันทึก'; ?>
                </button>
                <?php if ($editActivity): ?>
                    <a href="index.php?page=activity" class="btn btn-outline-secondary btn-lg">ยกเลิก</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activitySelect = document.getElementById('activity_name');
        const customActivityInput = document.getElementById('custom_activity_name');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const hoursCalculatedInput = document.getElementById('hours_calculated');
        const timeDisplay = document.getElementById('time_display');
        const hoursDisplay = document.getElementById('calculated_hours_display');
        const form = document.getElementById('activityForm');
        const scanInput = document.getElementById('scan_student_id');
        const studentNameDisplay = document.getElementById('student_name_display');
        const studentFinalInput = document.getElementById('student_id_final');
        const submitButton = document.getElementById('submit_button');

        const defaultTimeMap = {
            'กิจกรรมจิตอาสาทำความสะอาด': {
                start: '08:00',
                end: '12:00',
                hours: 4
            },
            'อบรมเชิงปฏิบัติการพัฒนาทักษะดิจิทัล': {
                start: '09:00',
                end: '17:00',
                hours: 8
            },
            'เข้าร่วมประชุมวิชาการ/สัมมนา': {
                start: '13:00',
                end: '16:00',
                hours: 3
            },
            'โครงการอนุรักษ์สิ่งแวดล้อมและพลังงาน': {
                start: '10:00',
                end: '14:00',
                hours: 4
            },
            'กิจกรรมกีฬาและนันทนาการเพื่อสุขภาพ': {
                start: '16:00',
                end: '18:00',
                hours: 2
            },
            'อื่นๆ (ระบุเอง)': {
                start: '09:00',
                end: '12:00',
                hours: 3
            }
        };

        function updateTimeAndHours(start, end, hours) {
            timeDisplay.textContent = `${start} - ${end}`;
            hoursDisplay.textContent = `${hours} ชม.`;

            startTimeInput.value = start;
            endTimeInput.value = end;
            hoursCalculatedInput.value = hours;
        }

        function setSubmitState(isEnabled, message = 'กรุณาสแกน QR Code') {
            submitButton.disabled = !isEnabled;
            if (!isEnabled) {
                // ถ้า disabled ให้ตั้งค่าข้อความสถานะ
                studentNameDisplay.textContent = message;
                document.getElementById('student_display_section').classList.remove('bg-success-subtle');
                document.getElementById('student_display_section').classList.add('bg-danger-subtle');
            } else {
                document.getElementById('student_display_section').classList.add('bg-success-subtle');
                document.getElementById('student_display_section').classList.remove('bg-danger-subtle');
            }
        }


        // **************************************************
        // ********* NEW: SCAN/AJAX LOGIC *******************
        // **************************************************
        scanInput.addEventListener('change', function() {
            const studentId = scanInput.value.trim();
            studentFinalInput.value = ''; // เคลียร์ค่า ID ที่ยืนยันแล้ว
            setSubmitState(false, 'กำลังค้นหา...');

            if (studentId.length < 5) {
                setSubmitState(false, 'รหัสนักศึกษาไม่ถูกต้อง');
                return;
            }

            // 1. เรียก AJAX ไปยัง Controller เพื่อดึงข้อมูลนักศึกษา
            fetch(`index.php?action=get_student_details&student_id=${studentId}`)
                .then(response => response.json())
                .then(data => {
                    const section = document.getElementById('student_display_section');
                    const pTag = section.querySelector('p');

                    if (data.success) {
                        // 2. พบข้อมูล: แสดงชื่อและเปิดปุ่มบันทึก
                        pTag.textContent = `${data.student.student_id} - ${data.student.student_name}`;
                        studentFinalInput.value = data.student.student_id; // ตั้งค่า ID ที่ยืนยัน
                        setSubmitState(true, pTag.textContent);
                        scanInput.classList.remove('is-invalid');
                        scanInput.classList.add('is-valid');
                    } else {
                        // 3. ไม่พบข้อมูล: แสดง Error และปิดปุ่มบันทึก
                        pTag.textContent = `!! ไม่พบรหัส ${studentId} ในระบบ !!`;
                        setSubmitState(false, pTag.textContent);
                        scanInput.classList.add('is-invalid');
                        scanInput.classList.remove('is-valid');
                    }
                })
                .catch(error => {
                    console.error('Error fetching student details:', error);
                    setSubmitState(false, 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
                });
        });

        // **************************************************

        activitySelect.addEventListener('change', function() {
            const selectedActivity = activitySelect.value;
            const times = defaultTimeMap[selectedActivity];
            const isCustom = selectedActivity === 'อื่นๆ (ระบุเอง)';

            if (times) {
                updateTimeAndHours(times.start, times.end, times.hours);

                customActivityInput.style.display = isCustom ? 'block' : 'none';
                if (isCustom) {
                    customActivityInput.setAttribute('required', 'required');
                } else {
                    customActivityInput.removeAttribute('required');
                }
            } else {
                updateTimeAndHours('', '', 0);
                customActivityInput.style.display = 'none';
                customActivityInput.removeAttribute('required');
            }
        });

        // Listener สำหรับ Form Submit
        form.addEventListener('submit', function(event) {
            // ตรวจสอบว่ามีการยืนยัน student_id แล้วหรือไม่
            if (studentFinalInput.value === '') {
                event.preventDefault();
                alert('กรุณาทำการสแกน/กรอกรหัสนักศึกษาและตรวจสอบว่าชื่อปรากฏขึ้นมาก่อนบันทึก');
                scanInput.focus();
                return;
            }

            // จัดการ Activity Name กรณีเลือก 'อื่นๆ'
            if (activitySelect.value === 'อื่นๆ (ระบุเอง)' && customActivityInput.value.trim() !== '') {
                customActivityInput.name = 'activity_name';
                activitySelect.name = 'temp_activity_name';
            } else {
                customActivityInput.name = 'temp_custom_activity_name';
                activitySelect.name = 'activity_name';
            }
        });

        // Initial call on load
        activitySelect.dispatchEvent(new Event('change'));
        setSubmitState(false, 'กรุณาสแกน QR Code'); // ปิดปุ่มบันทึกไว้ก่อน

        // หากเป็นหน้าแก้ไข ให้ดึง ID เดิมมาแสดงผล (สมมติว่า ID อยู่ใน $editActivity['student_id'] ถ้าไม่ empty)
        <?php if ($editActivity): ?>
            const editStudentId = '<?php echo $editActivity['student_id']; ?>';
            scanInput.value = editStudentId;
            scanInput.dispatchEvent(new Event('change')); // กระตุ้นการค้นหา
        <?php endif; ?>
    });
</script>