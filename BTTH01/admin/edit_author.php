<?php
declare(strict_types=1);
require '../includes/database_connection.php';
require '../includes/functions.php';


$id     = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$result = [
    'ten_tgia' => '',
    'hinh_tgia' => ''
];

if ($id) {
    $sql = "SELECT ma_tgia, ten_tgia, hinh_tgia
            FROM tacgia
            WHERE ma_tgia = :ma_tgia";
    $result = pdo($pdo, $sql, ['ma_tgia' => $id])->fetch();

    if (!$result) {
        redirect('author.php', ['failure' => 'Không tìm thấy bài viết']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_tgia = $_POST['ten_tgia'];
    $temp   = $_FILES['hinh_tgia']['tmp_name'] ?? '';
    $path = '../images/authors/' . $_FILES['hinh_tgia']['name'];
    $hinh_tgia = $_FILES['hinh_tgia']['name'];

    echo $hinh_tgia;
    $moved = move_uploaded_file($temp, $path);

    unlink("../images/authors/" . $result['hinh_tgia']);
    $sql = "UPDATE tacgia SET ten_tgia = :ten_tgia, hinh_tgia = :hinh_tgia
            WHERE ma_tgia = :ma_tgia";

    $argument = [
        'ma_tgia' => $id,
        'ten_tgia' => $ten_tgia,
        'hinh_tgia' => $hinh_tgia
    ];

    pdo($pdo, $sql, $argument);
    redirect('author.php', ['success' => 'Sửa thành công tác giả']);
}

$image_author_error = '';

?>
<?php $title = "Sửa tác giả";
require '../includes/header_admin.php'; ?>

<main class="container mt-5 mb-5">
    <!-- <h3 class="text-center text-uppercase mb-3 text-primary">CẢM NHẬN VỀ BÀI HÁT</h3> -->
    <div class="row">
        <div class="col-sm">
            <h3 class="text-center text-uppercase fw-bold">Sửa thông tin tác giả</h3>
            <form action="edit_author.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
                <div class="input-group mt-3 mb-3">
                    <span class="input-group-text" id="ma_tgia">Mã tác giả</span>
                    <input type="text" class="form-control" name="ma_tgia" readonly value="<?= $id ?>">
                </div>

                <div class="input-group mt-3 mb-3">
                    <span class="input-group-text" id="ten_tgia">Tên tác giả</span>
                    <input type="text" class="form-control" name="ten_tgia" value="<?= $result['ten_tgia'] ?>">
                </div>
                <div class="input-group  mb-3">
                    <span class="input-group-text" id="hinh_tgia">Hình ảnh</span>
                    <input type="file" class="form-control" name="hinh_tgia" id="image">
                </div>
                
                <p style="display:block">Old image:</p>
                <img src="../images/authors/<?= html_escape($result['hinh_tgia']) ?>" alt="<?= html_escape($result['hinh_tgia']) ?>" width="300px" >
                <div class="row">
                </div>

                <div class="form-group  float-end ">
                    <input type="submit" value="Lưu lại" class="btn btn-success">
                    <a href="author.php" class="btn btn-warning ">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
include '../includes/footer.php';
?>