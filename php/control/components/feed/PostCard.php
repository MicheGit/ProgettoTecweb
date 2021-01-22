<?php

    require_once __ROOT__.'\model\DAO\UserDAO.php';
    require_once __ROOT__.'\model\DAO\PostDAO.php';

    class PostCard extends PageFiller {

        private $postVO;

        public function __construct($postId) {
            parent::__construct(file_get_contents(__ROOT__.'\view\modules\feed\PostCard.xhtml'));
            // Get the post VO.
            $this->postVO = (new PostDAO())->get($postId);
    }

    public function resolveData() {

        $ritorno = [];

        $userVO = (new UserDAO())->get($this->postVO->getUserId());
        $ritorno['{nome}'] = $userVO->getNome();

        foreach ($this->postVO->arrayDump() as $key => $value) {
            if ($key === "immagini" && !isset($ritorno['{{percorsoImmagine}']))
                $ritorno['{percorsoImmagine}'] = $value[0] ?? null; // TODO caso in cui non ci sono immagini

            else
                $ritorno['{' . $key . '}'] = $value;

        }

        return $ritorno;
    }
}