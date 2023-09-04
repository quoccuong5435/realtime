<?php

include_once 'api/app/controller/AuthController.php';
include_once 'api/app/controller/UserController.php';
include_once 'api/app/Config.php';

$auth = new AuthController();
$auth->checkAuth();

$user = new UserController();
$result = $user->getUserById($_SESSION['unique_id']);


include_once "part/header.php";
?>
<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <img src="/api/images/<?php echo $result['img'] ?>" alt="">
                    <div class="details">
                        <span><?php echo $result['lname'].' '.$result['fname'] ?></span>
                        <p><?php  $text = $result['status'] == '0' ? 'Offline' : "Online";
                            echo $text;
                        ?></p>
                    </div>
                </div>
                <a href="api/logout.php?logout_id=<?php echo $result['unique_id'] ?>" class="logout">Logout</a>
            </header>
            <div class="search">
                <span class="text">Lựa chọn bạn bè để trò chuyện</span>
                <input type="text" name="search" id="" placeholder="Nhập tên để tìm kiếm">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">
                <a href="#">
                    <div class="content">
                        <img src="" alt="">
                        <div class="details">
                            <span>你好</span>
                            <div>Xin chào</div>
                        </div>
                    </div>
                    <div class="status-dot offline"><i class="fa fa-circle"></i></div>
                </a>
            </div>
        </section>
    </div>
</body>
<script src="javascript/users-event.js"></script>
</html>