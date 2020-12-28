<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    // TODO: Make includes files for pages.
    require_once __ROOT__.'\control\BasePage.php';
    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__.'\control\components\SearchBar.php';
    require_once __ROOT__.'\control\SessionUser.php ';
    require_once __ROOT__.'\control\components\Post.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $sessionUser = new SessionUser();
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("postpage"));

    $page->addComponent(new SearchBar());
    $page->addComponent(new BreadCrumb(array('Home', 'Post')));

    $pid = isset($_GET['pid']) ? $_GET['pid'] : null;
    $page->addComponent(new Post($pid,$sessionUser));

    echo $page;

?>