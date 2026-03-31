<?php
session_start();
session_destroy();
header("Location: login.php?success=You have been logged out.");
exit;
?>