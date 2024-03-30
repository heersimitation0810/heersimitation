<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $host;
$base_url .= "/jewellery/";

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
    $condition = array("email" => $email);
    $result = $imitation->get("users", "*", NULL, $condition);
    
    if (count($result) == 1) {
        $email = $result[0]['email'];
        $name = $result[0]['first_name'] . ' ' . $result[0]['last_name'];
        $resetLink = "<a href='". $base_url ."password-reset.php?email=". base64_encode($email) ."' target='_blank'>Password Reset Link</a>";    ;
        $html = "Dear $name, <br><br>
                We have received a request to reset the password associated with your account. To proceed with resetting your password, please click on the link below:
                <br><br>
                $resetLink
                <br><br>
                If you did not request this password reset, please disregard this email. Your account remains secure, and no changes have been made.
                <br><br>
                Best regards, <br>
                <b>Heer's Imitation Jewellery House</b>
                <br><br>
                <img src='cid:logo' alt='Company Logo' style='height:80%; width:80%;'>";

        if(smtp_mailer($email, 'Password Reset Request', $html)) {
            $msg = '<div class="alert alert-success">Password reset link send on email.</div>';
        } else {
            $msg =  '<div class="alert alert-danger">Something went wrong !!!</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Please enter correct email.</div>';
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
                        <h1 class="section-title">Forgotten Password</h1>
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
                            <div class="btn-wrapper mt-0">
                                <button class="theme-btn-1 btn btn-block" type="submit" name="submit">Reset Password</button>
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
                    }
                },
                messages: {
                    email: {
                        required: "Please enter the email",
                        email: "Please enter valid email address"
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
</html>

