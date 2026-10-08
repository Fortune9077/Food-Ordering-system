<?php
session_start();
$count = 0;
if (!empty($_SESSION['basket'])) {
    foreach ($_SESSION['basket'] as $item) {
        $count += $item['qty'];
    }
}
echo $count;
?>
