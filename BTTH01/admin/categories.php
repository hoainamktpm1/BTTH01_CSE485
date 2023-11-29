<?php
declare (strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';

$title = "Thể loại";

$success = $_GET['success'] ?? null;
$failure = $_GET['failure'] ?? null;

$sql = "SELECT ma_tloai, ten_tloai, SLBaiViet FROM theloai";
$categories = pdo($pdo, $sql)->fetchAll();
?>

<?php include '../includes/header_admin.php'; ?>
    <main class="container mt-4 mb-5">
        <div class="row">
            <div class="col-sm table-responsive-sm">
                <?php if ($success) { ?><div class="alert alert-success text-center"><?= $success ?></div><?php } ?>
                <?php if ($failure) { ?><div class="alert alert-danger"><?= $failure ?></div><?php } ?>
                <a href="category.php" class="btn btn-success mb-2">Thêm mới</a>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Mã thể loại</th>
                            <th>Tên thể loại</th>
                            <th>Số lượng bài viết</th>
                            <th>Sửa</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($categories as $category) {?>
                            <tr>
                                <th scope="row"><?= html_escape($category['ma_tloai']); ?></th>
                                <td><?= html_escape($category['ten_tloai']); ?> </td>
                                <td><?= html_escape($category['SLBaiViet']); ?> </td>
                                <td>
                                    <a href="category.php?id=<?= html_escape($category['ma_tloai']); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                </td>
                                <td>
                                    <a href="category-delete.php?id=<?= html_escape($category['ma_tloai']); ?>"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
<?php include '../includes/footer.php'; ?>
    