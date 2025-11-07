<?php
session_start();
// Nếu người dùng đã đăng nhập, chuyển hướng theo vai trò phù hợp
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'NHANVIEN') {
        header("Location: admin/index.php");
        exit;
    } else {
        header("Location: customer/index.php");
        exit;
    }
}
include 'includes/header.php';
?>
<!-- Hero Section với hiệu ứng Parallax và Overlay Gradient -->
<section class="hero-section" style="background: url('/assets/images/banner.jpg') no-repeat center center fixed; background-size: cover; height: 600px; position: relative;">
  <div class="hero-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.3));"></div>
  <div class="hero-content container text-center" style="position: relative; top: 40%; transform: translateY(-40%);" data-aos="fade-up">
      <!-- Tăng kích thước chữ (ví dụ 4rem) và sử dụng font-weight cao -->
      <h1 class="display-4 text-white font-weight-bold animate__animated animate__fadeInDown" style="font-size: 4rem;">
         Chào mừng đến với Đăng bán giày
      </h1>
      <!-- Tăng kích thước dòng giới thiệu (ví dụ 1.5rem) -->
      <p class="lead text-white animate__animated animate__fadeInUp" style="font-size: 1.5rem;">
         Nơi phong cách và chất lượng hòa quyện, giúp bạn tỏa sáng trong từng bước chân.
      </p>
      <div class="mt-4">
          <a href="/login.php" class="btn btn-lg btn-primary animate__animated animate__fadeInUp">Đăng nhập</a>
          <a href="/register.php" class="btn btn-lg btn-outline-light animate__animated animate__fadeInUp">Đăng ký ngay</a>
      </div>
  </div>
</section>

<!-- About Section -->
<section class="about-section py-5" data-aos="fade-up">
  <div class="container">
      <h2 class="text-center mb-4">Giới thiệu về chúng tôi</h2>
      <div class="row align-items-center">
         <div class="col-md-6">
           <img src="/assets/images/intro.jpg" class="img-fluid rounded shadow" alt="Giới thiệu">
         </div>
         <div class="col-md-6">
           <p class="lead text-justify" style="font-size: 1.5rem; line-height: 1.8rem;">
             Tại shop "Đăng bán giày" chúng tôi luôn cập nhật những mẫu giày thời trang độc đáo, 
             chất lượng vượt trội cùng phong cách hiện đại. Tự tin tỏa sáng mỗi ngày với những đôi giày 
             được lựa chọn kỹ càng và dịch vụ khách hàng chuyên nghiệp.
           </p>
         </div>
      </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- Thư viện AOS cho hiệu ứng scroll -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    easing: 'ease-in-out',
    once: true
  });
</script>