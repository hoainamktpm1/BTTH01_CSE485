<?php
declare(strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';

$title = 'Xóa bài viết';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id){
    redirect('articles.php', ['failure' => 'Không tìm thấy bài viết']);
}

$article = false;
$sql = "SELECT tieude, hinhanh FROM baiviet WHERE ma_bviet = :ma_bviet";
$article = pdo($pdo, $sql, ['ma_bviet' => $id])->fetch();
if(!$article){
    redirect('articles.php', ['failure' => 'Không tìm thấy bài viết']);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sql = "DELETE FROM baiviet WHERE ma_bviet = :ma_bviet";
    pdo($pdo, $sql, ['ma_bviet' => $id]);
    redirect('articles.php', ['success' => 'Đã xóa bài viết']);
}
?>
<?php include '../includes/header_admin.php'; ?>
    <main class="container admin d-flex justify-content-center" id="content" style="min-height: 50vh; margin-top: 200px">
        <form action="article-delete.php?id=<?= $id; ?>" method="POST" class="narrow">
            <h1 class="m-3">Xóa bài viết</h1>
            <p>Bạn có chắc chắn muốn xóa bài viết: <em><?= html_escape($article['tieude']) ?></em>?</p>
            <div class="row">
                <div class="col-6">
                    <input type="submit" name="delete" value="Xóa" class="btn btn-danger">
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="articles.php" class="btn btn-warning">Quay lại</a>
                </div>
            </div>
        </form>
    </main>
<?php include '../includes/footer.php'; ?>