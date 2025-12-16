<div class="card card-premium h-100">
    <div class="card-header card-header-premium d-flex justify-content-between align-items-center">
        <h5 class="card-title text-primary mb-0 fw-bold">📋 รายการกิจกรรมทั้งหมด</h5>
        <span class="badge bg-info text-dark rounded-pill px-3 py-2"><?php echo count($activities); ?> รายการ</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary table-opacity-25">
                    <tr>
                        <th class="ps-4 py-3 text-primary">วันที่</th>
                        <th class="py-3 text-primary">นักศึกษา</th>
                        <th class="py-3 text-primary">กิจกรรม</th>
                        <th class="py-3 text-center text-primary">เวลาเริ่มต้น - สิ้นสุด</th>
                        <th class="py-3 text-center text-primary">รวม (ชม.)</th>
                        <th class="py-3 text-end pe-4 text-primary" style="width: 120px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted bg-light">
                                <i class="bi bi-inbox-fill fs-1 d-block mb-2 opacity-50"></i>
                                ยังไม่มีข้อมูลกิจกรรม
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activities as $act): ?>
                            <tr>
                                <td class="ps-4 text-secondary fw-medium" style="font-size: 0.95rem;">
                                    <i class="bi bi-calendar3 me-1 opacity-75"></i>
                                    <?php echo date('d/m/Y', strtotime($act['activity_date'])); ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($act['student_name'] ?? 'ไม่พบชื่อ'); ?></div>
                                    <div class="small text-muted font-monospace" style="font-size: 0.85rem;">
                                        <i class="bi bi-person-badge me-1"></i> <?php echo $act['student_id']; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-primary-emphasis"><?php echo htmlspecialchars($act['activity_name']); ?></span>
                                </td>
                                <td class="text-center text-secondary fw-medium" style="font-size: 0.95rem;">
                                    <?php
                                    $start_t = isset($act['start_time']) ? date('H:i', strtotime($act['start_time'])) : '-';
                                    $end_t = isset($act['end_time']) ? date('H:i', strtotime($act['end_time'])) : '-';
                                    echo "{$start_t} - {$end_t}";
                                    ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill text-bg-primary bg-opacity-75 fs-6 px-3">
                                        <?php echo $act['hours']; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm shadow-sm">
                                        <a href="?page=activity&edit=<?php echo $act['id']; ?>" class="btn btn-light text-warning border" title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="index.php?page=activity" class="d-inline" onsubmit="return confirm('ยืนยันการลบข้อมูลกิจกรรมนี้?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $act['id']; ?>">
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