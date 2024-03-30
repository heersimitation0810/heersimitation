<?php
session_start();
include_once("config.php");
$imitation = new imitation();
$msg = '';

if(!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location:index.php");
}

if(isset($_POST['submit'])) {
    $id = $_SESSION['user_id'];
    $condition = array("id" => $id);
    $query = $imitation->get("users", "email", NULL, $condition);

    if ($query[0]['email'] != $_POST['email']) {
        $condition = array("email" => $_POST['email']);
        $query = $imitation->get("users", "email", NULL, $condition);
        if(count($query) == 1) {
            $msg =  '<div class="alert alert-danger"><b>Already register this email address.</b></div>';
        } else {
            $array = array(
                "first_name"   => isset($_POST['firstname']) ? $_POST['firstname'] : '', 
                "last_name"    => isset($_POST['lastname']) ? $_POST['lastname'] : '', 
                "email"        => isset($_POST['email']) ? $_POST['email'] : '',
                "contact"      => isset($_POST['contact']) ? $_POST['contact'] : '', 
                "updated_at"   => date("Y-m-d H:i:s")
            );
        
            $condition = array("id" => $id);
            $result = $imitation->update("users", $array, $condition);
        
            if ($result == 1) {
                $msg = '<div class="alert alert-success"><b>Successful Updated</b></div>';
            } else {
                $msg = '<div class="alert alert-danger"><b>Something Went Wrong !!</b></div>';
            }    
        }
    } else {
        $array = array(
            "first_name"   => isset($_POST['firstname']) ? $_POST['firstname'] : '', 
            "last_name"    => isset($_POST['lastname']) ? $_POST['lastname'] : '', 
            "email"        => isset($_POST['email']) ? $_POST['email'] : '',
            "contact"      => isset($_POST['contact']) ? $_POST['contact'] : '', 
            "updated_at"   => date("Y-m-d H:i:s")
        );
    
        $condition = array("id" => $id);
        $result = $imitation->update("users", $array, $condition);
    
        if ($result == 1) {
            $msg = '<div class="alert alert-success"><b>Successful Updated</b></div>';
        } else {
            $msg = '<div class="alert alert-danger"><b>Something Went Wrong !!</b></div>';
        }
    }
}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/account.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
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

    <!-- WISHLIST AREA START -->
    <div class="liton__wishlist-area pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- PRODUCT TAB AREA START -->
                    <div class="ltn__product-tab-area">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="tab-pane fade active show">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="ltn__form-box">
                                                <form method="POST" name="accountFrm" id="accountFrm">
                                                    <?php echo $msg; ?>
                                                    <?php 
                                                        $con = array('id' => $_SESSION['user_id']);
                                                        $userData = $imitation->get('users', '*', NULL, $con);
                                                    ?>
                                                    <div class="row mb-50">
                                                        <div class="col-md-6">
                                                            <label>First name:</label>
                                                            <input type="hidden" name="user_id" value="<?php echo $userData[0]['id']; ?>">
                                                            <input type="text" name="firstname" placeholder="First Name" value="<?php echo $userData[0]['first_name']; ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Last name:</label>
                                                            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $userData[0]['last_name']; ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Contact:</label>
                                                            <input type="text" name="contact" inputmode="numeric" maxlength="10" placeholder="Contact number" value="<?php echo $userData[0]['contact']; ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Email:</label>
                                                            <input type="email" name="email" placeholder="Email Address" value="<?php echo $userData[0]['email']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="btn-wrapper">
                                                        <button type="submit" name="submit" class="btn theme-btn-1 btn-effect-1 text-uppercase">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PRODUCT TAB AREA END -->
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->

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
            $('#accountFrm').validate({
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
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/account.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:47 GMT -->
</html>

