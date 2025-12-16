<?php if ($this->message): ?>
    <div class="alert alert-<?php echo $this->messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <?php echo htmlspecialchars($this->message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-4">
        <?php include __DIR__ . '/student_form.php'; ?>
    </div>
    <div class="col-md-8">
        <?php include __DIR__ . '/student_list.php'; ?>
    </div>
</div>