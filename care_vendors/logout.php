<?php
/**
 * Close Existing Sessions
 * Redirect to login page
 */
require_once('validator.php');
session_destroy();
header('Location: login_page_redirect');
?>