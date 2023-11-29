<?php
declare (strict_types = 1);
require '../includes/database_connection.php';
require '../includes/functions.php';

$sql = "SELECT COUNT(*) AS so_luong FROM tacgia";
$row_tacgia = pdo($pdo, $sql)->fetch();

$sql = "SELECT COUNT(*) AS so_luong FROM user";
$row_user = pdo($pdo, $sql)->fetch();

$sql = "SELECT COUNT(*) AS so_luong FROM theloai";
$row_theloai = pdo($pdo, $sql)->fetch();

$sql = "SELECT COUNT(*) AS so_luong FROM baiviet";
$row_baiviet = pdo($pdo, $sql)->fetch();

$title = "Trang chủ - Admin";
?>

<?php require '../includes/header_admin.php' ?>
    <main class="container mt-5 mb-5" style="min-height: 60vh;">
        <!-- <h3 class="text-center text-uppercase mb-3 text-primary">CẢM NHẬN VỀ BÀI HÁT</h3> -->
        <div class="row">
            <div class="col-sm-3">
                <div class="card mb-2" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="" class="text-decoration-none">Người dùng</a>
                        </h5>
                        <h5 class="h1 text-center">
                            <?= html_escape($row_user['so_luong']); ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card mb-2" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="" class="text-decoration-none">Thể loại</a>
                        </h5>
                        <h5 class="h1 text-center">
                            <?= html_escape($row_theloai['so_luong']); ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card mb-2" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="" class="text-decoration-none">Tác giả</a>
                        </h5>

                        <h5 class="h1 text-center">
                            <?= html_escape($row_tacgia['so_luong']); ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card mb-2" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="" class="text-decoration-none">Bài viết</a>
                        </h5>

                        <h5 class="h1 text-center">
                            <?= html_escape($row_tacgia['so_luong']); ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include '../includes/footer.php' ?>