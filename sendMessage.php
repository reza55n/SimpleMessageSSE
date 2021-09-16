<?php
file_put_contents('buffer.txt', $_GET['msg'] . "%$\n", FILE_APPEND | LOCK_EX);
?>