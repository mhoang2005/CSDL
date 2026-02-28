<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN') {
    header("Location: ../login.php");
    exit;
}
require '../includes/config.php';

if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// Lấy thông tin hoá đơn kèm theo tên khách hàng
$stmtOrder = $pdo->prepare("SELECT h.*, kh.TenKH FROM HoaDon h JOIN KhachHang kh ON h.MaKH = kh.MaKH WHERE h.MaHD = ?");
$stmtOrder->execute([$order_id]);
$order = $stmtOrder->fetch();
if (!$order) {
    echo "Không tìm thấy đơn hàng!";
    exit;
}

// Lấy thông tin địa chỉ giao hàng từ bảng DiaChi dựa trên MaDC
$stmtAddr = $pdo->prepare("SELECT * FROM DiaChi WHERE MaDC = ?");
$stmtAddr->execute([$order['MaDC']]);
$address = $stmtAddr->fetch();

// Lấy chi tiết đơn hàng từ bảng ChiTietHoaDon, kết hợp với thông tin sản phẩm và kích cỡ
$stmtDetails = $pdo->prepare("SELECT dthd.*, sp.TenSP, s.SoSize 
                              FROM ChiTietHoaDon dthd
                              JOIN SanPham sp ON dthd.MaSP = sp.MaSP
                              JOIN Size s ON dthd.MaSize = s.MaSize
                              WHERE dthd.MaHD = ?");
$stmtDetails->execute([$order_id]);
$details = $stmtDetails->fetchAll();

include '../includes/header.php';
?>
<div class="container mt-4">
  <h1>Chi tiết đơn hàng #<?php echo $order['MaHD']; ?></h1>
  <div class="mb-3">
      <strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['TenKH']); ?><br>
      <strong>Ngày lập:</strong> <?php echo $order['NgayLap']; ?><br>
      <strong>Tổng tiền:</strong> <?php echo number_format($order['TongTien'],2); ?><br>
      <strong>Voucher áp dụng:</strong> <?php echo $order['MaVoucher'] ? $order['MaVoucher'] : "-"; ?>
  </div>
  <?php if ($address): ?>
  <div class="mb-3">
      <h4>Thông tin giao hàng</h4>
      <p><strong>Địa chỉ:</strong> <?php echo $address['DiaChiChiTiet']; ?></p>
      <p><strong>Ghi chú:</strong> <?php echo $address['GhiChu']; ?></p>
  </div>
  <?php endif; ?>
  <h4>Chi tiết sản phẩm</h4>
  <table class="table table-bordered">
     <thead>
         <tr>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
         </tr>
     </thead>
     <tbody>
         <?php foreach($details as $detail): ?>
            <tr>
              <td><?php echo htmlspecialchars($detail['TenSP']); ?></td>
              <td><?php echo $detail['SoSize']; ?></td>
              <td><?php echo $detail['SoLuong']; ?></td>
              <td><?php echo number_format($detail['DonGia'],2); ?></td>
              <td><?php echo number_format($detail['SoLuong'] * $detail['DonGia'],2); ?></td>
            </tr>
         <?php endforeach; ?>
     </tbody>
  </table>
  <a href="orders.php" class="btn btn-secondary">Quay lại đơn hàng</a>
</div>
<?php include '../includes/footer.php'; ?>