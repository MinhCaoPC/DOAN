<?php
session_start();
session_destroy();
header("Location: TrangChu.html");
exit();
?>