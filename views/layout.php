<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'ระบบบันทึกกิจกรรม NPRU'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f0f2f5;
            /* สีพื้นหลังให้อ่อนลง */
        }

        .navbar-npru {
            background: linear-gradient(90deg, #003366 0%, #0056b3 100%);
            /* ไล่สีน้ำเงินเข้ม */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .main-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-top: -40px;
            /* ดึงขึ้นไปทับ Navbar เล็กน้อย */
            position: relative;
            z-index: 10;
        }

        .page-title {
            color: #003366;
            font-weight: 700;
            border-left: 5px solid #ffc107;
            /* แถบสีเหลืองทอง */
            padding-left: 15px;
        }

        .card-premium {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }

        .card-premium:hover {
            transform: translateY(-3px);
        }

        .card-header-premium {
            background-color: #fff;
            border-bottom: 2px solid #f0f2f5;
            padding: 1.25rem;
            border-radius: 12px 12px 0 0 !important;
        }
    </style>
</head>

<body class="bg-light min-h-screen d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-npru py-3 pb-5">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_PcSRte2hkgRFwdq0nWAi0VpEk1slS7930jPDN9zU&s" alt="NPRU Logo" height="60" class="me-3 bg-white rounded-circle p-1">
                <div>
                    <div class="fw-bold fs-5">มหาวิทยาลัยราชภัฏนครปฐม</div>
                    <div class="fs-6 fw-light opacity-75">ระบบบันทึกกิจกรรมนักศึกษา</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 <?php echo (!isset($_GET['page']) || $_GET['page'] == 'activity') ? 'active bg-primary bg-opacity-50 fw-bold rounded-pill' : ''; ?>"
                            href="index.php?page=activity">
                            <i class="bi bi-calendar-check me-1"></i> กิจกรรม
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link text-white px-3 <?php echo (isset($_GET['page']) && $_GET['page'] == 'student') ? 'active bg-primary bg-opacity-50 fw-bold rounded-pill' : ''; ?>"
                            href="index.php?page=student">
                            <i class="bi bi-people-fill me-1"></i> นักศึกษา
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="main-container">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 page-title">
                <?php echo htmlspecialchars($title ?? 'ระบบบันทึกกิจกรรม'); ?>
            </h2>
            <?php echo $content ?? ''; ?>
        </div>
    </div>

    <footer class="mt-auto py-3 bg-white text-center text-muted small shadow-sm">
        © <?php echo date('Y'); ?> Nakhon Pathom Rajabhat University. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>