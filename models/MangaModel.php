<?php 
class MangaModel extends Model {
    private $json;
    private $HTML;

    function __construct() {

        
        $media = new mangaMediaComponent();

        if(isset($_SESSION['user'])) {
            $id = $_SESSION['user']->getId();
    
            $this->HTML = $media->outputTemplate($id);
        } else {
            $this->HTML = $media->outputTemplate();
        }

    }

    public function getHTML() {
        return $this->HTML;
    }

    public function createMangaListing($page = 1) {
        $database = new database;
        $db = $database->getConnection();
        $pageSize = 24;
        $startPage = $pageSize * ($page - 1);
        $startPage = $startPage !== 0 ? $startPage++ : 0;

        try {
        $query = <<<SQL
        SELECT manga_id, chapters, status, title
        from manga
        order by manga_id
        limit $startPage, $pageSize
        SQL;


        $stmt = $db->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $stmt->closeCursor();

        $content = '';

        foreach ($res as $elem) {
            extract($elem);

            $chapters = $chapters == '' ? 'N/A' : $chapters;

        $content .= "<div class=\"list\">
                    <a class=\"list__link\" href=\"index.php?route=manga&id={$manga_id}\">
                    <h3 class=\"list__title\">{$title}</h3>
                    <div class=\"list__body\">
                    <div class=\"list__text\">Last chapter:&nbsp;&nbsp;{$chapters}</div>
                    <small class=\"list__text\">{$status}</small>
                    </div>
                    </a>
                    </div>";
        }
        $nextPage = $page + 1;
        $previousPage = $page - 1 === 0 ? 0 : $page - 1;
        $prev = $startPage == 0 ? '' : <<<HTML
        <form class="prev" method="POST" action="index.php?route=manga">
        <input type="hidden" name="page" value="{$previousPage}">
        <input type="submit" class="prev_btn" value="Previous">
        </form>
        HTML;

        $next = <<<HTML
        <form class="next" method="POST" action="index.php?route=manga">
        <input type="hidden" name="page" value="{$nextPage}">
        <input type="submit" class="next_btn" value="Next">
        </form>
        HTML;

        $this->HTML = <<<HTML
        <div class="mangaListingContainer">
            $prev
            $next
            $content
        </div>
HTML;


        } catch(PDOException $e) {
            throw new PDOException($e);
        }
    }


    public function outputJson() {
        echo $this->json;
    }

}