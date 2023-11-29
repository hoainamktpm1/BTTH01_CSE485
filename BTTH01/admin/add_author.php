<?php
declare(strict_types=1);
require '../includes/database_connection.php';
require '../includes/functions.php';
require '../includes/validate.php';

$uploads = '..' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'authors' . DIRECTORY_SEPARATOR;
$file_types      = ['image/jpeg', 'image/png', 'image/gif'];
$file_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$max_size        = '5242880'; // 5 MB
$temp   = $_FILES['hinh_tgia']['tmp_name'] ?? '';
$destination = ''; //Nơi lưu trữ file

$name_author_error = $image_author_error = $warning = '';
$name_author = $image_author = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_FILES['hinh_tgia']['error'] === 0) {
        $image_author_error = in_array(mime_content_type($temp), $file_types) ? '' : 'Sai loại file';
        $extension          = strtolower(pathinfo($_FILES['hinh_tgia']['name'], PATHINFO_EXTENSION));
        $image_author_error = in_array($extension, $file_extensions) ? '' : 'Sai phần mở rộng của file';
        $image_author_error = ($_FILES['hinh_tgia']['size'] <= $max_size) ? '' : 'Kích cỡ file quá lớn';


        if ($image_author_error === '') {
            $image_author = create_filename($_FILES['hinh_tgia']['name'], $uploads);
            $destination = $uploads . $image_author;
        }

        $name_author        = $_POST['ten_tgia'];
        $name_author_error  = is_text($name_author, 1, 30) ? '' : 'Tên tác giả phải chứa 1 - 30 kí tự';

        $invalid = $name_author_error && $image_author_error;
        if ($invalid) {
            $warning = 'Hãy thêm đầy đủ thông tin';
        } else {
            $sql = "INSERT INTO tacgia(ten_tgia, hinh_tgia) VALUES (:ten_tgia, :hinh_tgia)";

            move_uploaded_file($temp, $destination);
            $arguments = ['ten_tgia' => $name_author, 'hinh_tgia' => $image_author];
            $save = pdo($pdo, $sql, $arguments);
            if ($save) {
                redirect('author.php', ['success' => 'Đã lưu thành công']);
            } else {
                redirect('author.php', ['failure' => 'Lưu không thành công']);
            }
        }
    }
}
$title = "Thêm tác giả";
require '../includes/header_admin.php';
?>
<main class="container mt-5 mb-5">
    <?php if ($warning) { ?>
        <div class="alert alert-danger"><?= $warning ?></div>
    <?php } ?>
    <form action="add_author.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold">Thêm mới tác giả</h3>
                <div class="input-group mt-3 mb-3">
                    <span class="input-group-text" id="ten_tgia">Tên tác giả</span>
                    <input type="text" class="form-control" name="ten_tgia">
                    <span class="text-danger errors"><?= $name_author_error ?></span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="hinh_tgia">Hình ảnh</span>
                    <input type="file" class="form-control" name="hinh_tgia">
                    <span class="text-denger errors"><?= $image_author_error ?></span>
                </div>
                <div class="form-group float-end">
                    <input type="submit" value="Thêm" class="btn btn-success">
                    <a href="author.php" class="btn btn-warning ">Quay lại</a>
                </div>

            </div>
        </div>
    </form>
</main>
<?php
include '../includes/footer.php';
?>