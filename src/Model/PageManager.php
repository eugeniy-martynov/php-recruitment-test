<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function trackLastUpdatePageById($id)
    {
        $now = new \DateTime();
        $now = date_format($now, 'Y-m-d H:i:s');
        /** @var \PDOStatement $statement */
        $statement = $this
            ->database
            ->prepare('UPDATE pages 
              SET last_visit = :last 
              WHERE page_id = :id;');
        $statement->bindParam(':last', $now, \PDO::PARAM_STR);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function getLeastRecentlyPageByWebsite($websiteIds)
    {
        /** @var \PDOStatement $query */
        $query = $this
            ->database
            ->prepare('SELECT * 
                FROM pages 
                WHERE website_id IN (' . $websiteIds . ') AND 
                last_visit = (SELECT MAX(last_visit) 
                  FROM pages
                  WHERE website_id IN (' . $websiteIds . '))');
        $query->execute();
        return $query->fetchObject(Page::class);
    }

    public function getMostRecentlyPageByWebsite($websiteIds)
    {
        /** @var \PDOStatement $query */
        $query = $this
            ->database
            ->prepare('SELECT * 
                FROM pages 
                WHERE website_id IN (' . $websiteIds . ') AND 
                last_visit = (SELECT MIN(last_visit) 
                  FROM pages
                  WHERE website_id IN (' . $websiteIds . '))');
        $query->execute();
        return $query->fetchObject(Page::class);
    }

    public function getCountOfPage($websiteIds)
    {
        /** @var \PDOStatement $query */
        $query = $this
            ->database
            ->prepare('SELECT COUNT(page_id) as count
                FROM pages 
                WHERE website_id IN (' . $websiteIds . ')');
        $query->execute();
        return $query->fetchObject();
    }
}
