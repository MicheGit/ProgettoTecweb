<?php


class PostForm extends Component
{
    private $data;

    public function __construct(SessionUser &$sessionUser, $defaults = array())
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/post/PostForm.xhtml'));
        $this->data = array();
        $this->data['{UserID}'] = $sessionUser->getUser()->getData()['ID'];

        if (isset($defaults['titolo-post']))
            $this->data['titolo-post'] = $defaults['titolo-post'];
        if (isset($defaults['descrizione-post']))
            $this->data['descrizione-post'] = $defaults['descrizione-post'];

    }

    public function resolveData()
    {
        return $this->data;
    }

}