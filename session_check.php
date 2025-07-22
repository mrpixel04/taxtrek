<?php
// session_check.php - Include this in all protected pages

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Check if user account is still active
include_once("db_connect.php");
if ($connection !== null) {
    $check_user_sql = "SELECT isactive, ispaid FROM TBL_USERS WHERE id = " . $_SESSION['user_id'];
    $check_result = mysqli_query($connection, $check_user_sql);
    if ($check_result && mysqli_num_rows($check_result) == 1) {
        $user_status = mysqli_fetch_assoc($check_result);
        if ($user_status['isactive'] !== 'ACTIVE') {
            session_destroy();
            header("Location: index.php");
            exit();
        }
        // Update session with current payment status
        $_SESSION['user_ispaid'] = $user_status['ispaid'];
    } else {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    mysqli_close($connection);
}
?> 