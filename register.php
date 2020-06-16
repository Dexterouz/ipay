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
                            <input type="text" name="f_name" id="f_name" placeholder="Enter your first name"
                                value= "<?php if (issset($_POST['f_name'])) {
                                    echo htmlspecialchars($_POST['f_name'], ENT_QUOTES);
                                }
                                ?>";
                            >
                        </div>
                        <div class="form-row">
                            <label for="last-name">Last Name:</label>
                            <input type="text" name="l_name" id="l_name" placeholder="Enter your last name"
                                value="<?php if (isset($_POST['l_name'])) {
                                    echo htmlspecialchars($_POST['l_name'], ENT_QUOTES);
                                }
                                ?>";
                            >
                        </div>
                        <div class="form-row">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email address"
                                value="<?php if (isset($_POST['email'])) {
                                    echo htmlspecialchars($_POST['email']);
                                }
                                ?>";
                            >
                        </div>
                        <div class="form-row">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" placeholder="Enter your password"
                                value="<?php if (isset($_POST['password'])) {
                                    echo htmlspecialchars($_POST['password']);
                                }
                                ?>";
                            >
                        </div>
                        <div class="form-row">
                            <label for="c_password">Confirm Password:</label>
                            <input type="password" name="c_password" id="c_password" placeholder="Confirm your password">
                        </div>
                        <div class="form-row">
                            <label for="phone_no">Phone Number:</label>
                            <input type="tel" name="phone_no" id="phone_no" placeholder="Enter your phone number"
                                value="<?php if (isset($_POST['phone_no'])) {
                                    echo htmlspecialchars($_POST['phone_no'], ENT_QUOTES);
                                }
                                ?>";
                            >
                        </div>
                        <div class="form-row">
                            <input type="submit" name="submit" id="submit" value="Submit">
                        </div>
                        <div class="form-row">
                            <p class="not">Already Registered? Click <a href="login.html">Here</a> to login</p>
                        </div>
                    </form>
                </div>
                </section>
                <?php include 'includes/footer.html'; ?>
        </section>
    </div>
</body>
</html>