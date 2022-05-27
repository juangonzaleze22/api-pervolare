
<?php
    class db {
        private $servidor = "localhost";
        private $user = "root";
        private $password = ""; 
        private $datebase = "pervolare";

        public function connectDb(){
            date_default_timezone_set('America/Bogota');
            $con = new mysqli($this->servidor, $this->user, $this->password, $this->datebase);
            mysqli_set_charset($con, 'utf8');
            if ($con->connect_error) {
                return $con->connect_error;
            }
            return $con; 
        }
    }
?>