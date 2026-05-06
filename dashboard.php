<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if ($_SESSION['role'] == 'admin') {
    header("Location: admin/dashboard.php");
} elseif ($_SESSION['role'] == 'provider') {
    header("Location: provider/dashboard.php");
} else {
    header("Location: client/dashboard.php");
}
exit;