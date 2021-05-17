<?php
    function loadClass ($pClassName) {
        require(__DIR__ . "/" . $pClassName . ".php");
    }
    // spl_autoload_register('loadClass');
?>