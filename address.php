<?php 
session_start();
include_once("config.php");
include_once("email.php");
$imitation = new imitation();

if(!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location:index.php");
}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
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
    <div class="ltn__checkout-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content">
                            <div class="ltn__checkout-single-content-info mt-20" id="stopScrollHere">
                                <h3>Address Details</h3>
                                <div class="row">
                                <?php 
                                    $con = array('user_id' => $_SESSION['user_id']);
                                    $addressData = $imitation->get('address', '*', NULL, $con);

                                    if (count($addressData) >= 1) {
                                        foreach ($addressData as $key => $val) { 
                                            // Check if it's the first iteration, if so, add mt-4 class
                                            $mtClass = ($key == 1) ? 'mt-4' : '';

                                            ?>
                                            <div class="card <?php echo $mtClass; ?>" style="background-color: black; border: 1px solid gold;">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="address" name="address" <?php echo $val['default_status'] == '1' ? 'checked' : ''; ?> value="<?php echo $val['id']; ?>">
                                                        <label class="form-check-label" for="address">
                                                            <?php echo $val['address_line1'] . ', ' . $val['address_line2'] . ', ' . $val['city'] . ', ' . $val['state'] . ', ' . $val['country'] . ', ' . $val['zipcode']; ?>
                                                        </label>
                                                    </div>
                                                    <a href="edit-address.php?type=edit&id=<?php echo base64_encode($val['id']); ?>" style="position: absolute; top: 0px; right: 10px;">Edit</a>
                                                    <span style="position: absolute; top: 2px; left: 4px;"><i class="icon-cancel"></i></span>
                                                </div>
                                            </div>
                                    <?php  
                                        }
                                    } else { ?>
                                        <div class="col-md-12">
                                            <span>No Address Found</span>
                                        </div>
                                    <?php 
                                        }
                                    ?>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-md-12">
                                        <a href="add-address.php?type=add" class="btn theme-btn-1 btn-effect-1">Add New Address</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:17 GMT -->
</html>

