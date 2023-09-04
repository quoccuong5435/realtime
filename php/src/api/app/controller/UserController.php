<?php
class UserController
{
    public Config $config;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->config = new Config();
    }

    public function getData() {
        $sql = "select * from user where not unique_id = {$_SESSION['unique_id']} order by user_id desc";
        $query = mysqli_query($this->config->connect(), $sql);
        $output = '';

        if (mysqli_num_rows($query) == 0) {
            $output .= "Không có bạn bè hoạt động";
        }elseif (mysqli_num_rows($query) > 0) {
            $output = $this->getFriendList($query);
        }
        echo  $output;
    }

    public function searchUser($searchTerm) {
        $sql = "SELECT * FROM user 
                WHERE NOT unique_id = {$_SESSION['unique_id']} 
                  AND (fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%')";
        $output = '';
        $query = mysqli_query($this->config->connect(), $sql);
        if(mysqli_num_rows($query) > 0) {
            $output .= $this->getFriendList($query);
        }else {
            $output .= "Không tìm thấy tin nhắn";
        }
        echo $output;
    }

    public function getUserById($unique_id){
        $sql = mysqli_query($this->config->connect(), "SELECT * FROM user WHERE unique_id = {$unique_id}");
        if(mysqli_num_rows($sql)>0){
            return mysqli_fetch_assoc($sql);
        }
    }
    public function getFriendList($query) {
        $rs = '';
        while($row = mysqli_fetch_assoc($query)) {
            $sql = "SELECT * FROM message WHERE 
                             (incoming_message_id = {$row['unique_id']} OR outgoing_message_id = {$row['unique_id']}) 
                         AND (outgoing_message_id = {$_SESSION['unique_id']} OR incoming_message_id = {$_SESSION['unique_id']})
                         ORDER BY message_id DESC LIMIT 1";
            $query_2 = mysqli_query($this->config->connect(),$sql);

            $last_mess =  '';
            $data =   mysqli_fetch_assoc($query_2) ;
            if(mysqli_num_rows($query_2) > 0) {
                $last_mess = $data['message'];
            }else {
                $last_mess = 'Không có tin nhắn';
            }

            if (strlen($last_mess) > 30) {
                $last_mess = substr($last_mess,0,30) . '...';
            }
            // last answer
            $you = '';
            if (isset($data['outgoing_message_id'])) {
                $_SESSION['unique_id'] == $data['outgoing_message_id'] ? $you = "Bạn: " : $you = '';
            }

            // answer activity
            $row['status'] == '0' ? $offline = "offline" : $offline = '';
            //content
            $rs .= '<a href="chat.php?user_id='.$row['unique_id'].'">
                    <div class="content">
                        <img src="api/images/'.$row['img'].'" alt="">
                        <div class="details">
                            <span>'.$row['lname'].' '.$row['fname'].'</span>
                            <div>'.$you. $last_mess.'</div>
                        </div>
                    </div>
                    <div class="status-dot'.$offline.'"><i class="fa fa-circle"></i></div>
                </a>';

        }
        return $rs;
    }


}