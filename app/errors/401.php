<?php
header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized ', true, 401);
?>
<h1>401 Unauthorized </h1>
<p><?= $msg ?></p>