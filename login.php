
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="assets/login.css">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="login-container">
    <div class="illustration-section">
        <a href="index.php" class="premium-home-btn" title="Quay về Trang chủ">
            <i class="fa-solid fa-left-long" aria-hidden="true"></i>
            <span>Trang chủ</span>
        </a>

        <div class="greeting-content">
            <h1 class="greeting-title">Chào mừng bạn </h1>
            <p class="greeting-text">
                Nền tảng thuê xe tự lái tiện lợi: đặt xe trong vài phút, nhận xe linh hoạt tại nơi bạn muốn. Đa dạng dòng xe, giá minh bạch và bảo hiểm đầy đủ.
            </p>
        </div>
        <img src="img/a1.png"  class="illustration-image">
    </div>

    <div class="login-form-section">
        <h1 class="login-title">Đăng Nhập</h1>

        <form method="POST" action="handle/login_process.php" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="username" placeholder="Nhập Email của bạn" >
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" class="password-input" name="password" placeholder="Nhập Mật khẩu của bạn">
                    <button type="button" class="password-toggle" id="togglePassword" aria-pressed="false">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <a href="#" class="forgot-password">Quên Mật khẩu?</a>

            <button type="submit" class="login-btn" name="login">ĐĂNG NHẬP</button>
        </form>

        <div class="signup-link">
            Chưa có tài khoản? <a href="#">Đăng ký ngay</a>
        </div>
    </div>
</div>

<script>
    const btn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const passwordInput = document.getElementById('password');

    if (btn && eyeIcon && passwordInput) {
        btn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye', !isHidden);
            eyeIcon.classList.toggle('fa-eye-slash', isHidden);
            btn.setAttribute('aria-pressed', String(isHidden));
        });
    }
</script>

