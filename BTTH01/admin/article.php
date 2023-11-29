<?php
declare(strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';
require '../includes/validate.php';

$title = 'Sửa bài viết';

$uploads = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'songs'. DIRECTORY_SEPARATOR;
$file_types      = ['image/jpeg', 'image/png', 'image/gif'];
$file_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$max_size        = '5242880';  

$id     = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$temp   = $_FILES['image']['tmp_name'] ?? '';
$destination = ''; //Nơi lưu trữ file

$article = [
    'ma_bviet'  => $id,
    'tieude'    => '',
    'ten_bhat'  => '',
    'tomtat'    => '',
    'noidung'   => '',
    'ma_tgia'   => '',
    'ma_tloai'  => '',
    'hinhanh'   => '',
];

$errors = [
    'warning'   => '',
    'tieude'    => '',
    'ten_bhat'  => '',
    'tomtat'    => '',
    'noidung'   => '',
    'tacgia'    => '',
    'theloai'   => '',
    'hinhanh'   => '',
];

if($id){
    $sql = "SELECT ma_bviet, tieude, ten_bhat, tomtat, noidung, ma_tgia, ma_tloai, hinhanh
            FROM baiviet
            WHERE ma_bviet = :ma_bviet";
    $article = pdo($pdo, $sql, ['ma_bviet' => $id])->fetch();
    
    if(!$article){
        redirect('articles.php', ['failure' => 'Không tìm thấy bài viết']);
    }
}

$saved_image = $article['hinhanh'] ? true : false;

$sql        = "SELECT ma_tgia, ten_tgia FROM tacgia";
$authors    = pdo($pdo, $sql)->fetchAll();

$sql        = "SELECT ma_tloai, ten_tloai FROM theloai";
$categories = pdo($pdo, $sql)->fetchAll();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // $errors['hinhanh'] = ($temp === '' and $_FILES['image']['error'] === 1) ? 'Kích cỡ file quá lớn' : '';

    if($temp and $_FILES['image']['error'] === 0){
        $errors['hinhanh'] = in_array(mime_content_type($temp), $file_types) ? '' : 'Sai loại file';
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $errors['hinhanh'] .= in_array($extension, $file_extensions) ? '' : 'Sai phần mở rộng của file';
        $errors['hinhanh'] .= ($_FILES['image']['size'] <= $max_size) ? '' : 'Kích cỡ file quá lớn';

        if($errors['hinhanh'] === ''){
            $article['hinhanh'] = create_filename($_FILES['image']['name'], $uploads); //tên không trùng lặp trên kho
            $destination = $uploads . $article['hinhanh'];
        }
    }

    $article['tieude']      = $_POST['tieude'];
    $article['ten_bhat']    = $_POST['ten_bhat'];
    $article['tomtat']      = $_POST['tomtat'];
    $article['noidung']     = $_POST['noidung'];
    $article['ma_tgia']     = $_POST['ma_tgia'];
    $article['ma_tloai']    = $_POST['ma_tloai'];

    $errors['tieude']    = is_text($article['tieude'], 1, 80) ? '' : 'Tiêu đề phải chứa 1 - 80 kí tự';
    $errors['ten_bhat']  = is_text($article['ten_bhat'], 1, 80) ? '' : 'Tên bài hát phải chứa 1 - 80 kí tự';
    $errors['tomtat']    = is_text($article['tomtat'], 1, 500) ? '' : 'Tóm tắt phải chứa từ 1 - 500 kí tự';
    $errors['noidung']   = is_text($article['noidung'], 1, 100000) ? '' : 'Nội dung phải chứa từ 1 - 100,000 kí tự';
    $errors['tacgia']    = is_author_id($article['ma_tgia'], $authors) ? '' : 'Vui lòng chọn tác giả';
    $errors['theloai']   = is_category_id($article['ma_tloai'], $categories) ? '' : 'Vui lòng chọn thể loại';

    $invalid = implode($errors);

    if($invalid){
        $errors['warning'] = 'Hãy sửa các lỗi ở trên';
    } 
    else{
        $arguments = $article;
        if($id){
            $sql = "UPDATE baiviet 
                       SET tieude = :tieude, ten_bhat = :ten_bhat, tomtat = :tomtat, noidung = :noidung,
                           ma_tgia = :ma_tgia, ma_tloai = :ma_tloai, hinhanh = :hinhanh
                    WHERE ma_bviet = :ma_bviet";
        }
        else{
            unset($arguments['ma_bviet']);
            $sql = "INSERT INTO baiviet(tieude, ten_bhat, tomtat, noidung, ma_tgia, ma_tloai, hinhanh)
                            VALUES (:tieude, :ten_bhat, :tomtat, :noidung, :ma_tgia, :ma_tloai, :hinhanh)";
        }
        move_uploaded_file($temp, $destination);
        pdo($pdo, $sql, $arguments);
        redirect('articles.php', ['success' => 'Đã lưu bài viết']);
    }
    $article['hinhanh'] = $saved_image ? $article['hinhanh'] : '';
}
?>
<?php include '../includes/header_admin.php'; ?>
    <form action="article.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <main class="container" id="content">
        <h3 class="text-center text-uppercase fw-bold mt-4">Sửa thông tin bài viết</h3>
            <?php if ($errors['warning']) { ?>
                <div class="alert alert-danger"><?= $errors['warning'] ?></div>
            <?php } ?>
            <div class="row">
                <section class="image col-sm-6">
                <?php if (!$article['hinhanh']) { ?>
                    <label for="image" class="fw-bold">Tải ảnh lên:</label>
                    <div class="form-group image-placeholder mb-6">
                        <input type="file" name="image" class="form-control-file" id="image"><br>
                        <img id="display-image" width = "80%" height = "60%">
                        <span class="errors"><?= $errors['hinhanh'] ?></span>
                    </div>
                <?php } else { ?>
                    <label class="fw-bold">Hình ảnh:</label>
                    <img src="../images/songs/<?= html_escape($article['hinhanh']) ?>"
                        alt="<?= html_escape($article['hinhanh']) ?> " width = "100%" height = "80%">
                <?php } ?>
                </section>
                <section class="text col-sm-6">
                    <div class="form-group">
                        <label for="tieude" class="fw-bold">Tiêu đề: </label>
                        <input type="text" name="tieude" id="tieude" value="<?= html_escape($article['tieude']) ?>"
                            class="form-control">
                        <span class="errors"><?= $errors['tieude'] ?></span>
                    </div>
                    <div class="form-group mt-2">
                        <label for="ten_bhat" class="fw-bold">Tên bài hát: </label>
                        <input type="text" name="ten_bhat" id="ten_bhat" value="<?= html_escape($article['ten_bhat']) ?>"
                            class="form-control">
                        <span class="errors"><?= $errors['ten_bhat'] ?></span>
                    </div>
                    <div class="form-group mt-2">
                        <label for="tomtat" class="fw-bold">Tóm tắt: </label>
                        <textarea name="tomtat" id="tomtat"
                                class="form-control"><?= html_escape($article['tomtat']) ?></textarea>
                        <span class="errors"><?= $errors['tomtat'] ?></span>
                    </div>
                    <div class="form-group mt-2">
                        <label for="tomtat" class="fw-bold">Nội dung: </label>
                        <textarea name="noidung" id="noidung"
                                class="form-control"><?= html_escape($article['noidung']) ?></textarea>
                        <span class="errors"><?= $errors['noidung'] ?></span>
                    </div>
                    <div class="form-group mt-2">
                        <label for="ma_tgia" class="fw-bold">Tác giả: </label>
                        <select name="ma_tgia" id="ma_tgia">
                        <?php foreach ($authors as $author) { ?>
                            <option value="<?= $author['ma_tgia'] ?>"
                                <?= ($article['ma_tgia'] == $author['ma_tgia']) ? 'selected' : ''; ?>>
                                <?= html_escape($author['ten_tgia']) ?></option>
                        <?php } ?>
                        </select>
                        <span class="errors"><?= $errors['tacgia'] ?></span>
                    </div>
                    <div class="form-group mt-2">
                        <label for="ma_tloai" class="fw-bold">Thể loại: </label>
                        <select name="ma_tloai" id="ma_tloai">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?= $category['ma_tloai'] ?>"
                                <?= ($article['ma_tloai'] == $category['ma_tloai']) ? 'selected' : ''; ?>>
                                <?= html_escape($category['ten_tloai']) ?></option>
                        <?php } ?>
                        </select>
                        <span class="errors"><?= $errors['theloai'] ?></span>
                    </div>
                    <div class="mt-2 mb-2 d-flex justify-content-end">
                        <input type="submit" name="update" value="Lưu lại" class="btn btn-success m-1">
                        <a href="articles.php" class="btn btn-warning m-1">Quay lại</a>
                    </div>
                </section>
            </div>
        </main>
    </form>
    <script>
        const inputFile = document.getElementById('image');
        const imagePreview = document.getElementById('display-image');

        inputFile.addEventListener('change', function() {
            const file = this.files[0];
            const reader = new FileReader();

            reader.addEventListener('load', function() {
            imagePreview.src = reader.result;
            });

            reader.readAsDataURL(file);
        });
    </script>

<?php include '../includes/footer.php' ?>