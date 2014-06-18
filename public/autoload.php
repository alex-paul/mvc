<?php
spl_autoload_register('load_system');

function load_system($sClassName)
{
    $sPath = '../core/system/';
    $sCompleteClassFileName = $sClassName . '.class.php';
    if(file_exists($sPath . $sCompleteClassFileName)) {
        require_once($sPath . $sCompleteClassFileName);
    } else {
        throw new \Exception('Class ' . $sClassName . ' was not found!');
        die();
    }

}