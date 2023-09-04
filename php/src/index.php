<?php
    include_once "part/header.php"
?>
<body>
<div class="wrapper">
    <section class="form signup">
        <header>Phòng chat Realtime</header>
        <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="error-text"></div>
            <div class="name-details">
                <div class="field input">
                    <label>Tên</label>
                    <input type="text" name="fname" placeholder="Tên" required>
                </div>
                <div class="field input">
                    <label>Họ</label>
                    <input type="text" name="lname" placeholder="Họ" required>
                </div>
            </div>
            <div class="field input">
                <label>Email</label>
                <input type="text" name="email" placeholder="Nhập email" required>
            </div>
            <div class="field input">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                <i class="fas fa-eye"></i>
            </div>
            <div class="field image">
                <label>Select Image</label>
                <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
            </div>
            <div class="field button">
                <input type="submit" name="submit" value="Bắt đầu Chat">
            </div>
        </form>
        <div class="link">Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></div>
    </section>
</div>

<script src="javascript/pass-show-hide.js"></script>
<script src="javascript/signup.js"></script>
</body>
</html>
