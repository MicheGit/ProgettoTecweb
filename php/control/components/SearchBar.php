<?php

    require_once __ROOT__.'\control\components\Component.php';
    class SearchBar implements Component {

        private $HTML;

        public function __construct(string $HTMLcontent = null) {

            ($HTMLcontent) ?  $this->HTML = $HTMLcontent :
                $this->HTML = file_get_contents(__ROOT__.'\view\modules\SearchBar.xhtml');


        }

        function build() {

            return $this->HTML;


        }
    }