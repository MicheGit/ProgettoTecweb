<?php

    // Ci sto lavorando ma se vuoi farlo tu, sei libero di farlo.
    class BreadCrumb extends Component {

        private $previous;
        private $HTML;

        /** Prende in input la pagina corrente e l'array di pagine precedenti.
         * crumb = array associativo Descrizione => Pagina.php
         * @param array $crumb
         * @param string|null $HTML  */
        public function __construct(array $crumb, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\BreadCrumb.xhtml'));
            $this->previous = $crumb;

        }

        function build() {
            //https://codepen.io/iamglynnsmith/pen/BRGjgW
            // TODO: Move this stuff to file and just make a strreplace.

            $ret = "";

            foreach ($this->previous as $key => $value) {

                $span = "<span> " . $key . "</span>";

                if ($value !== '') { $span = " <a class='breadcrumb-item' href='" . $value . "'>". $span . "</a>"; }

                $ret .= $span;

            }

            return str_replace("<breadCrumb />", $ret, $this->baseLayout());
        }
    }