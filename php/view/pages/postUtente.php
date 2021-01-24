<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    // TODO: Identico per amici e tag.

    require_once __ROOT__ . '\view\pages\standardLayoutIncludes.php';

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\control\components\catalogo\GenericBrowser.php';
    require_once __ROOT__.'\control\components\profile\UserDetails.php';

    require_once __ROOT__ . '\model\DAO\UserDAO.php';
    require_once __ROOT__.'\model\DAO\PostDAO.php';

    /* Visualizzazione.*/
    // Base component of page layout.
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');
    $page = new BasePage($basePage);

    $id = $_GET['usid'] ?? header('Location: Home.php');
    $pageUser = (new UserDAO())->get($id);

    $page->addComponent(new SiteBar('PostUtente'));
    // TODO: Mettere nome dell'utente nella breadcrumb?
    $page->addComponent(new BreadCrumb(array('Utente' => 'UserPage.php?id='.$pageUser->getId(), 'I Post di: '. $pageUser->getNome()=>'')));

    // TODO: Change to a "cardborard" of the user (as summary).
    $page->addComponent(new UserDetails($pageUser, "postUtente.php?usid=", false));

    $tagPreviewLayout = file_get_contents(__ROOT__.'\view\modules\user\PostCard.xhtml');

    $postList = (new PostDAO())->getOfUtente($pageUser->getId());

    $page->addComponent(new GenericBrowser($postList, $tagPreviewLayout,
        'postUtente.php?usid='.$pageUser->getId()."&", $_GET['page'] ?? 0, 2));

    echo $page;


?>

