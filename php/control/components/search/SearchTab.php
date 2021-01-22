<?php

require_once __ROOT__.'\control\BasePage.php';
require_once __ROOT__.'\control\components\catalogo\GenericBrowser.php';


class SearchTab extends BasePage
{
    private $keyword;

    public function __construct($keyword, $entity)
    {
        parent::__construct(file_get_contents(__ROOT__."/view/modules/search/SearchTab.xhtml"));
        $this->keyword = addslashes($keyword);

        switch ($entity) {
            case "commento":
                $query = "select m.*, c.*, u.*, p.title from commento m join contenuto c on m.contentID = c.ID join utente u on u.ID = c.UserID join post p on p.contentID = m.postID where c.content like '%". $this->keyword ."%';";
                $layout = file_get_contents(__ROOT__."/view/modules/search/CommentCard.xhtml");
                break;
            case "specie":
                $query = "select * from specie where nomeScientifico like '%". $this->keyword ."%' or descrizione like '%". $this->keyword ."%';";
                $layout = file_get_contents(__ROOT__."/view/modules/search/SpecieCard.xhtml");
                break;
            case "post":
            default:
                $layout = file_get_contents(__ROOT__."/view/modules/feed/PostCard.xhtml");
                $query = "select * from post p left join contenuto c on p.contentID = c.ID left join immaginipost i on i.postID = p.contentID join utente u on u.ID = c.UserID where p.title like '%". $this->keyword ."%' or c.content like '%". $this->keyword ."%' group by p.contentID ;";
        }

        $results = DatabaseAccess::executeQuery($query);

        $this->addComponent(new GenericBrowser($results, $layout, "Search.php"));
    }

    public function resolveData()
    {
        return array(
            "{keyword}" => $this->keyword,
        );
    }

}