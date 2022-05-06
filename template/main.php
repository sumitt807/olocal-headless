<?php
include PARTIALTEMPLATE . 'head.php';
include PARTIALTEMPLATE . 'header.php';

$rewrite = getRewriteRulesFromDB();
if (isset($rewrite[0]) && !empty($rewrite[0]) && file_exists(TEMPLATE . $rewrite[0] . '.php')) {
    include TEMPLATE . $rewrite[0] . '.php';
} else {
    include TEMPLATE . 'home.php';
}

include PARTIALTEMPLATE . 'footer.php';
