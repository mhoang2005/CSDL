<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN'){
    header("Location: ../login.php");
    exit;
}
require '../includes/config.php';
include '../includes/header.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == 'delete' && isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM Voucher WHERE MaVoucher = ?");
    $stmt->execute([$id]);
    header("Location: vouchers.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($action == 'add'){
        $maVoucher = $_POST['maVoucher'];
        $giamGia  = $_POST['giamGia'];
        $dieuKien = $_POST['dieuKien'];
        $hanSuDung = $_POST['hanSuDung'];
        $stmt = $pdo->prepare("INSERT INTO Voucher (MaVoucher, GiamGia, DieuKien, HanSuDung) VALUES (?, ?, ?, ?)");
        $stmt->execute([$maVoucher, $giamGia, $dieuKien, $hanSuDung]);
        header("Location: vouchers.php");
        exit;
    }
    if($action == 'edit' && isset($_GET['id'])){
        $maVoucher = $_GET['id'];
        $giamGia  = $_POST['giamGia'];
        $dieuKien = $_POST['dieuKien'];
        $hanSuDung = $_POST['hanSuDung'];
        $stmt = $pdo->prepare("UPDATE Voucher SET GiamGia = ?, DieuKien = ?, HanSuDung = ? WHERE MaVoucher = ?");
        $stmt->execute([$giamGia, $dieuKien, $hanSuDung, $maVoucher]);
        header("Location: vouchers.php");
        exit;
    }
}
?>
<div class="container mt-4">
  <h1>Quản lý Voucher</h1>
  <?php if($action == 'add' || ($action == 'edit' && isset($_GET['id']))): ?>
    <?php
      $editData = null;
      if($action == 'edit'){
          $maVoucher = $_GET['id'];
          $stmt = $pdo->prepare("SELECT * FROM Voucher WHERE MaVoucher = ?");
          $stmt->execute([$maVoucher]);
          $editData = $stmt->fetch();
      }
    ?>
    <form method="POST">
      <?php if($action == 'add'): ?>
      <div class="form-group">
        <label>Mã Voucher</label>
        <input type="text" name="maVoucher" class="form-control" required>
      </div>
      <?php else: ?>
      <p><strong>Mã Voucher: </strong><?php echo $editData['MaVoucher']; ?></p>
      <?php endif; ?>
      <div class="form-group">
        <label>Mức giảm (Giá trị)</label>
        <input type="number" name="giamGia" step="0.01" class="form-control" value="<?php echo $editData ? $editData['GiamGia'] : ''; ?>" required>
      </div>
      <div class="form-group">
        <label>Điều kiện đơn hàng</label>
        <input type="number" name="dieuKien" step="0.01" class="form-control" value="<?php echo $editData ? $editData['DieuKien'] : ''; ?>" required>
      </div>
      <div class="form-group">
        <label>Hạn Sử Dụng</label>
        <input type="date" name="hanSuDung" class="form-control" value="<?php echo $editData ? $editData['HanSuDung'] : ''; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary"><?php echo ($action=='add') ? 'Thêm' : 'Cập nhật'; ?></button>
      <a href="vouchers.php" class="btn btn-secondary">Hủy</a>
    </form>
  <?php else: ?>
    <a href="vouchers.php?action=add" class="btn btn-success mb-3">Thêm Voucher</a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Mã Voucher</th>
          <th>Mức Giảm</th>
          <th>Điều Kiện</th>
          <th>Hạn Sử Dụng</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $stmt = $pdo->query("SELECT * FROM Voucher");
      while($row = $stmt->fetch()){
          echo "<tr>";
          echo "<td>" . $row['MaVoucher'] . "</td>";
          echo "<td>" . number_format($row['GiamGia'],2) . "</td>";
          echo "<td>" . number_format($row['DieuKien'],2) . "</td>";
          echo "<td>" . $row['HanSuDung'] . "</td>";
          echo "<td>
            <a href='vouchers.php?action=edit&id=" . $row['MaVoucher'] . "' class='btn btn-warning btn-sm'>Sửa</a> 
            <a href='vouchers.php?action=delete&id=" . $row['MaVoucher'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Bạn có chắc muốn xóa không?');\">Xóa</a>
          </td>";
          echo "</tr>";
      }
      ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>