<?php
session_start();
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Truy vấn tài khoản từ bảng TaiKhoan (lưu ý: không mã hoá mật khẩu cho ví dụ đơn giản)
  $stmt = $pdo->prepare("SELECT * FROM TaiKhoan WHERE TenDangNhap = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();
  
  if ($user && $user['MatKhau'] == $password) {
      $_SESSION['username'] = $user['TenDangNhap'];
      $_SESSION['role'] = $user['VaiTro'];
      
      // Chuyển đến giao diện phù hợp với vai trò
      if ($user['VaiTro'] == 'NHANVIEN') {
          header("Location: admin/index.php");
      } else {
          header("Location: customer/index.php");
      }
      exit;
  } else {
      $error = "Sai tên đăng nhập hoặc mật khẩu.";
  }
}
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Đăng Nhập</h2>
  <?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="form-group">
      <label>Tên đăng nhập:</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Mật khẩu:</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Đăng nhập</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>