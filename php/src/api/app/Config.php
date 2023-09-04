<?php
class  Config
{
    public function connect()
    {
        $hostname = 'db';
        $username = 'realtime';
        $password = '123456';
        $dbname = 'realtime';

        $conn = mysqli_connect($hostname, $username, $password, $dbname);
        if (!$conn) {
            echo "No connect" . mysqli_connect_error();
        }
        return $conn;
    }
}