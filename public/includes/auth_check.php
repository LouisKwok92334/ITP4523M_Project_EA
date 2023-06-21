<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }    

    if (!isset($_SESSION['loggedin'])) {
        // User is not logged in
        http_response_code(403);
        die("Access denied");
    }
?>