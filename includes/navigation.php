<?php
    switch($menu) {
        case 1: # for home page
        session_start();
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="index.php"><img src="./images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="index.php">Home</a></li>
                <li><a href="how-it-works.php">How it works</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="right">
                <?php echo (isset($_SESSION['user_id']) ? '<a href="./member_page/dashboard.php">Dashboard</a>' : '<a href="./login.php">Login</a>');?>
                </li>
            </ul>
        </nav>

        <?php
        break;

        case 2: # for how-it-works page
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="index.php"><img src="./images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="index.php">Home</a></li>
                <li><a href="how-it-works.php">How it works</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="right">
                    <a href="login.php">Login</a>
                </li>
            </ul>
        </nav>
        <?php
        break;

        case 3: # for contact us page
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="index.php"><img src="./images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="index.php">Home</a></li>
                <li><a href="how-it-works.php">How it works</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="right">
                    <a href="login.php">Login</a>
                </li>
            </ul>
        </nav>
        <?php
        break;

        case 4: # for about us page
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="index.php"><img src="./images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="index.php">Home</a></li>
                <li><a href="how-it-works.php">How it works</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="right">
                    <a href="login.php">Login</a>
                </li>
            </ul>
        </nav>
        <?php
        break;

        case 5: # for register page
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="index.php"><img src="./images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="index.php">Home</a></li>
                <li><a href="how-it-works.php">How it works</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="right">
                <?php echo (isset($_SESSION['user_id']) ? '<a href="./logout.php">Logout</a>' : '<a href="./login.php">Login</a>');?>                
                </li>
            </ul>
        </nav>
        <?php
        break;

        case 6: #for login page
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="index.php"><img src="./images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="index.php">Home</a></li>
                <li><a href="how-it-works.php">How it works</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="right">
                <?php echo (isset($_SESSION['user_id']) ? '<a href="./logout.php">Logout</a>' : '<a href="./register.php">Register</a>');?>                
                </li>
            </ul>
        </nav>
        <?php
        break;

        case 7: # for dashboard page
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="../index.php"><img src="../images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../how-it-works.php">How it works</a></li>
                <li><a href="../about-us.php">About Us</a></li>
                <li><a href="../contact-us.php">Contact Us</a></li>
                <li class="right">
                    <?php echo (isset($_SESSION['user_id']) ? '<a href="../logout.php">Logout</a>' : '<a href="../login.php">Login</a>');?>
                </li>
            </ul>
        </nav>
        <?php
        break;

        case 8: # for generate wallet
            ?>
            <nav class="header">
            <ul>
                <span class="logo-wrapper">
                    <a href="../../index.php"><img src="../../images/ipay-logo.png" alt="ipay"></a>
                </span>
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../../how-it-works.php">How it works</a></li>
                <li><a href="../../about-us.php">About Us</a></li>
                <li><a href="../../contact-us.php">Contact Us</a></li>
                <li class="right">
                    <?php echo (isset($_SESSION['user_id']) ? '<a href="../../logout.php">Logout</a>' : '<a href="../../login.php">Login</a>');?>
                </li>
            </ul>
        </nav>
        <?php
        break;
    }
?>

