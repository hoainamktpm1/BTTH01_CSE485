<?php
declare(strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id){
    redirect('author.php', ['failure' => 'Không tìm thấy tác giả']);
}

$result = false;
$sql = "SELECT ten_tgia, hinh_tgia FROM tacgia WHERE ma_tgia = :ma_tgia";
$result = pdo($pdo, $sql, ['ma_tgia' => $id])->fetch();
if(!$result){
    redirect('author.php', ['failure' => 'Không tìm thấy tác giả']);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sql = "DELETE FROM baiviet WHERE ma_tgia = :ma_tgia";
    pdo($pdo, $sql, ['ma_tgia' => $id]);
    $sql = "DELETE FROM tacgia WHERE ma_tgia = :ma_tgia";
    pdo($pdo, $sql, ['ma_tgia' => $id]);
    unlink('../images/authors/'.$result['hinh_tgia']);
    redirect('author.php', ['success' => 'Đã xóa tác giả']);
}
?>
<?php $title = "Xoá tác giả"; require '../includes/header_admin.php'; ?>
    <main class="container admin d-flex justify-content-center" id="content" style="min-height: 50vh; margin-top: 200px">
        <form action="delete_author.php?id=<?= $id; ?>" method="POST" class="narrow">
            <h1 class="m-3">Xóa tác giả</h1>
            <p>Bạn có chắc chắn muốn xóa tác giả: <em><?= html_escape($result['ten_tgia']) ?></em>?</p>
            <div class="row" margin>
                <div class="col-6">
                    <input type="submit" name="delete" value="Xóa" class="btn btn-danger">
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="author.php" class="btn btn-warning">Quay lại</a>
                </div>
            </div>
        </form>
    </main>
<?php include '../includes/footer.php'; ?>