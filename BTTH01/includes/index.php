<?php
declare(strict_types = 1);
require './includes/database_connection.php';
require './includes/functions.php';

$sql = "SELECT bv.ma_bviet, bv.tieude, bv.ten_bhat, bv.hinhanh,
               tg.ten_tgia AS tacgia,
               tl.ten_tloai AS theloai
          FROM baiviet      AS bv
          JOIN tacgia       AS tg ON bv.ma_tgia = tg.ma_tgia
          JOIN theloai      AS tl ON bv.ma_tloai = tl.ma_tloai
          ORDER BY bv.ma_bviet
          LIMIT 8;";

$articles = pdo($pdo, $sql)->fetchAll();

$title = 'Music for Life';
?>
<?php include './includes/header_home_page.php'; ?>
    <div id="carouselExampleIndicators" class="carousel slide">     
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/slideshow/slide01.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="images/slideshow/slide02.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="images/slideshow/slide03.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <main class="container" id="content">
        <h2 class="text-center text-uppercase m-3 text-black fw-bold">---------------TOP bài hát yêu thích---------------</h2>
        <div class="row">
            <?php foreach($articles as $article) { ?>
                <article class="col-sm-3 summary">
                    <div class="card mb-4" style="width: 100%;">
                        <a href="detail.php?id=<?= $article['ma_bviet']; ?>" class="text-decoration-none">
                            <img src="./images/songs/<?= html_escape($article['hinhanh']); ?>" 
                                alt="<?= html_escape($article['tieude']); ?>" class="card-img-top" style="height: 10em;">
                            <h5 class="card-title text-center fw-bold"><?= html_escape($article['ten_bhat']); ?></h5>
                            <h6><span class="fw-bold">Tác giả: </span><?= html_escape($article['tacgia']); ?></h6>
                            <h6><span class="fw-bold">Thể loại: </span><?= html_escape($article['theloai']); ?></h6>
                        </a>
                    </div>
            </article>
            <?php } ?>
        </div>
    </main>
<?php
    include './includes/footer.php';
?>