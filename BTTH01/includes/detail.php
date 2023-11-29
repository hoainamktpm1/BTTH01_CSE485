<?php 
declare(strict_types = 1);
require './includes/database_connection.php';
require './includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id){
    die('<h1>Không có bài viết này!</h1>');
}

$sql = "SELECT bv.ma_bviet, bv.tieude, bv.ten_bhat, bv.tomtat, bv.noidung, bv.hinhanh,
               tg.ten_tgia AS tacgia,
               tl.ten_tloai AS theloai
          FROM baiviet      AS bv
          JOIN tacgia       AS tg ON bv.ma_tgia = tg.ma_tgia
          JOIN theloai      AS tl ON bv.ma_tloai = tl.ma_tloai
          WHERE bv.ma_bviet = :id;";

$article = pdo($pdo, $sql, ['id' => $id])->fetch();
if(!$article){
    die('<h1>Không có bài viết này!</h1>');
}

$title = $article['ten_bhat'].' - '.$article['tacgia'];
?>
<?php include './includes/header_home_page.php'; ?>
    <main class="container article mt-5" id="content">
       <div class="row mb-5">
            <section class="image col-sm-4">
                <img src="./images/songs/<?= html_escape($article['hinhanh']); ?>" 
                     alt="<?= html_escape($article['tieude']); ?>">
            </section>
            <section class="text col-sm-8">
                <h5 class="card-title mb-2">
                    <a href="#" class="text-decoration-none fw-bold text-black"><?= html_escape($article['tieude']) ?></a>
                </h5>
                <p class="card-text"><span class="fw-bold">Bài hát: </span> <?= html_escape($article['ten_bhat']) ?></p>
                <p class="card-text"><span class="fw-bold">Thể loại: </span> <?= html_escape($article['theloai']) ?></p>
                <p class="card-text"><span class="fw-bold">Tóm tắt: </span> <?= html_escape($article['tomtat']) ?></p>
                <p class="card-text"><span class="fw-bold">Nội dung: </span> <?= html_escape($article['noidung']) ?></p>
                <p class="card-text"><span class="fw-bold">Tác giả: </span> <?= html_escape($article['tacgia']) ?></p>
            </section>
       </div>
    </main>

<?php include './includes/footer.php';

