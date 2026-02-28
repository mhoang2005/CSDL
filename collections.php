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
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM BoSuuTap WHERE MaBST = ?");
    $stmt->execute([$id]);
    header("Location: collections.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($action == 'add'){
        $tenbst = $_POST['tenbst'];
        $mota = $_POST['mota'];
        $stmt = $pdo->prepare("INSERT INTO BoSuuTap (TenBST, MoTa) VALUES (?, ?)");
        $stmt->execute([$tenbst, $mota]);
        header("Location: collections.php");
        exit;
    }
    if($action == 'edit' && isset($_GET['id'])){
        $id = intval($_GET['id']);
        $tenbst = $_POST['tenbst'];
        $mota = $_POST['mota'];
        $stmt = $pdo->prepare("UPDATE BoSuuTap SET TenBST = ?, MoTa = ? WHERE MaBST = ?");
        $stmt->execute([$tenbst, $mota, $id]);
        header("Location: collections.php");
        exit;
    }
}
?>
<div class="container mt-4">
  <h1>Quản lý Bộ Sưu Tập</h1>
  <?php if($action == 'add' || ($action == 'edit' && isset($_GET['id']))): ?>
    <?php 
      $editData = null;
      if($action == 'edit'){
          $id = intval($_GET['id']);
          $stmt = $pdo->prepare("SELECT * FROM BoSuuTap WHERE MaBST = ?");
          $stmt->execute([$id]);
          $editData = $stmt->fetch();
      }
    ?>
    <form method="POST">
      <div class="form-group">
        <label>Tên Bộ Sưu Tập</label>
        <input type="text" name="tenbst" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['TenBST']) : ''; ?>" required>
      </div>
      <div class="form-group">
        <label>Mô tả</label>
        <textarea name="mota" class="form-control" required><?php echo $editData ? htmlspecialchars($editData['MoTa']) : ''; ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary"><?php echo ($action=='add') ? 'Thêm' : 'Cập nhật'; ?></button>
      <a href="collections.php" class="btn btn-secondary">Hủy</a>
    </form>
  <?php else: ?>
    <a href="collections.php?action=add" class="btn btn-success mb-3">Thêm Bộ Sưu Tập</a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Mã BST</th>
          <th>Tên BST</th>
          <th>Mô tả</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $stmt = $pdo->query("SELECT * FROM BoSuuTap");
      while($row = $stmt->fetch()){
          echo "<tr>";
          echo "<td>" . $row['MaBST'] . "</td>";
          echo "<td>" . htmlspecialchars($row['TenBST']) . "</td>";
          echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
          echo "<td>
            <a href='collections.php?action=edit&id=" . $row['MaBST'] . "' class='btn btn-warning btn-sm'>Sửa</a> 
            <a href='collections.php?action=delete&id=" . $row['MaBST'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Bạn có chắc muốn xóa không?');\">Xóa</a>
          </td>";
          echo "</tr>";
      }
      ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>