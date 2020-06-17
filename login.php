<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/report_message.min.css">
    <link rel="stylesheet" href="./styles/styles.min.css">
    <title>iPay Login Page</title>
</head>
<body>
    <div class="container">
        <section class="content">
            <?php include 'includes/navigation.php'; ?>
            
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                require 'process_login.php';
            }
            ?>
            <section class="login">
                <h1 class="title">Login</h1>
                <div class="login-container">
                    <form action="login.php" method="post" autocomplete="off">
                    <?php 
                        // for reporting error message(s)
                        if (isset($errors)) {
                            foreach ($errors as $error) {
                                echo '<span class="error">'.$error.'</span>';
                            }
                        }

                        // for reporting warning message(s)
                        if (isset($warnings)) {
                            foreach ($warnings as $warning) {
                                echo '<span class="warning">'.$warning.'</span>';
                            }
                        }
                        ?>    
                        <div class="form-row">
                            <label for="email">Email:</label>
                            <input type="email" name="email" placeholder="Enter your email address">
                        </div>
                        <div class="form-row">
                            <label for="password">Password:</label>
                            <input type="password" name="password" placeholder="Enter your password">
                        </div>
                        <div class="form-row">
                            <input type="submit" value="Login">
                        </div>
                        <div class="form-row">
                            <p class="not">Not registered? Click <a href="register.php">Here</a> to register</p>
                        </div>
                    </form>
                </div>
            </section>

        <?php include 'includes/footer.html'; ?>
        </section>
    </div>
</body>
</html>