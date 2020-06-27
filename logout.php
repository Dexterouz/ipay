<?php
  session_start(); // access the current session
  // if no session variable exist then redirect the user
    if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
    // Cancel the session and redirect the user:
    } else {
    // Cancel the session
    $_SESSION = array(); // Destroy the variables
    $params = session_get_cookie_params();
    // Destroy the cookie
    Setcookie(session_name(), '', time() - 42000, $params['path'],
    $params['domain'], $params['secure'], $params['httponly']);
    if (session_status() == PHP_SESSION_ACTIVE) {
      session_destroy(); // Destroy the session itself
        header("Location: index.php");
        exit();
    }
}
?>
