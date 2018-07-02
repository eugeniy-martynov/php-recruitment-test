<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function getById($varnishId) {
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnish WHERE varnish_id = :id');
        $query->setFetchMode(\PDO::FETCH_CLASS, Varnish::class);
        $query->bindParam(':id', $varnishId, \PDO::PARAM_STR);
        $query->execute();
        /** @var Varnish $varnish */
        $varnish = $query->fetch(\PDO::FETCH_CLASS);
        return $varnish;
    }

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnish WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO varnish (ip, user_id) VALUES (:ip, :user)');
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }
}
