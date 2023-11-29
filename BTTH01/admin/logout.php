<?php
session_start();
if (isset($_SESSION['LAST_ACTIVITY'])) {
    session_destroy();
    header("Location: ../index.php");
}
?>