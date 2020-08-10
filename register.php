<?php $menu = 5; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/report_message.min.css">
    <link rel="stylesheet" href="./styles/styles.min.css">
    <title>iPay Register Page</title>
</head>
<body>
    <div class="container">
        <section class="content">
        <?php include 'includes/navigation.php'; ?>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require 'process_register.php';
        }
        ?>
            <section class="register">
                <h1 class="title">Register</h1>
                <div class="register-container">
                    <form action="register.php" method="post">
                        <?php 
                        // for reporting error message(s)
                        if (isset($errors)) {
                            foreach ($errors as $error) {
                                echo '<span class="error">'.$error.'</span>';
                            }
                        }

                        // for reporting success message(s)
                        if (isset($success)) {
                            foreach ($success as $successMsg) {
                                echo '<span class="success">'.$successMsg.'</span>';
                            }
                        }
                        ?>
                        <div class="form-row">
                            <label for="first-name">First Name:</label>
                            <input type="text" name="f_name" id="f_name" placeholder="Enter your first name">
                        </div>
                        <div class="form-row">
                            <label for="last-name">Last Name:</label>
                            <input type="text" name="l_name" id="l_name" placeholder="Enter your last name">
                        </div>
                        <div class="form-row">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email address">
                        </div>
                        <div class="form-row">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" placeholder="Enter your password">
                        </div>
                        <div class="form-row">
                            <label for="c_password">Confirm Password:</label>
                            <input type="password" name="c_password" id="c_password" placeholder="Confirm your password">
                        </div>
                        <div class="form-row">
                            <label for="phone_no">Phone Number:</label>
                            <input type="tel" name="phone_no" id="phone_no" placeholder="Enter your phone number">
                        </div>
                        <div class="form-row">
                            <input type="submit" name="submit" id="submit" value="Submit">
                        </div>
                        <div class="form-row">
                            <input type="hidden" name="referral" value="<?=isset($_GET['referral']) ? $_GET['referral'] : '';?>">
                        </div>
                        <div class="form-row">
                            <p class="not">Already Registered? Click <a href="login.php">Here</a> to login</p>
                        </div>
                    </form>
                </div>
                </section>
                <?php include 'includes/footer.html'; ?>
        </section>
    </div>
</body>
</html>