<?php
// Bật hiển thị lỗi để debug (sau này có thể tắt)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'NHANVIEN') {
    header("Location: ../login.php");
    exit;
}

require '../includes/config.php';
include '../includes/header.php';

// Lấy danh sách sản phẩm từ bảng SanPham
$products = $pdo->query("SELECT MaSP, TenSP FROM SanPham ORDER BY TenSP ASC")->fetchAll(PDO::FETCH_ASSOC);

// Xác định sản phẩm được chọn qua GET; nếu không được chọn thì chọn sản phẩm đầu tiên (nếu có)
if (isset($_GET['MaSP'])) {
    $selectedProduct = intval($_GET['MaSP']);
} else {
    $selectedProduct = count($products) > 0 ? $products[0]['MaSP'] : 0;
}

// Xử lý cập nhật số lượng size cho sản phẩm được chọn
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_sizes'])) {
    $maSP = intval($_POST['MaSP']);
    // Duyệt qua mảng số lượng được gửi từ form, key là MaSize, value là số lượng
    foreach ($_POST['SoLuong'] as $maSize => $soLuong) {
        $soLuong = intval($soLuong);
        // Kiểm tra xem record đã tồn tại trong SanPham_ChiTiet chưa
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM SanPham_ChiTiet WHERE MaSP = ? AND MaSize = ?");
        $stmt->execute([$maSP, $maSize]);
        if ($stmt->fetchColumn() > 0) {
            // Nếu tồn tại, cập nhật số lượng
            $updateStmt = $pdo->prepare("UPDATE SanPham_ChiTiet SET SoLuong = ? WHERE MaSP = ? AND MaSize = ?");
            $updateStmt->execute([$soLuong, $maSP, $maSize]);
        } else {
            // Nếu không tồn tại, chèn mới nếu số lượng > 0
            if ($soLuong > 0) {
                $insertStmt = $pdo->prepare("INSERT INTO SanPham_ChiTiet (MaSP, MaSize, SoLuong) VALUES (?, ?, ?)");
                $insertStmt->execute([$maSP, $maSize, $soLuong]);
            }
        }
    }
    header("Location: size_management.php?MaSP=" . $maSP);
    exit;
}

// Lấy danh sách các size từ bảng Size và số lượng hiện có từ SanPham_ChiTiet cho sản phẩm được chọn
$sizes = $pdo->query("SELECT s.MaSize, s.SoSize, IFNULL(pt.SoLuong, 0) AS SoLuong
                      FROM Size s
                      LEFT JOIN SanPham_ChiTiet pt ON s.MaSize = pt.MaSize AND pt.MaSP = $selectedProduct
                      ORDER BY s.SoSize ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1>Quản lý Size cho sản phẩm</h1>
    
    <!-- Form chọn sản phẩm -->
    <form method="GET" class="mb-4">
        <div class="form-group">
            <label for="MaSP">Chọn sản phẩm:</label>
            <select name="MaSP" id="MaSP" class="form-control" onchange="this.form.submit()">
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['MaSP']; ?>" <?php if($product['MaSP'] == $selectedProduct) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($product['TenSP']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    
    <?php if ($selectedProduct): ?>
    <form method="POST">
        <input type="hidden" name="MaSP" value="<?php echo $selectedProduct; ?>">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Size giày</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sizes)): ?>
                    <?php foreach ($sizes as $size): ?>
                        <tr>
                            <td><?php echo $size['SoSize']; ?></td>
                            <td>
                                <input type="number" name="SoLuong[<?php echo $size['MaSize']; ?>]" value="<?php echo $size['SoLuong']; ?>" class="form-control" min="0">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">Không có kích thước nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="submit" name="update_sizes" class="btn btn-primary">Cập nhật kích thước</button>
    </form>
    <?php else: ?>
        <p>Không có sản phẩm nào.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>