<?php

    require_once __ROOT__ . '\model\VO\SpecieVO.php';
    require_once __ROOT__ . '\model\DAO\ConservazioneDAO.php';
    require_once __ROOT__ . '\model\DAO\GenereDAO.php';

    class SpecieDAO extends DAO {

        public function get($id) {

            $result = $this->performCall(array($id), 'get_specie');

            /** Se fallisce la ricerca ritorniamo un elemento vuoto.*/
            if(isset($result['failure'])) return new GenereVO();

            $result['genereVO'] = (new GenereDAO())->get($result['genere']); unset($result['genere']);
            $result['conservazioneVO'] = (new ConservazioneDAO())->get($result['conservazione']); unset($result['conservazione']);

            return new SpecieVO(...$result);
        }

        public function getAll() {

            $VOArray = array();

            $result = $this->performMultiCAll(array(), 'get_all_specie');
            if(isset($result['failure'])) return $VOArray;


            foreach ($result as $element){

                    /** Creazione di un genere. Che a sua volta crea la famiglia e sua volta l ordine.*/
                    $element['genereVO'] = new GenereVO( $element['genere'], $element['nomeScientifico_genere'],
                        new FamigliaVO($element['famiglia'], $element['nomeScientifico_famiglia'],
                            new OrdineVO($element['ordine'],  $element['nomeScientifico_ordine'])));

                    /** Creazione di stato estinzione.*/
                    $element['conservazioneVO'] = new ConservazioneVO(
                        $element['conservazione'], $element['nome'],
                        $element['probEstinzione'], $element['descrizione']);

                    /** Scartiamo gli attributi non più necessari.*/
                    unset($element['genere'], $element['nomeScientifico_genere'],
                        $element['famiglia'],  $element['nomeScientifico_famiglia'],
                        $element['ordine'], $element['nomeScientifico_ordine']);

                    unset($element['conservazione'],$element['nome'],
                        $element['probEstinzione'], $element['descrizione']);

                    $VOArray [] = new SpecieVO(...$element);
            }
            return $VOArray;
        }

        /** @param SpecieVO $element */
        public function checkId(VO &$element) : bool {
            return $this->idValid($element, 'specie_id');
        }

        /** @param SpecieVO $element */
        public function save(VO &$element) : bool {

            /** Non è possibile salvare qualcosa che non ha un ordine o che non ha associato un grado di estinzione.*/

            $parentVO = $element->getGenereVO();

            if(is_null($parentVO->getId()) || is_null($element->getConservazioneVO()->getId())) return false;
            $parentDAO = new GenereDAO();

            /** Se effettivamente esiste questo ordine a cui si fa rifermento.*/
            if($parentDAO->checkId($parentVO)) {
                /** Se esiste l id della famiglia, quella va semplicemente aggiornata.*/
                if ($this->checkId($element)) {

                    $data = $element->smartDump();

                    $result = $this->performCall($data, 'update_specie');
                    return isset($result['failure']);

                } else {

                    $data = $element->smartDump(true);
                    $result = $this->performCall($data, 'create_specie');

                    if(!isset($result['failure']))
                        $element = new $element(... $result, ...$element->varDumps(true));

                    return !$element->getId() === null;

                }
            }

            $element->setGenereVO(new GenereVO());
            return false;

        }

        public function delete(VO &$element) : bool {
            return $this->defaultDelete($element, 'delete_specie');
        }
    }