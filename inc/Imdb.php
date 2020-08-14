<?php

class Imdb
{

    private $db_conn;
    public $table;

    function __construct($db)
    {
        $this->db_conn = $db;
        $this->table = 'imdb';
    }

    public function create()
    {
        $sql = "INSERT INTO {$this->table} SET writer = ?, stars = ?, grade = ?, duration = ?, genre = ?, description = ?, thumbnail = ?, trailer = ?";

        $prep_state = $this->db_conn->prepare($sql);

        $prep_state->bindParam(1, $this->writer);
        $prep_state->bindParam(2, $this->stars);
        $prep_state->bindParam(3, $this->grade);
        $prep_state->bindParam(4, $this->duration);
        $prep_state->bindParam(5, $this->genre);
        $prep_state->bindParam(6, $this->description);
        $prep_state->bindParam(7, $this->thumbnail);
        $prep_state->bindParam(8, $this->trailer);

        if ($prep_state->execute()) {
            return 1;
        } else {
            return 0;
        }
    }
}
