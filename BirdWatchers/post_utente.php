<?php

require_once "standardLayoutIncludes.php";

require_once __DIR__ . "/Application/genericBrowser/GenericBrowser.php";
require_once __DIR__ . "/Application/title/Title.php";

require_once __DIR__ . "/Application/databaseObjects/user/UserDAO.php";
require_once __DIR__ . "/Application/databaseObjects/post/PostDAO.php";

/* Visualizzazione.*/
// Base component of page layout.
$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
$page = new BasePage($basePage);

$id = $_GET['usid'] ?? header('Location: index.php');
$pageUser = (new UserDAO())->get($id);

$page->addComponent(new SiteBar('post_utente'));

$page->addComponent(new BreadCrumb(array('Utente' => 'user_page.php?id='.$pageUser->getId(), 'I Post di: '. $pageUser->getNome()=>'')));

$page->addComponent(new Title("Post pubblicati",null, "I post che l'utente ha pubblicato sulla piattaforma."));

$tagPreviewLayout = file_get_contents(__DIR__. "/Application/feed/postCard/PostCard.xhtml");

$postList = (new PostDAO())->getOfUtente($pageUser->getId());

$page->addComponent(new GenericBrowser($postList, $tagPreviewLayout,
    'post_utente.php?usid='.$pageUser->getId()."&", $_GET['page'] ?? 0, 8));

echo $page;


?>
