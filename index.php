<?php $menu = 1; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.min.css">
    <title>iPay Home</title>
</head>
<body>
    <div class="container">
        <section class="content">
            <?php include 'includes/navigation.php'; ?>
            <section class="landing-bg">
                <img src="./images/ipay-logo-w.png" alt="">
                <h1>Welcome to iPay Online Investment</h1>
                <div class="welcome-note">
                    <p>
                        Your Number one stop for your effective and trustworthy investment, our system is design in a way that you
                        fund your wallet, from where you make your transaction; such as invest in the existing packages, send token,
                        receive token and makes some withdrawal in physical cash.
                    </p>
                </div>
                <div class="button">
                    <a href="./how-it-works.html">How it works</a>
                </div>
            </section>
            <section class="main-content">
                <div class="plan">Senior Man Plan</div>
                <div class="part-1">
                    <div class="detail">
                        <div class="gold-plan-title">Gold</div>
                        <img src="./images/gold.png" alt="gold">
                        <p class="desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Similique sapiente soluta fugit assumenda harum dolores ratione omnis ipsa,
                            nam esse voluptate illum voluptatem nemo dolorem nulla deleniti rem magni.
                            <button class="buy-btn" onclick="location.href='./buy.php'">Buy</button>
                        </p>
                    </div>
                    <div class="detail">
                        <div class="silver-plan-title">Silver</div>
                        <img src="./images/silver.png" alt="silver">
                        <p class="desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Similique sapiente soluta fugit assumenda harum dolores ratione omnis ipsa,
                            nam esse voluptate illum voluptatem nemo dolorem nulla deleniti rem magni.
                            <button class="buy-btn">Buy</button>
                        </p>
                    </div>
                    <div class="detail">
                        <div class="bronze-plan-title">Bronze</div>
                        <img src="./images/bronze.png" alt="bronze">
                        <p class="desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Similique sapiente soluta fugit assumenda harum dolores ratione omnis ipsa,
                            nam esse voluptate illum voluptatem nemo dolorem nulla deleniti rem magni.
                            <button class="buy-btn">Buy</button>
                        </p>
                    </div>
                </div>

                <div class="plan">Small Bobo Plan</div>
                <div class="part-2">
                    <div class="detail">
                        <div class="commando-plan-title">Commando</div>
                        <img src="./images/commando.png" alt="commando">
                        <p class="desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Similique sapiente soluta fugit assumenda harum dolores ratione omnis ipsa,
                            nam esse voluptate illum voluptatem nemo dolorem nulla deleniti rem magni.
                            <button class="buy-btn">Buy</button>
                        </p>
                    </div>
                    <div class="detail">
                        <div class="seal-plan-title">Seal</div>
                        <img src="./images/seal.png" alt="seal">
                        <p class="desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Similique sapiente soluta fugit assumenda harum dolores ratione omnis ipsa,
                            nam esse voluptate illum voluptatem nemo dolorem nulla deleniti rem magni.
                            <button class="buy-btn">Buy</button>
                        </p>
                    </div>
                    <div class="detail">
                        <div class="marine-plan-title">Marine</div>
                        <img src="./images/marine.png" alt="marine">
                        <p class="desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Similique sapiente soluta fugit assumenda harum dolores ratione omnis ipsa,
                            nam esse voluptate illum voluptatem nemo dolorem nulla deleniti rem magni.
                            <button class="buy-btn">Buy</button>
                        </p>
                    </div>
                </div>
            </section>
            <footer class="footer">Copyright &copy; 2020. <a href="index.html">iPay Incorporated</a> | All Righ Reserved</footer>
        </section>
    </div>
</body>
</html>