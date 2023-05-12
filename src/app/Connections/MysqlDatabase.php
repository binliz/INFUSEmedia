<?php

namespace app\Connections;

use app\Interfaces\DBConnectionInterface;

class MysqlDatabase implements DBConnectionInterface
{
    protected \PDO $db;

    public function connect($config)
    {
        $this->db = new \PDO(
            $config['dsn'], $config['user'], $config['password']
        );
    }

    public function log(array $data): void
    {
        if (count(array_intersect_key($data, array_flip(['ip_address', 'user_agent', 'page_url']))) !== 3) {
            // LOG to Errors or throw
            return;
        }
        if (empty($data['ip_address'])) {
            // LOG to Errors or throw
            return;
        }
        if (empty($data['page_url'])) {
            // LOG to Errors or throw
            return;
        }

        $sql = 'INSERT INTO `logs` (`ip_address`,`user_agent`,`page_url`) VALUES (:ip_address,:user_agent,:page_url)' .
            ' ON DUPLICATE KEY UPDATE `views_count`=`views_count`+1 ';

        $sth = $this->db->prepare($sql);
        $sth->bindParam('ip_address', $data['ip_address']); // In real case i will be use inet_pton
        $sth->bindParam('user_agent', $data['user_agent']);
        $sth->bindParam('page_url', $data['page_url']);
        $sth->execute();
    }

}
