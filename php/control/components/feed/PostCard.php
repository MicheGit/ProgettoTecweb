<?php
require_once __ROOT__.'\control\components\summaries\PageFiller.php';


class PostCard extends PageFiller {
    private $postElement;


    public function __construct($postId) {
        // construct parent
        parent::__construct(file_get_contents(__ROOT__.'\view\modules\feed\PostCard.xhtml'));

        // get post's attributes
        $this->postElement = new PostElement();
        $this->postElement->loadElement($postId);
    }

    public function build() {
        // Get parent layout.
        $HTML = $this->baseLayout();

        foreach ($this->resolveData() as $placeholder => $value)
            $HTML = str_replace($placeholder, $value, $HTML);

        return $HTML;
    }

    public function resolveData() {

        $ritorno = [];

        foreach ($this->postElement->getData() as $key => $value) {
            if ($key === "immagini") {
                $ritorno['{linkImage}'] = $value[0] ?? null; // TODO caso in cui non ci sono immagini
            } else {
                if ($key === 'UserID') {
                    $author = new UserElement($value);

                    $ritorno['{authorName}'] = $author->getData()['nome'];
                }
                $ritorno['{' . $key . '}'] = $value;
            }
        }
        return $ritorno;
    }
}