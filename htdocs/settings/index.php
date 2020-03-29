<?php

require_once '../common.php';
Auth\Session::grantAccess([]);
httpResponse::showHtmlHeader('Настройки');
$menu=require './sections.php';
httpResponse::showNavPills('%s/', $menu);
foreach ($menu as $path=>$name) {
?>
<p><a href="<?=$path?>/"><?=$name?></a></p>
<?php
}
httpResponse::showHtmlFooter();