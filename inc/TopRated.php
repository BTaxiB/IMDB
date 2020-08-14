<?php

class TopRated
{

    private $db_conn;
    public $table;

    function __construct($db)
    {
        $this->db_conn = $db;
        $this->table = 'top_rated';
    }

    public function create()
    {
        $sql = "INSERT INTO {$this->table} SET title = ?, writer = ?, stars = ?, grade = ?, duration = ?, genre = ?, description = ?, thumbnail = ?, trailer = ?";

        $prep_state = $this->db_conn->prepare($sql);

        $prep_state->bindParam(1, $this->title);
        $prep_state->bindParam(2, $this->writer);
        $prep_state->bindParam(3, $this->stars);
        $prep_state->bindParam(4, $this->grade);
        $prep_state->bindParam(5, $this->duration);
        $prep_state->bindParam(6, $this->genre);
        $prep_state->bindParam(7, $this->description);
        $prep_state->bindParam(8, $this->thumbnail);
        $prep_state->bindParam(9, $this->trailer);

        if ($prep_state->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $prep_state = $this->db_conn->prepare($sql);

        $prep_state->execute();

        return $prep_state;
    }
}
