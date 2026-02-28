<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN') {
    header("Location: ../login.php");
    exit;
}
require '../includes/config.php';
// Lấy danh sách đơn hàng từ HoaDon cùng với thông tin khách hàng từ bảng KhachHang
$stmt = $pdo->query("SELECT h.*, kh.TenKH 
                     FROM HoaDon h 
                     JOIN KhachHang kh ON h.MaKH = kh.MaKH 
                     ORDER BY h.NgayLap DESC");
$orders = $stmt->fetchAll();
include '../includes/header.php';
?>
<div class="container mt-4">
  <h1>Quản lý đơn hàng</h1>
  <?php if(count($orders) > 0): ?>
    <table class="table table-bordered">
       <thead>
         <tr>
           <th>Mã HĐ</th>
           <th>Khách hàng</th>
           <th>Ngày lập</th>
           <th>Tổng tiền</th>
           <th>Voucher</th>
           <th>Chi tiết</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach($orders as $order): ?>
           <tr>
             <td><?php echo $order['MaHD']; ?></td>
             <td><?php echo htmlspecialchars($order['TenKH']); ?></td>
             <td><?php echo $order['NgayLap']; ?></td>
             <td><?php echo number_format($order['TongTien'],2); ?></td>
             <td><?php echo $order['MaVoucher'] ? $order['MaVoucher'] : '-'; ?></td>
             <td>
                <a href="order_detail.php?order_id=<?php echo $order['MaHD']; ?>" class="btn btn-sm btn-primary">Xem</a>
             </td>
           </tr>
         <?php endforeach; ?>
       </tbody>
    </table>
  <?php else: ?>
    <p>Không có đơn hàng nào.</p>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>