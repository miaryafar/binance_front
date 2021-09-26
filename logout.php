<?php
//echo password_hash("Salam@123",PASSWORD_DEFAULT);
session_start();
session_destroy();
// Redirect to the login page:
header('Location: index.html');
?>