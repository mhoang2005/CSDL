<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN') {
    header("Location: ../login.php");
    exit;
}
include '../includes/header.php';
?>

<div class="container mt-5">
  <!-- Jumbotron tiêu đề -->
  <div class="jumbotron jumbotron-fluid bg-dark text-white shadow-lg rounded">
    <div class="container text-center">
      <h1 class="display-3 font-weight-bold">Trang Quản Lý</h1>
      <p class="lead">Hệ thống điều hành chuyên nghiệp – Quản lý và theo dõi mọi hoạt động một cách trực quan.</p>
    </div>
  </div>
  
  <!-- Các card quản lý -->
  <div class="row">
    <!-- Quản lý sản phẩm -->
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <i class="fas fa-box-open fa-3x text-primary mb-3"></i>
          <h5 class="card-title">Quản lý sản phẩm</h5>
          <p class="card-text">Cập nhật, thêm mới và chỉnh sửa các sản phẩm giày.</p>
          <a href="products.php" class="btn btn-outline-primary btn-block">Xem ngay</a>
        </div>
      </div>
    </div>
    <!-- Quản lý bộ sưu tập -->
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <i class="fas fa-layer-group fa-3x text-success mb-3"></i>
          <h5 class="card-title">Quản lý bộ sưu tập</h5>
          <p class="card-text">Sắp xếp và quản lý các bộ sưu tập giày độc đáo.</p>
          <a href="collections.php" class="btn btn-outline-success btn-block">Xem ngay</a>
        </div>
      </div>
    </div>
    <!-- Quản lý voucher -->
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <i class="fas fa-ticket-alt fa-3x text-warning mb-3"></i>
          <h5 class="card-title">Quản lý voucher</h5>
          <p class="card-text">Điều hành và cập nhật các chương trình khuyến mãi.</p>
          <a href="vouchers.php" class="btn btn-outline-warning btn-block">Xem ngay</a>
        </div>
      </div>
    </div>
    
    <!-- Quản lý size -->
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <i class="fas fa-ruler fa-3x text-secondary mb-3"></i>
          <h5 class="card-title">Quản lý Size</h5>
          <p class="card-text">Quản lý các kích thước giày cho sản phẩm.</p>
          <a href="size_management.php" class="btn btn-outline-secondary btn-block">Xem ngay</a>
        </div>
      </div>
    </div>
    
    <!-- Quản lý đơn hàng -->
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <i class="fas fa-shopping-cart fa-3x text-info mb-3"></i>
          <h5 class="card-title">Quản lý đơn hàng</h5>
          <p class="card-text">Theo dõi và xử lý các đơn hàng từ khách hàng.</p>
          <a href="orders.php" class="btn btn-outline-info btn-block">Xem ngay</a>
        </div>
      </div>
    </div>
    
    <!-- Đăng xuất -->
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
          <h5 class="card-title">Đăng xuất</h5>
          <p class="card-text">Thoát khỏi hệ thống quản trị.</p>
          <a href="../logout.php" class="btn btn-outline-danger btn-block">Đăng xuất</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>