<?php include('includes/header.php'); ?>

<style>
.hero-banner {
    background: url('img/banner.png') no-repeat center center;
    background-size: cover;
    height: 85vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
}

.hero-content {
    background: rgba(0, 0, 0, 0.55);
    padding: 40px 60px;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.hero-content h1 {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 30px;
}

.hero-content a {
    background: #007bff;
    color: white;
    padding: 12px 28px;
    text-decoration: none;
    font-weight: 500;
    border-radius: 30px;
    transition: 0.3s;
}

.hero-content a:hover {
    background: #0056b3;
}
</style>

<div class="hero-banner">
    <div class="hero-content">
        <h1>Thuê xe dễ dàng, an toàn và tiện lợi</h1>
        <p>Đặt xe trong 3 phút, nhận xe trong 30 phút – nhanh hơn bao giờ hết!</p>
        <a href="login.php">Bắt đầu ngay</a>
    </div>
</div>

<section class="featured">
    <div class="container">
        <h2>🚘 Xe nổi bật</h2>
        <div class="car-grid">
            <div class="car-card">
                <img src="https://vietwheels-ruby.s3.amazonaws.com/uploads/picture/url/68773/big_toyota-vios-1-5e-mt-3-tui-khi-an-giang-huyen-an-phu-1219." alt="Toyota Vios">
                <h3>Toyota Vios</h3>
                <p>Giá thuê: <b>700.000đ/ngày</b></p>
                <a href="login.php" class="btn-book">Thuê ngay</a>
            </div>

            <div class="car-card">
                <img src="https://images6.alphacoders.com/893/893710.jpg" alt="Mazda CX5">
                <h3>Mazda CX-5</h3>
                <p>Giá thuê: <b>1.000.000đ/ngày</b></p>
                <a href="login.php" class="btn-book">Thuê ngay</a>
            </div>

            <div class="car-card">
                <img src="https://www.hdwallpapers.in/download/blue_audi_a4_45_tfsi_quattro_s_line_2020_4k_5k_hd_cars-2560x1440.jpg" alt="Audi A4">
                <h3>Audi A4</h3>
                <p>Giá thuê: <b>2.200.000đ/ngày</b></p>
                <a href="login.php" class="btn-book">Thuê ngay</a>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>
