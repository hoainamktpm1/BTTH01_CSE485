<?php
$error = $_GET['error'] ?? '';
?>
<?php
$title = "Login";
$name_css = "style_login.css";
require './includes/database_connection.php';
require './includes/functions.php';
?>
<?php
$username = $password = '';
$username_error = $password_error = '';
if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        $username_error = "Tên đăng nhập không được để trống.";
    } else {
        $username = html_escape($_POST['username']);
    }
    
    if (empty($_POST['password'])) {
        $password_error = "Mật khẩu không được để trống.";
    } else {
        $password = html_escape($_POST['password']);
    }
    
    $validate_success = empty($username_error) && empty($password_error);
    if ($validate_success) {
        $sql = "SELECT * FROM user WHERE ten_dnhap = :username AND mat_khau = :password";
        $result = pdo($pdo, $sql, ['username' => $username, 'password' => $password]);
        if ($result->rowCount() > 0) {
            session_start();
            $_SESSION['LAST_ACTIVITY'] = time();
            header("Location: ./admin/");
        } else {
            header("Location: login.php?error='Sai tên đăng nhập hoặc mật khẩu'");
        }
    }
}
?>
<?php
require './includes/header_home_page.php';
?>
<?php if ($error) { ?><div class="alert alert-success text-center"><?= $error ?></div><?php } ?>
<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Sign In</h3>
                <div class="d-flex justify-content-end social_icon">
                    <span><i class="fab fa-facebook-square"></i></span>
                    <span><i class="fab fa-google-plus-square"></i></span>
                    <span><i class="fab fa-twitter-square"></i></span>
                </div>
            </div>
            <div class="card-body">
                <form action="login.php" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="txtUser"><i class="fas fa-user"></i></span>
                        <input type="text" class="w-75 form-control <?php echo $username_error ? 'is-invalid' : '' ?>" name="username" placeholder="username">
                        <p class="text-danger"><?php echo $username_error; ?></p>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="txtPass"><i class="fas fa-key"></i></span>
                        <input type="password" class="w-75 form-control <?php echo $password_error ? 'is-invalid' : '' ?>" name="password" placeholder="password">
                        <p class="text-danger"><?php echo $password_error; ?></p>
                    </div>

                    <div class="row align-items-center remember">
                        <input type="checkbox">Remember Me
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" name="submit" class="btn float-end login_btn">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center ">
                    Don't have an account?<a href="#" class="text-warning text-decoration-none">Sign Up</a>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="#" class="text-warning text-decoration-none">Forgot your password?</a>
                </div>
            </div>
        </div>

    </div>
</main>
<?php
include './includes/footer.php';
?>