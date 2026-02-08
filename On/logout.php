<?php
// Step 1: Session start karna zaroori hai taaki hum use access karke destroy kar sakein
session_start();

// Step 2: Saare session variables ko khali (unset) karna
session_unset();

// Step 3: Session ko poori tarah khatam (destroy) karna
session_destroy();

// Step 4: User ko wapas login page par redirect karna
header("Location: login.php");
exit();
?>