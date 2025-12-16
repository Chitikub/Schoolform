<div class="card card-premium h-100">
    <div class="card-header card-header-premium d-flex justify-content-between align-items-center">
        <h5 class="card-title text-primary mb-0 fw-bold">📋 รายชื่อนักศึกษาทั้งหมด</h5>
        <span class="badge bg-primary rounded-pill px-3 py-2"><?php echo count($students); ?> คน</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary table-opacity-25">
                    <tr>
                        <th class="ps-4 py-3 text-primary">รหัสนักศึกษา</th>
                        <th class="py-3 text-primary">ชื่อ - นามสกุล</th>
                        <th class="py-3 text-center text-primary" style="width: 100px;">QR Code</th>
                        <th class="py-3 text-end pe-4 text-primary" style="width: 150px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted bg-light">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                                ยังไม่มีข้อมูลนักศึกษา
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary font-monospace">
                                    <i class="bi bi-hash me-1"></i><?php echo $student['student_id']; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($student['student_name']); ?></div>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-info border-0 p-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#qrCodeModal"
                                        data-student-id="<?php echo $student['student_id']; ?>"
                                        data-student-name="<?php echo htmlspecialchars($student['student_name']); ?>"
                                        title="แสดง QR Code">
                                        <i class="bi bi-qr-code fs-5"></i>
                                    </button>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm shadow-sm">
                                        <a href="?page=student&edit=<?php echo $student['student_id']; ?>" class="btn btn-light text-warning border" title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="index.php?page=student" class="d-inline" onsubmit="return confirm('ต้องการลบนักศึกษาคนนี้ใช่หรือไม่? หากมีกิจกรรมที่บันทึกไว้อาจจะไม่สามารถลบได้');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                                            <button type="submit" class="btn btn-light text-danger border rounded-end" title="ลบ">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="qrCodeModalLabel">QR Code: <span id="modal-student-name"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted" id="modal-student-id"></p>
                <img id="qr-code-img" src="" alt="QR Code" class="img-fluid border p-3 rounded shadow-sm">
                <p class="small text-danger mt-3">ใช้ QR Code นี้ในการสแกนบันทึกกิจกรรม</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-info text-white" onclick="window.print()"><i class="bi bi-printer"></i> พิมพ์</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qrCodeModal = document.getElementById('qrCodeModal');

        // Listener เมื่อ Modal ถูกเปิด
        qrCodeModal.addEventListener('show.bs.modal', function(event) {
            // ดึงข้อมูลจากปุ่มที่กด (ปุ่ม QR Code)
            const button = event.relatedTarget;
            const studentId = button.getAttribute('data-student-id');
            const studentName = button.getAttribute('data-student-name');

            const modalTitle = qrCodeModal.querySelector('#modal-student-name');
            const modalId = qrCodeModal.querySelector('#modal-student-id');
            const qrCodeImg = qrCodeModal.querySelector('#qr-code-img');

            // **การสร้าง QR Code ด้วย API**
            // โค้ดที่เก็บใน QR Code คือ: รหัสนักศึกษา (studentId)
            // URL API: https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=รหัสนักศึกษา
            const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(studentId)}`;

            modalTitle.textContent = studentName;
            modalId.textContent = `รหัส: ${studentId}`;
            qrCodeImg.src = qrApiUrl; // โหลดรูป QR Code
        });
    });
</script>