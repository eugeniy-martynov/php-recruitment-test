<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->addColumnLastVisit();
    }

    private function addColumnLastVisit()
    {
        $createQuery = <<<SQL
ALTER TABLE `pages`
ADD last_visit datetime; 
SQL;
        $this->database->exec($createQuery);
    }
}
