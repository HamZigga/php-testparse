<?php
include_once './lib/Database.php';

/**
 * Lot Class
 */
class Lot
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function store($parameters)
    {
        $query = "SELECT `id` FROM bankrupts WHERE `inn` = '$parameters[0]'";
        $result = $this->db->select($query);
        if (isset($result)) {
            foreach ($result as $row) {
                $ids[] = $row;
            }
            $bankruptid = $ids[0]['id'];
        }

        $query = "REPLACE INTO lots (`id`, `bankrupt_id`, `lot_number`, `lot_info`, `lot_price`)
        VALUES (NULL, '$bankruptid', '$parameters[1]', '$parameters[2]', '$parameters[3]')";
        $this->db->insert($query);
    }
}
