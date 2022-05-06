<?php
include 'config.php';



// $rewrite = getRewriteRulesFromDB();

if (isset($rewrite[0]) && !empty($rewrite[0]) && file_exists(TEMPLATE . $rewrite[0] . '.php')) {
}

include TEMPLATE . 'main.php';
