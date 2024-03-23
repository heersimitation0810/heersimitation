<?php
session_start();
include_once("config.php");
include_once("email.php");
$imitation = new imitation();
$msg = "";

if (isset($_POST['submit'])) {
    $condition = array("email" => $_POST['email']);
    $query = $imitation->get("users", "email", NULL, $condition);
    if (count($query) == 1) {
        $msg =  '<div class="alert alert-danger">Already register this email address.</div>';
    } else {
        $array = array(
            "first_name" => $_POST['firstname'],
            "last_name" => $_POST['lastname'],
            "contact" => $_POST['contact'],
            "email" => $_POST['email'],
            "password" => base64_encode($_POST['password']),
        );
        
        $result = $imitation->insert("users", $array);
        $email = $_POST['email'];
        if($result) {
            $otp = rand(100000, 999999);
            $_SESSION['otp']  = $otp;
            $_SESSION['email'] = $email;
            $name = $_POST['firstname'] . ' ' . $_POST['lastname'];

            $html = "Dear $name, <br><br>
                    Thank you for choosing Heers imitation jewellery house. To ensure the security of your account, we require you to verify your email address with the following One-Time Password (OTP):
                    <br><br>
                    OTP: <b>$otp</b>
                    <br><br>
                    Please use the above OTP to complete the verification process. This OTP is valid for a limited time period only and should not be shared with anyone. If you did not request this OTP, please ignore this email.
                    <br><br>
                    Best regards, <br>
                    <b>Heer's Imitation Jewellery House</b>
                    <br><br>
                    <img src='cid:logo' alt='Company Logo' style='height:80%; width:80%;'>";

            if(smtp_mailer($email, 'OTP Request', $html)) {
                header("Location:otp.php");
            } else {
                $msg =  '<div class="alert alert-danger">Something went wrong !!!</div>';
            }
        } else {
            $msg =  '<div class="alert alert-danger">Something went wrong !!!</div>';
        }
    }
}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
<head>
    <?php include_once('links.php'); ?>
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="body-wrapper">

    <!-- HEADER AREA START (header-5) -->
    <?php include_once('header.php'); ?>
    <!-- HEADER AREA END -->
    
    <!-- Utilize Cart Menu Start -->
    <div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <span class="ltn__utilize-menu-title">Cart</span>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="mini-cart-product-area ltn__scrollbar">
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/1.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Red Hot Tomato</a></h6>
                        <span class="mini-cart-quantity">1 x $65.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/2.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Vegetables Juices</a></h6>
                        <span class="mini-cart-quantity">1 x $85.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/3.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Orange Sliced Mix</a></h6>
                        <span class="mini-cart-quantity">1 x $92.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/4.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Orange Fresh Juice</a></h6>
                        <span class="mini-cart-quantity">1 x $68.00</span>
                    </div>
                </div>
            </div>
            <div class="mini-cart-footer">
                <div class="mini-cart-sub-total">
                    <h5>Subtotal: <span>$310.00</span></h5>
                </div>
                <div class="btn-wrapper">
                    <a href="cart.html" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                    <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                </div>
                <p>Free Shipping on All Orders Over $100!</p>
            </div>

        </div>
    </div>
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- LOGIN AREA START (Register) -->
    <div class="ltn__login-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Register</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        <form name="registerFrm" id="registerFrm" method="POST" class="ltn__form-box contact-form-box">
                            <?php echo $msg; ?>
                            <input type="text" name="firstname" id="firstname" placeholder="First Name">
                            <input type="text" name="lastname" id="lastname" placeholder="Last Name">
                            <input type="text" name="email" id="email" placeholder="Email*">
                            <input type="text" name="contact" id="contact" inputmode="numeric" maxlength="10" placeholder="Contact Number*">
                            <input type="password" name="password" id="password" placeholder="Password*">
                            <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password*">
                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit" name="submit">CREATE ACCOUNT</button>
                            </div>
                        </form>
                        <div class="by-agree text-center">
                            <div class="go-to-btn">
                                <a href="login.php">ALREADY HAVE AN ACCOUNT ?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- LOGIN AREA END -->

    <!-- FOOTER AREA START -->
    <?php include_once('footer.php'); ?>
    <!-- FOOTER AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add validation rules to the form
            $('#registerFrm').validate({
                rules: {
                    firstname: {
                        required: true
                    },
                    lastname: {
                        required: true
                    },
                    contact: {
                        required: true,
                        digits: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                    },
                    confirmpassword: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    firstname: {
                        required: "Please enter the first name"
                    },
                    lastname: {
                        required: "Please enter the last name"
                    },
                    contact: {
                        required: "Please enter the contact number",
                        digits: "Please enter only digits",
                    },
                    email: {
                        required: "Please enter the email",
                        email: "Please enter valid email address"
                    },
                    password: {
                        required: "Please enter the password"
                    },
                    confirmpassword: {
                        required: "Please enter the confirm password",
                        equalTo: "Passwords do not match"
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
</html>

