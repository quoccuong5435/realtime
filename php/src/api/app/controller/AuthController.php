<?php
class AuthController
{
    private Config $config;
    public function __construct()
    {
        $this->config = new Config();
    }

    public function checkAuth()
    {
        if (!isset($_SESSION['unique_id'])) {
            header("location: login.php");
        }
    }

    public function logout()
    {
        $this->checkAuth();
        $db = $this->config->connect();
        $logout_id = mysqli_real_escape_string($db,$_GET['logout_id']);
        if (isset($logout_id)) {
            $status = "0";
            $update_query = mysqli_query($db,"UPDATE user set status = '{$status}' WHERE unique_id = {$result['unique_id']}");
            if ($update_query) {
                session_unset();
                session_destroy();
                header("Location:login.php");
            }else {
                header("Location:users.php");
            }
        }
    }
    public function signup() {
        $db = $this->config->connect();
        $fname = mysqli_real_escape_string($this->config->connect(), $_POST['fname']);
        $lname = mysqli_real_escape_string($this->config->connect(), $_POST['lname']);
        $email = mysqli_real_escape_string($this->config->connect(), $_POST['email']);
        $password = mysqli_real_escape_string($this->config->connect(), $_POST['password']);
        $error = "";
        if (empty($fname) or empty($lname) or empty($email) or empty($password)) {
            $error = "Field Require";
        }

        if (!filter_var($email , FILTER_SANITIZE_EMAIL)) {
            $error = "Email no match format";
        }

        $query  = mysqli_query($db, "SELECT * FROM user WHERE email ='($email)'" );
        if (mysqli_num_rows($query) > 0) {
            $error = "Email exit";
        }

        if (isset($_FILES['image'])) {
            $img_name = $_FILES['image']['name'];
            $img_type = $_FILES['image']['type'];
            $tmp_name = $_FILES['image']['tmp_name'];

            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ['jpeg', 'png', 'png'];

            if (!in_array($img_ext, $extensions)) {
                $error = "Import Image Only";
            }

            $type = ['images/jpeg', 'images/png' , 'images/jpg'];
            if (!in_array($img_type, $type)) {
                $error = "Import Image Only";
            }

            $time = time();
            if (move_uploaded_file($tmp_name, "images/".$img_name)) {
                $ran_id = rand($time, 10000000000);
                $status = "1";
                $encrypt_pass = md5($password);
                $assoc_array = [
                    'unique_id' => $ran_id,
                    'fname' => $fname,
                    'lname' => $lname,
                    'password' => $encrypt_pass,
                    'status' => $status,
                    'img' => $img_name,
                    'email' => $email,
                ];
                $keys = array();
                $values = array();
                foreach($assoc_array as $key => $value){
                    $keys[] = $key;
                    $values[] = $value;
                }
                $insert_query = mysqli_query($this->config->connect(),  "INSERT INTO user(`".implode("`,`", $keys)."`) VALUES('".implode("','", $values)."')");
                if (!$insert_query) {
                    $error = "Register fail";
                }
            }

            $query_2  = mysqli_query($db, "SELECT * FROM user WHERE email ='${email}'" );
            if (!mysqli_num_rows($query) > 0) {
                $result = mysqli_fetch_assoc($query_2);
                if ($result) {
                    $_SESSION['unique_id'] = $result['unique_id'];
                }
            }else {
                $error = "Email exit";
            }

        }

        if (!empty($error)) {
            echo $error;
            return;
        }
    }

    public function login() {
        $db = $this->config->connect();
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        $error = "";
        if (empty($email) or empty($password)) {
            $error = "Field Require";
        }

        $query  = mysqli_query($db, "SELECT * FROM user WHERE email ='($email)'" );
        if (mysqli_num_rows($query) == 0) {
            $error = "Email not exit";
        }

        $result = mysqli_fetch_assoc($query);
        if (md5($password) !== $result['password']) {
            $error = "Password no correct";
        }

        $status =  "1";
        $update_query = mysqli_query($db,"UPDATE user set status = '{$status}' WHERE unique_id = {$result['unique_id']}");
        if ($update_query) {
            $_SESSION['unique_id'] = $result['unique_id'];
            echo "Login success";
        }else {
            $error = "Login fail";
        }

        if (!empty($error)) {
            return $error;
        }
    }
}