<div class="card h-100">
    <div class="card-body p-0">
        <div class="p-4 border-bottom border-light">
            <h5 class="card-title text-primary mb-0 fw-bold">📋 รายการกิจกรรมทั้งหมด</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">วันที่</th>
                        <th class="py-3">นักศึกษา</th>
                        <th class="py-3">กิจกรรม</th>
                        <th class="py-3 text-center">ชั่วโมง</th>
                        <th class="py-3 text-end pe-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted bg-light">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                ยังไม่มีข้อมูลกิจกรรม
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activities as $act): ?>
                            <tr>
                                <td class="ps-4 text-muted" style="width: 15%;">
                                    <?php echo date('d/m/y', strtotime($act['activity_date'])); ?>
                                </td>
                                <td style="width: 25%;">
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($act['student_name']); ?></div>
                                    <div class="small text-muted" style="font-size: 0.8rem;">
                                        <?php echo $act['student_code']; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($act['activity_name']); ?>
                                </td>
                                <td class="text-center" style="width: 10%;">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3">
                                        <?php echo $act['hours']; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4" style="width: 20%;">
                                    <div class="btn-group">
                                        <a href="?edit=<?php echo $act['id']; ?>" class="btn btn-sm btn-outline-secondary border-0">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('ต้องการลบข้อมูลนี้ใช่หรือไม่?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $act['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                <i class="bi bi-trash"></i>
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