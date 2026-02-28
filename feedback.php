<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN') {
    header("Location: ../login.php");
    exit;
}
require '../includes/config.php';
include '../includes/header.php';

// Truy vấn danh sách feedback từ bảng DanhGia, kèm theo thông tin khách hàng và sản phẩm
$stmt = $pdo->query("SELECT dg.*, kh.TenKH, sp.TenSP 
                     FROM DanhGia dg 
                     JOIN KhachHang kh ON dg.MaKH = kh.MaKH 
                     JOIN SanPham sp ON dg.MaSP = sp.MaSP 
                     ORDER BY dg.NgayDanhGia DESC");
$feedbacks = $stmt->fetchAll();
?>

<div class="container mt-4">
   <h1>Danh sách Feedback từ khách hàng</h1>
   <?php if (count($feedbacks) > 0): ?>
       <table class="table table-bordered">
           <thead>
               <tr>
                   <th>Mã Feedback</th>
                   <th>Khách hàng</th>
                   <th>Sản phẩm</th>
                   <th>Số sao</th>
                   <th>Bình luận</th>
                   <th>Ngày đánh giá</th>
               </tr>
           </thead>
           <tbody>
           <?php foreach ($feedbacks as $fb): ?>
               <tr>
                   <td><?php echo $fb['MaKH'] . '-' . $fb['MaSP']; ?></td>
                   <td><?php echo htmlspecialchars($fb['TenKH']); ?></td>
                   <td><?php echo htmlspecialchars($fb['TenSP']); ?></td>
                   <td>
                     <?php 
                        // Hiển thị số sao dưới dạng icon
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $fb['SoSao']) 
                                  ? '<i class="fas fa-star text-warning"></i>' 
                                  : '<i class="far fa-star text-warning"></i>';
                        }
                     ?>
                   </td>
                   <td><?php echo nl2br(htmlspecialchars($fb['BinhLuan'])); ?></td>
                   <td><?php echo $fb['NgayDanhGia']; ?></td>
               </tr>
           <?php endforeach; ?>
           </tbody>
       </table>
   <?php else: ?>
       <p>Không có feedback nào.</p>
   <?php endif; ?>
   <a href="index.php" class="btn btn-secondary">Quay lại Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>