<?php

require_once "standardLayoutIncludes.php";

require_once __DIR__ . "/Application/catalogo/Catalogo.php";
require_once __DIR__ . "/Application/databaseObjects/specie/SpecieDAO.php";

try {
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

    $page = new BasePage($basePage);

    /** Standard navigation for our page.*/
    $page->addComponent(new SiteBar("catalogo"));
    $page->addComponent(new BreadCrumb(array('Catalogo' => '')));
    try {
        $specieDAO = new SpecieDAO();
        $genereDAO = new GenereDAO();
        $famigliaDAO = new FamigliaDAO();
        $ordineDAO = new OrdineDAO();


        /** Selected ovvero che abbiamo deciso di filtrare per quel valore. */
        $oSelected = $_GET['oSelected'] ?? array();
        $oSelected = !(count($oSelected) > 1 || count($oSelected) == 0);

        $fSelected = $_GET['fSelected'] ?? array();
        $fSelected = !(count($fSelected) > 1 || count($fSelected) == 0);

        $gSelected = $_GET['gSelected'] ?? array();
        $gSelected = !(count($gSelected) > 1 || count($gSelected) == 0);

        $oValue = $oSelected ? $_GET['oValue'] ?? null : null;
        $fValue = $fSelected ? $_GET['fValue'] ?? null : null;
        $gValue = $gSelected ? $_GET['gValue'] ?? null : null;

        $oVOArray = array();
        $fVOArray = array();
        $gVOArray = array();


        if ($gSelected)
            $gVOArray = $genereDAO->getAllFilterBy($fValue, $oValue);

        if ($fSelected) {
            if ($gSelected)
                if (!empty($gVOArray))
                    $fVOArray [] = $gVOArray[0]->getFamigliaVO();
                else
                    $fVOArray [] = $famigliaDAO->get($fValue);
            else
                $fVOArray = $famigliaDAO->getAllFilterBy($oValue);
        }

        if ($oSelected) {
            if ($gSelected) {
                if (!empty($gVOArray))
                    $oVOArray [] = $gVOArray[0]->getFamigliaVO()->getOrdineVO();
                else
                    $oVOArray [] = $ordineDAO->get($oValue);

            } else if ($fSelected) {

                if (!empty($fVOArray))
                    $oVOArray [] = $fVOArray[0]->getOrdineVO();
                else
                    $oVOArray[] = $ordineDAO->get($oValue);

            } else
                $oVOArray = $ordineDAO->getAll();
        }

        /** Ottieni tutte le specie considerando i parametri necessari. */
        $birds = $specieDAO->getAllFilterBy($gSelected ? $gValue : null, $fSelected ? $fValue : null, $oSelected ? $oValue : null);

        $url = "catalogo.php?";
        foreach ($_GET as $key => $value) {
            if ("oSelected" == $key)
                $url .= !$oSelected ? "$key%5B%5D=1&$key%5B%5D=1&" : "$key%5B%5D=1&";
            else if ("fSelected" == $key)
                $url .= !$fSelected ? "$key%5B%5D=1&$key%5B%5D=1&" : "$key%5B%5D=1&";
            else if ("gSelected" == $key)
                $url .= !$gSelected ? "$key%5B%5D=1&$key%5B%5D=1&" : "$key%5B%5D=1&";
            else if ($key != "page") $url .= "$key=$value&";
        }

        $page->addComponent(
            new Catalogo($birds, $url, $_GET['page'] ?? 0, 20,
                $oVOArray, $fVOArray, $gVOArray, /** Array delle selezioni possibili.*/
                $oSelected, $fSelected, $gSelected,
                $oValue, $fValue, $gValue));
        /** Valori selezionati per le voci scelte se definiti.*/

    } catch (Throwable $error) {
        $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell\'operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Attenzione, c\' è stato un errore!', 'index.php', '500'));
    }
    echo $page;
} catch (Throwable $error) {
    header('Location: internal_server_error.php?erroStatusCode=500');
}

