<?php

session_start();
include_once("config.php");
include_once("email.php");
$imitation = new imitation();
$msg = "";

if(isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    header("Location:index.php");
}

if(isset($_POST['submit'])) { 
    $email = $_POST['email'];
    $password = base64_encode($_POST['password']);
    $condition = array("email" => $email, "password" => $password);
    $result = $imitation->get("users", "*", NULL, $condition);
    
    if (count($result) == 1) {
        if($result[0]['verified'] == '1') {
            foreach ($result as $key => $value) {
                $_SESSION["user_id"] = $result[0]['id'];
                $_SESSION["email"] = $result[0]['email'];
                $_SESSION["password"] = $password;

                if(isset($_SESSION['page'])) {
                    $page = $_SESSION['page'];
                    header("Location:$page");
                } else {
                    header("Location:index.php");
                }
            }
            exit;
        } else {
            $otp = rand(10000, 999999);
            $_SESSION['otp']  = $otp;
            $_SESSION['email'] = $result[0]['email'];
            $email = $result[0]['email'];
            $name = $result[0]['first_name'] . ' ' . $result[0]['last_name'];

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
        }
    } else {
        $msg = '<div class="alert alert-danger">Please enter correct Email/Password.</div>';
    }
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
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
    
    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- LOGIN AREA START -->
    <div class="ltn__login-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Sign In</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="account-login-inner">
                        <form name="loginFrm" method="POST" id="loginFrm" class="ltn__form-box contact-form-box">
                            <?php echo $msg; ?>
                            <input type="text" name="email" id="email" placeholder="Email*">
                            <input type="password" name="password" id="password" placeholder="Password*">
                            <div class="btn-wrapper mt-0">
                                <button class="theme-btn-1 btn btn-block" type="submit" name="submit">SIGN IN</button>
                            </div>
                            <div class="go-to-btn mt-20">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="#"><small>Forgotten your password?</small></a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="register.php"><small>Create a new account ?</small></a>
                                    </div>
                                </div>
                            </div>
                        </form>
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
            $('#loginFrm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
                    email: {
                        required: "Please enter the email",
                        email: "Please enter valid email address"
                    },
                    password: {
                        required: "Please enter the password"
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
</html>

