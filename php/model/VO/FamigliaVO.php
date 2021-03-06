<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "VO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "OrdineVO.php";

    class FamigliaVO implements VO {

        private $id;

        private $nomeScientifico;
        private $ordineVO;

        public function __construct(int $id = null, string $nomeScientifico = null, OrdineVO $ordineVO = null) {

            $this->id = $id; $this->nomeScientifico = $nomeScientifico;
            $this->ordineVO = $ordineVO ?? new OrdineVO();

        }
        public function __get($name){ return $this->$name ?? null; }

        public function varDumps(bool $id = false) : array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);

        }

        /** Getter di id. Può solo essere settato alla costruzione.
         * @return int|null */
        public function getId(): ?int{
            return $this->id;
        }

        /** Getter per il nome scientifico.
         * @return string */
        public function getNomeScientifico(): string{
            return $this->nomeScientifico;
        }

        /** Setter per il nome scientifico.
         * @param string $nomeScientifico */
        public function setNomeScientifico(string $nomeScientifico): void{
            $this->nomeScientifico = $nomeScientifico;
        }

        /** Getter per ordine della famiglia.
         * @return OrdineVO */
        public function getOrdineVO(): OrdineVO{
            return $this->ordineVO;
        }

        /** Setter per ordine della famiglia.
         * @param OrdineVO $ordineVO */
        public function setOrdineVO(OrdineVO $ordineVO): void{
            $this->ordineVO = $ordineVO;
        }


        public function smartDump(bool $id = false) : array {

            $data = get_object_vars($this);

            if($id) unset($data['id']);
            $data['ordineVO'] = $this->ordineVO->getId();

            return array_values($data);

        }

        public function arrayDump(): array {

            $result = get_object_vars($this);

            /** Togliamo gli array.*/
            unset($result['ordineVO']);

            foreach ($this->ordineVO->arrayDump() as $key => $value)
                $result["o_$key"] = $value;


            return $result;
        }
    }