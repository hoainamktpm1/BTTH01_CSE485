<?php
declare(strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';

$title = 'Xóa thể loại';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id){
    redirect('categories.php', ['failure' => 'Không tìm thấy thể loại']);
}

$category = false;
$sql = "SELECT ten_tloai FROM theloai WHERE ma_tloai = :ma_tloai";
$category = pdo($pdo, $sql, ['ma_tloai' => $id])->fetch();
if(!$category){
    redirect('categories.php', ['failure' => 'Không tìm thấy thể loại']);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try{
        $sql = "DELETE FROM theloai WHERE ma_tloai = :ma_tloai";
        pdo($pdo, $sql, ['ma_tloai' => $id]);
        redirect('categories.php', ['success' => 'Đã xóa thể loại']);
    }
    catch(PDOException $e){
        if($e->errorInfo[1] == 1451){
            redirect('categories.php', ['failure' => 'Thể loại '.$category['ten_tloai'].' có chứa các bài viết khác.
            Vui lòng di chuyển các bài viết hoặc xóa chúng trước khi xóa thể loại này']);
        }
        else{
            throw $e;
        }
    }
}
?>
<?php include '../includes/header_admin.php'; ?>
    <main class="container admin d-flex justify-content-center" id="content" style="min-height: 50vh; margin-top: 200px">
        <form action="category-delete.php?id=<?= $id; ?>" method="POST" class="narrow">
            <h1 class="m-3">Xóa thể loại</h1>
            <p>Bạn có chắc chắn muốn xóa thể loại: <em><?= html_escape($category['ten_tloai']) ?></em>?</p>
            <div class="row">
                <div class="col-6">
                    <input type="submit" name="delete" value="Xóa" class="btn btn-danger">
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="categories.php" class="btn btn-warning">Quay lại</a>
                </div>
            </div>
        </form>
    </main>
<?php include '../includes/footer.php'; ?>