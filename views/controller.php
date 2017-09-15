<?php
/*************************
* controller.php v0.1.2 **
* for project Kernel    **
* Rapoo (c) 15.09.2017  **
*************************/

define('PATH', $_SERVER['DOCUMENT_ROOT']);
define('_DIR_', PATH.'/views/');
define('_VIEW_', $_GET['view']);

include_once(PATH.'/core/kernel.php');

if(!file_exists(_DIR_._VIEW_))
{
    include_once(PATH.'/core/err/404.php');
}

if(file_exists(_DIR_._VIEW_)
    AND !file_exists(_DIR_._VIEW_.'/core.php'))
{
    include_once(PATH.'/core/err/403.php');
}

if(file_exists(_DIR_._VIEW_.'/core.php'))
{
    include_once(_DIR_._VIEW_.'/core.php');
}

include_once(PATH.'/core/base.php');
?>