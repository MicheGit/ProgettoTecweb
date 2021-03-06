<?php
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Component.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "catalogo" . DIRECTORY_SEPARATOR . "PreviewsPage.php";

    class GenericBrowser extends Component {

        private $previewPage;

        private $currentPage;
        private $numberPages;

        private $nextPageReference;

        public function __construct(array $elements, string $previewLayout, string $nextPageReference, int $currentPage = 0,
                                    int $elementsPerPage = 10, string $HTML = null, string $browsePageHTML = null){

            parent::__construct( $HTML ?? (
                count($elements) > 0
                    ? file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "browsing" . DIRECTORY_SEPARATOR . "Browser.xhtml")
                    : '<img src="\res\images\wow-such-empty.png" alt="Questa ricerca non ha prodotto risultati" />'));

            $this->nextPageReference = $nextPageReference .'page=';

            $this->currentPage = $currentPage;
            $this->numberPages = count($elements) / $elementsPerPage;


            $newElementsList = array_slice($elements, $elementsPerPage * $currentPage, $elementsPerPage);

            $this->previewPage = new PreviewsPage($newElementsList, $previewLayout, $browsePageHTML);

        }

        public function resolveData(){

            $resolveData = [];

            /** Elements page.*/
            $resolveData['{elements}'] = $this->previewPage->returnComponent();

            /** Page browsing */
            $browsingList = '';

            /** We check that we actually need a given number of pages. */
            if($this->numberPages >= 1) {
                for ($i = 0; $i < $this->numberPages; $i++){

                    if ($i == $this->currentPage)
                        $browsingList .= '<a href= "#" class="selected-button page-select">' . ($i + 1) . '</a>';
                    else // stile alternativo. (link selezionabile che porta alla pagina successiva).
                        $browsingList .= '<a href="' . $this->nextPageReference . $i . '" class="page-select">' . ($i + 1) . '</a>';

                }
            }

            $resolveData['{navigation}'] = $browsingList;
            return $resolveData;

    }
}