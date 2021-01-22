<?php

    require_once __ROOT__.'\model\DAO\DAO.php';
    require_once __ROOT__.'\model\DAO\OrdineDAO.php';

    require_once __ROOT__.'\model\VO\OrdineVO.php';
    require_once __ROOT__.'\model\VO\FamigliaVO.php';

    class FamigliaDAO extends DAO{

        /** * @inheritDoc  */
        public function get($id) {

            $result = $this->performCall(array($id), 'get_famiglia');

            /** Se fallisce la ricerca ritorniamo un elemento vuoto.*/
            if(isset($result['failure'])) return new FamigliaVO();

            $result['ordineVO'] = (new OrdineDAO())->get($result['ordine']); unset($result['ordine']);

            return new FamigliaVO(...$result);
        }

        /** * @inheritDoc  */
        public function getAll() {

            $VOArray = array();

            $result = $this->performMultiCAll(array(), 'get_all_famiglia');
            if(isset($result['failure'])) return $VOArray;

            foreach ($result as $element){
                    /** Creazione di sotto oggetti.*/
                    $element['ordineVO'] = new OrdineVO($element['ordine'], $element['nomeScientifico_ordine']);
                    unset($element['ordine'], $element['nomeScientifico_ordine']);

                    // Creazione dell oggetto finale.
                    $VOArray [] = new FamigliaVO(...$element);
            }

            return $VOArray;
        }

        /** @param FamigliaVO $element */
        public function checkId(VO &$element) : bool {
            return $this->idValid($element, 'famiglia_id');
        }

        /** * @inheritDoc
         * @param FamigliaVO $element : Elemento famiglia da salvare. */
        public function save(VO &$element) : bool{

            /** Non è possibile salvare qualcosa che non ha un ordine.*/
            if(is_null($element->getOrdineVO()->getId())) return false;

            $parentDAO = new OrdineDAO();
            $parentVO = $element->getOrdineVO();

            echo 'checkid:'. $parentDAO->checkId($parentVO);

            /** Se effettivamente esiste questo ordine a cui si fa rifermento.*/
            if($parentDAO->checkId($parentVO)) {

                /** Se esiste l id della famiglia, quella va semplicemente aggiornata.*/
                if ($this->checkId($element)) {

                    $result = $this->performNoOutputModifyCall($element->smartDump(), 'update_famiglia');
                    return isset($result['failure']);

                } else {

                    $result = $this->performCall($element->smartDump(true), 'create_famiglia');

                    if(!isset($result['failure']))
                        $element = new $element(...$result, ...$element->varDumps(true));
                    /* Ritorna vero se è stato costruito l oggetto falso altrimenti.*/
                    return !$element->getId() === null;

                }
            }

            /** Elemento ordine non valido, viene perciò eliminato.*/
            $element->setOrdineVO(new OrdineVO());
            return false;

        }

        /** * @inheritDoc  */
        public function delete(VO &$element) : bool{
            return $this->defaultDelete($element, 'delete_famiglia');
        }
    }