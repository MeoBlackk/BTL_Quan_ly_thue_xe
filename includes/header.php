<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thuê Xe Tự Lái - CarVip Style</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="container nav-container">
            <h2 class="logo">Car<span>Renter</span></h2>
            <nav class="nav-links">
                <a href="index.php" class="active">Trang chủ</a>
                <a href="#">Xe cho thuê</a>
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
                <a href="login.php" class="btn-login">Đăng nhập</a>
            </nav>
        </div>
        <script>
          window.addEventListener('scroll', function() {
          const navbar = document.querySelector('.navbar');
          if (window.scrollY > 40) {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = '0 3px 12px rgba(0,0,0,0.08)';
          } else {
              navbar.style.background = 'linear-gradient(90deg, #f7faff, #e9f3ff)';
              navbar.style.boxShadow = '0 4px 10px rgba(0,0,0,0.05)';
          }
        });
      </script>
    </header>
