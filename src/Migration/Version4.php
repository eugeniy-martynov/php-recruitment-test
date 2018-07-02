<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version4
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->createVarnishTable();
        $this->addColumnVarnishId();
    }

    private function createVarnishTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnish` (
  `varnish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `varnish_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function addColumnVarnishId()
    {
        $createQuery = <<<SQL
ALTER TABLE `websites`
ADD varnish_id int(11); 
SQL;
        $this->database->exec($createQuery);
    }
}
