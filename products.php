<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN') {
    header("Location: ../login.php");
    exit;
}
require '../includes/config.php';
include '../includes/header.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Xử lý yêu cầu POST cho thêm/sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Xử lý thêm sản phẩm mới
    if ($action == 'add') {
        $tensp = $_POST['tensp'];
        $mota  = $_POST['mota'];
        $gia   = $_POST['gia'];
        $mabst = $_POST['mabst'];

        // Chèn thông tin sản phẩm vào bảng SanPham
        $stmt = $pdo->prepare("INSERT INTO SanPham (TenSP, MoTa, Gia, MaBST) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tensp, $mota, $gia, $mabst]);
        // Lấy ID của sản phẩm vừa chèn
        $maSP = $pdo->lastInsertId();

        // Kiểm tra file upload ảnh sản phẩm (nếu có)
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $ext     = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $newName = uniqid() . '.' . $ext;
                $uploadPath = '../assets/images/' . $newName;
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    // Xây dựng URL ảnh dựa trên cấu hình máy chủ (điều chỉnh nếu cần)
                    $url = "http://dangbangiay.42web.io/assets/images/" . $newName;
                    $stmtImg = $pdo->prepare("INSERT INTO AnhSanPham (MaSP, URL) VALUES (?, ?)");
                    $stmtImg->execute([$maSP, $url]);
                }
            }
        }

        header("Location: products.php");
        exit;
    }

    // Xử lý cập nhật sản phẩm hiện có
    if ($action == 'edit' && isset($_GET['id'])) {
        $id    = intval($_GET['id']);
        $tensp = $_POST['tensp'];
        $mota  = $_POST['mota'];
        $gia   = $_POST['gia'];
        $mabst = $_POST['mabst'];

        $stmt = $pdo->prepare("UPDATE SanPham SET TenSP = ?, MoTa = ?, Gia = ?, MaBST = ? WHERE MaSP = ?");
        $stmt->execute([$tensp, $mota, $gia, $mabst, $id]);

        // Xử lý upload ảnh mới (nếu có) cho sản phẩm được sửa
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $ext     = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $newName = uniqid() . '.' . $ext;
                $uploadPath = '../assets/images/' . $newName;
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    $url = "http://dangbangiay.42web.io/assets/images/" . $newName;
                    // Kiểm tra xem sản phẩm đã có ảnh chưa
                    $stmtCheck = $pdo->prepare("SELECT * FROM AnhSanPham WHERE MaSP = ?");
                    $stmtCheck->execute([$id]);
                    $existingImage = $stmtCheck->fetch();
                    if ($existingImage) {
                        // Nếu đã có, update lại URL ảnh (bổ sung xoá file cũ nếu cần)
                        $stmtUpdateImg = $pdo->prepare("UPDATE AnhSanPham SET URL = ? WHERE MaSP = ?");
                        $stmtUpdateImg->execute([$url, $id]);
                    } else {
                        // Nếu chưa có, insert bản ghi ảnh mới
                        $stmtInsertImg = $pdo->prepare("INSERT INTO AnhSanPham (MaSP, URL) VALUES (?, ?)");
                        $stmtInsertImg->execute([$id, $url]);
                    }
                }
            }
        }

        header("Location: products.php");
        exit;
    }
}

// Xử lý xóa sản phẩm
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM SanPham WHERE MaSP = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit;
}
?>
<div class="container mt-4">
  <h1>Quản lý sản phẩm</h1>
  <?php if ($action == 'add' || ($action == 'edit' && isset($_GET['id']))): ?>
    <?php 
      $editData = null;
      if ($action == 'edit') {
          $id = intval($_GET['id']);
          $stmt = $pdo->prepare("SELECT * FROM SanPham WHERE MaSP = ?");
          $stmt->execute([$id]);
          $editData = $stmt->fetch();
      }
    ?>
    <!-- Form được thiết lập enctype để hỗ trợ upload file -->
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Tên sản phẩm</label>
        <input type="text" name="tensp" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['TenSP']) : ''; ?>" required>
      </div>
      <div class="form-group">
        <label>Mô tả</label>
        <textarea name="mota" class="form-control" required><?php echo $editData ? htmlspecialchars($editData['MoTa']) : ''; ?></textarea>
      </div>
      <div class="form-group">
        <label>Giá</label>
        <input type="number" name="gia" class="form-control" step="0.01" value="<?php echo $editData ? htmlspecialchars($editData['Gia']) : ''; ?>" required>
      </div>
      <div class="form-group">
        <label>Mã Bộ Sưu Tập</label>
        <input type="number" name="mabst" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['MaBST']) : ''; ?>" required>
      </div>
      <!-- Trường upload ảnh cho sản phẩm -->
      <div class="form-group">
        <label>Ảnh sản phẩm (Chọn file nếu muốn tải lên ảnh mới):</label>
        <input type="file" name="product_image" class="form-control-file">
      </div>
      <button type="submit" class="btn btn-primary"><?php echo ($action == 'add') ? 'Thêm' : 'Cập nhật'; ?></button>
      <a href="products.php" class="btn btn-secondary">Hủy</a>
    </form>
  <?php else: ?>
    <a href="products.php?action=add" class="btn btn-success mb-3">Thêm sản phẩm</a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Mã SP</th>
          <th>Tên SP</th>
          <th>Mô tả</th>
          <th>Giá</th>
          <th>Mã BST</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $stmt = $pdo->query("SELECT * FROM SanPham");
      while ($row = $stmt->fetch()){
          echo "<tr>";
          echo "<td>" . $row['MaSP'] . "</td>";
          echo "<td>" . htmlspecialchars($row['TenSP']) . "</td>";
          echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
          echo "<td>" . number_format($row['Gia'], 2) . "</td>";
          echo "<td>" . $row['MaBST'] . "</td>";
          echo "<td>
                  <a href='products.php?action=edit&id=" . $row['MaSP'] . "' class='btn btn-warning btn-sm'>Sửa</a>
                  <a href='products.php?action=delete&id=" . $row['MaSP'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Bạn có chắc muốn xóa không?');\">Xóa</a>
                </td>";
          echo "</tr>";
      }
      ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>