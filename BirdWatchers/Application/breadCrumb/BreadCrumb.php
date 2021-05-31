<?php

require_once __DIR__ . "/../Component.php";
class BreadCrumb extends Component {

    private $previous;

    /** Prende in input la pagina corrente e l'array di pagine precedenti.
     * crumb = array associativo Descrizione => Pagina.php
     * @param array $crumb
     * @param string|null $HTML  */
    public function __construct(array $crumb, string $HTML = null) {
        parent::__construct( $HTML ?? file_get_contents(__DIR__ . "/BreadCrumb.xhtml"));
        $this->previous = $crumb;
    }

    function build() {

        $ret = "";

        foreach ($this->previous as $key => $value) {

            $last = '';
            if (empty($value)) {
                $last = 'aria-current="page"';
            }

            $span = "<span $last> " . $key . "</span>";

            // non metto il link sull'home perché c'è già nella sitebar
            if ($value !== '') {
                $span = " <a class='breadcrumb-item' href='" . $value . "'>". $span . "</a>";
            }

            $ret .= $span;

        }

        $html = $this->baseLayout();

        if (empty($ret)) {
            $html = str_replace('id="home-crumb"', 'id="home-crumb" aria-current="page"', $html);
        }

        return str_replace("<breadCrumb />", $ret, $html);
    }
}