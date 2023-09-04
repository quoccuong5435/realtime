<?php
class MessageController {
    public Config  $config;
    public function __construct()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        $this->config = new Config();
    }

    public function insertChat() {
        $db = $this->config->connect();
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($db, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($db,  $_POST['message']);

        if (empty($incoming_id) or empty($outgoing_id)) {
            echo "Can not send messsage this time";
            return;
        }

        if (!empty($message)) {
            $assoc_array = [
                'incoming_message_id' => $incoming_id,
                'outgoing_message_id' => $outgoing_id,
                'message' => $message,
            ];
            $keys = array();
            $values = array();
            foreach($assoc_array as $key => $value){
                $keys[] = $key;
                $values[] = $value;
            }
            $insert_query = mysqli_query($db,  "INSERT INTO message(`".implode("`,`", $keys)."`) VALUES('".implode("','", $values)."')");
        }
    }

    public function getChat(){
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($this->config->connect(), $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM message LEFT JOIN user ON user.unique_id = message.outgoing_message_id
                WHERE (outgoing_message_id = {$outgoing_id} AND incoming_message_id = {$incoming_id})
                OR (outgoing_message_id = {$incoming_id} AND incoming_message_id = {$outgoing_id}) ORDER BY message_id";
        $query = mysqli_query($this->config->connect(), $sql);
        if($query &&  mysqli_num_rows($query)>0){
            while ($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_message_id'] === $outgoing_id){
                    $output .= '<div class="chat outgoing">
                                  <div class="details">
                                    <p>'.$row['message'].'</p>
                                  </div>
                                </div>';
                } else {
                    $output .= '<div class="chat incoming">
                                  <div class="details">
                                    <p>'.$row['message'].'</p>
                                  </div>
                                </div>';
                }
            }
        } else {
            $output .= "<div class='text'>Không có tin nhắn. Khi bạn có, tin nhắn sẽ hiện tại đây.</div>";
        }
        echo $output;
    }
}