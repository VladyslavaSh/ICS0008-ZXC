<header>
    <a id="logo_link" href="./index.php">
    <img id="logo" src="./img/logo.png" alt="logo">
    </a>

    <input type="checkbox" id="check">
    <label for="check" class="nav_btn">
        <img src="./img/menu_icon.png" alt="menu button">
    </label>

    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./about.php">About</a></li>
            <li><a href="./catalog.php">Order</a></li>
            <li><a href="./policy.php">Policy</a></li>
            <li><a href="./support.php">Support</a></li>
            <?php
            if(isset($_SESSION["email"])) {
                echo '<li><a id="avatar_link" href="./account.php"><img id="avatar" src="./img/user_icon.png"></a></li>';
                echo '<li><a href="./logout.php" id="LogOut">Log Out</a></li>';
            }
            if(!isset($_SESSION["email"])) echo '<li><a href="./login.php" id="LogIn">Log In</a></li>';
            ?>
        </ul>
    </nav>

</header>
