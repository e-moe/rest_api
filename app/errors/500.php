<?php
header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
?>
<h1>500 Internal Server Error</h1>
<p><?= $msg ?></p>