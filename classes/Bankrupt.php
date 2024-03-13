<?php
include_once './lib/Database.php';

/**
 * Bankrupt Class
 */
class Bankrupt
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function store($parameters)
    {
        $query = "INSERT IGNORE INTO bankrupts (`id`, `email`, `phone`, `inn`,
                `case_number`, `url`, `start_date`, `end_date`)
                VALUES (NULL, '$parameters[0]', '$parameters[1]', '$parameters[2]',
                '$parameters[3]','$parameters[4]', '$parameters[5]', '$parameters[6]')";
        $this->db->insert($query);
    }
}
