<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\Report.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\Browser.php';

    require_once __ROOT__ . '\model\BirdElement.php';

    require_once __ROOT__ . '\control\components\catalogo\Catalogo.php';

    $basePage = file_get_contents(__ROOT__ . '\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    /** Standard navigation for our page.*/
    $page->addComponent(new SiteBar("famiglia"));
    $page->addComponent(new BreadCrumb(array('Catalogo'=> '')));


    /* Dobbiamo trovare: gli ordini, i generi e le famiglie.*/

    $ordineEnabled = isset($_GET['ordineEnabled']) && !isset($_GET['ordineDisable']); /* What to add is known now.*/
    $famigliaEnabled = isset($_GET['famigliaEnabled']) && !isset($_GET['famigliaDisable']); /* What to add is known now.*/
    $genereEnabled = isset($_GET['genereEnabled']) && !isset($_GET['genereDisable']); /* What to add is known now.*/

    /* I valori delle selezioni se esistono */
    $ordineValue = isset($_GET['ordineValue']) ? $_GET['ordineValue'] : null;
    $famigliaValue = isset($_GET['famigliaValue']) ? $_GET['famigliaValue'] : null;
    $genereValue = isset($_GET['genereValue']) ? $_GET['genereValue'] : null;


    // Init delle liste.
    $ordineList = null;
    $famigliaList = null;
    $genereList = null;

    if($ordineEnabled) { // L'ordine è stato attivato.
        /** Se non è possibile ancora scegliere tra famiglia o genere possiamo scorrere.*/
        if (!$famigliaEnabled && !$genereEnabled)
            $ordineList = BirdElement::getOrdini();
        else /* abbiamo solo l'elemento a cui appartiene la famiglia o il genere corrente. */
            $ordineList = BirdElement::getOrdine($ordineValue);

    }

    if($famigliaEnabled) {
        if (!$genereEnabled)
            $famigliaList = BirdElement::getFamigle($ordineValue);
         else
            $famigliaList = BirdElement::getFamiglia($famigliaValue);
    }


    if($genereEnabled)
        $genereList = BirdElement::getGeneri($ordineValue, $famigliaValue);


    $birds = BirdElement::getBirds($ordineEnabled? $ordineValue : null, $famigliaEnabled ? $famigliaValue : null,
            $genereEnabled? $genereValue : null);

    print_r($birds);

    $page->addComponent(new
            Catalogo($birds, $ordineList, $famigliaList, $genereList, $ordineValue, $famigliaValue, $genereValue));

    echo $page;

?>