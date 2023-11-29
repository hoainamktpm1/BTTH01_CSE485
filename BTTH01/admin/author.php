<?php
declare(strict_types=1);
require '../includes/database_connection.php';
require '../includes/functions.php';

$success = $_GET['success'] ?? null;
$failure = $_GET['failure'] ?? null;

$sql = "SELECT * FROM tacgia";

$rows = pdo($pdo, $sql)->fetchAll();

$title = "Tác giả";
?>

<?php
require '../includes/header_admin.php';
?>
<main class="container mt-5 mb-5">
    <div class="row">
        <div class="col-sm">
            <?php if ($success) { ?><div class="alert alert-success text-center"><?= $success ?></div><?php } ?>
            <?php if ($failure) { ?><div class="alert alert-danger"><?= $failure ?></div><?php } ?>
            <a href="add_author.php" class="btn btn-success">Thêm mới</a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tên tác giả</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Sửa</th>
                        <th scope="col">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) { ?>
                        <tr>
                            <th scope="row"><?= html_escape($row['ma_tgia']); ?></th>
                            <td><?= html_escape($row['ten_tgia']); ?></td>
                            <td><img src="../images/authors/<?= $row['hinh_tgia'] ?>" alt="Đây là hình tác giả" width = 100px></td>
                            <td><a href="edit_author.php?id=<?= html_escape($row['ma_tgia']) ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="delete_author.php?id=<?= html_escape($row['ma_tgia']) ?>"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
include '../includes/footer.php';
?>