<?php
include_once 'api/app/controller/AuthController.php';
include_once 'api/app/controller/UserController.php';
include_once 'api/app/Config.php';

$auth = new AuthController();
$auth->checkAuth();

$user = new UserController();
$result = $user->getUserById($_GET['user_id']);
include_once "part/header.php";
?>
<body>
<div class="wrapper">
    <section class="chat-area">
        <header>
            <a href="user.php" class="back-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <img src="api/images/<?php echo $result['img']?> " alt="">
            <div class="details">
                <span><?php echo $result['lname'].' '.$result['fname'] ?></span>
                <p>
                    <?php  $text = $result['status'] == '0' ? 'Offline' : "Online";echo $text; ?>
                </p>
            </div>
        </header>

        <div class="chat-box">
            <div class="chat outgoing">
                <div class="details">
                    <p>1998</p>
                </div>
            </div>
            <div class="chat incoming">
                <div class="details">
                    <p>100</p>
                </div>
            </div>
        </div>
        <form action="" class="typing-area">
            <input type="text" name="incoming_id" class="incoming_id" value="<?php echo $_GET['user_id']; ?>" id="" hidden>
            <input type="text" name="message" class="input-field" placeholder="Nhập nội dung ở đây..." autocomplete="off">

            <button>
                <i class="fab fa-telegram-plane"></i>
            </button>
        </form>
    </section>
</div>
</body>
<script src="javascript/chat-event.js"></script>
</html>