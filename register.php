<?php
session_start();
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  // Kiểm tra tên đăng nhập đã tồn tại hay chưa
  $stmt = $pdo->prepare("SELECT * FROM TaiKhoan WHERE TenDangNhap = ?");
  $stmt->execute([$username]);
  if($stmt->fetch()){
      $error = "Tên đăng nhập đã tồn tại.";
  } else {
      $stmt = $pdo->prepare("INSERT INTO TaiKhoan (TenDangNhap, MatKhau, VaiTro) VALUES (?, ?, ?)");
      $stmt->execute([$username, $password, $role]);

      if($role == 'KHACH'){
          $tenkh = $_POST['tenkh'];
          $email = $_POST['email'];
          $sdt = $_POST['sdt'];
          $stmt = $pdo->prepare("INSERT INTO KhachHang (TenKH, Email, SDT, TenDangNhap) VALUES (?, ?, ?, ?)");
          $stmt->execute([$tenkh, $email, $sdt, $username]);
      } elseif($role == 'NHANVIEN'){
          $tennv = $_POST['tennv'];
          $email = $_POST['email'];
          $stmt = $pdo->prepare("INSERT INTO NhanVien (TenNV, Email, TenDangNhap) VALUES (?, ?, ?)");
          $stmt->execute([$tennv, $email, $username]);
      }
      header("Location: login.php");
      exit;
  }
}
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Đăng Ký</h2>
  <?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>
  <form method="post" id="registerForm">
    <div class="form-group">
      <label>Tên đăng nhập:</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Mật khẩu:</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Chọn loại tài khoản:</label>
      <select name="role" id="roleSelect" class="form-control" required>
          <option value="KHACH">Khách hàng</option>
          <option value="NHANVIEN">Quản lý</option>
      </select>
    </div>
    <div id="khachFields">
      <div class="form-group">
        <label>Họ và tên:</label>
        <input type="text" name="tenkh" class="form-control">
      </div>
      <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="form-group">
        <label>Số điện thoại:</label>
        <input type="text" name="sdt" class="form-control">
      </div>
    </div>
    <div id="nhanvienFields" style="display: none;">
      <div class="form-group">
        <label>Tên nhân viên:</label>
        <input type="text" name="tennv" class="form-control">
      </div>
      <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" class="form-control">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Đăng ký</button>
  </form>
</div>
<script>
  document.getElementById('roleSelect').addEventListener('change', function(){
    if(this.value === 'KHACH'){
      document.getElementById('khachFields').style.display = 'block';
      document.getElementById('nhanvienFields').style.display = 'none';
    } else {
      document.getElementById('khachFields').style.display = 'none';
      document.getElementById('nhanvienFields').style.display = 'block';
    }
  });
</script>
<?php include 'includes/footer.php'; ?>