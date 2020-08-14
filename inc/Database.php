<?php



class Database extends PDO {



    public function __construct($ini) {
        $this->cfg = $ini;



        try {

            parent::__construct($this->cfg['db_type'] . ':host=' . $this->cfg['db_host'] . ';dbname=' . $this->cfg['db_name'] . ';charset=utf8', $this->cfg['db_user'], $this->cfg['db_pass']);

            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            $custom_errormsg = 'Error connecting to database - <u>check your database connection properties in the constants.php file!</u>';

            echo "<br>\n <div style ='color:red'><strong>" . $custom_errormsg . "</strong></div><br>\n<br>\n ". $e->getMessage();

            echo "<br>\nPHP Version : ".phpversion()."<br>\n";

        }

    }



}

