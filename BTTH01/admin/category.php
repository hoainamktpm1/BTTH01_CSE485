<?php 
declare(strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';
require '../includes/validate.php';

$title = 'Sửa thể loại';
 
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$category = [
    'ma_tloai' => '',
    'ten_tloai' => '',
];
$errors = [
    'warning' => '',
    'ten_tloai' => '',
];

if($id){
    $sql = "SELECT ma_tloai, ten_tloai
                FROM theloai
               WHERE ma_tloai = :ma_tloai";
    $category = pdo($pdo, $sql, ['ma_tloai' => $id])->fetch();
    
    if(!$category){
        redirect('categories.php', ['failure' => 'Không tìm thấy thể loại']);
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $category['ten_tloai'] = $_POST['ten_tloai'];

    $errors['ten_tloai'] = (is_text($category['ten_tloai'], 1, 24)) ? '' : 'Tên thể loại phải từ 1 - 24 kí tự';

    $invalid = implode($errors);
    if($invalid){
        $errors['warning'] = 'Hãy sửa các lỗi ở trên';
    }
    else{
        $arguments = $category;
        if($id){
            $sql = "UPDATE theloai 
                        SET ten_tloai = :ten_tloai
                       WHERE ma_tloai = :ma_tloai";
        }
        else{
            unset($arguments['ma_tloai']);
            $sql = "INSERT INTO theloai(ten_tloai)
                        VALUES(:ten_tloai)";
        }
        pdo($pdo, $sql, $arguments);
        redirect('categories.php', ['success' => 'Đã lưu thể loại']);
    }
}
?>
<?php include '../includes/header_admin.php' ?>
<form action="category.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
    <main class="container" id="content" style="min-height: 70vh;">
    <h3 class="text-center text-uppercase fw-bold mt-4">Sửa thông tin thể loại</h3>
        <?php if ($errors['warning']) { ?>
            <div class="alert alert-danger"><?= $errors['warning'] ?></div>
        <?php } ?>
        <div class="row">
            <section class="text col-sm">
                <div class="form-group">
                    <label for="ten_tloai" class="fw-bold">Tên thể loại: </label>
                    <input type="text" name="ten_tloai" id="ten_tloai" value="<?= html_escape($category['ten_tloai']) ?>"
                        class="form-control">
                    <span class="errors"><?= $errors['ten_tloai'] ?></span>
                </div>
                <div class="mt-2 mb-2 d-flex justify-content-end">
                    <input type="submit" name="update" value="Lưu lại" class="btn btn-success m-1">
                    <a href="categories.php" class="btn btn-warning m-1">Quay lại</a>
                </div>
            </section>
        </div>
    </main>
</form>
<?php include '../includes/footer.php'; ?>