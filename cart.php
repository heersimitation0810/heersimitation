<?php
session_start();
include_once("config.php");
$imitation = new imitation();

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/cart.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
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

    <!-- BREADCRUMB AREA START -->
    <!-- BREADCRUMB AREA END -->

    <!-- SHOPING CART AREA START -->
    <div class="liton__shoping-cart-area mb-120 mt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <!-- <thead>
                                    <th class="cart-product-remove">Remove</th>
                                    <th class="cart-product-image">Image</th>
                                    <th class="cart-product-info">Product</th>
                                    <th class="cart-product-price">Price</th>
                                    <th class="cart-product-quantity">Quantity</th>
                                    <th class="cart-product-subtotal">Subtotal</th>
                                </thead> -->
                                <tbody>
                                    <tr>
                                        <td class="cart-product-remove">x</td>
                                        <td class="cart-product-image">
                                            <a href="product-details.html"><img src="img/product/1.png" alt="#"></a>
                                        </td>
                                        <td class="cart-product-info">
                                            <h4><a href="product-details.html">Vegetables Juices</a></h4>
                                        </td>
                                        <td class="cart-product-price">$149.00</td>
                                        <td class="cart-product-quantity">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="02" name="qtybutton" class="cart-plus-minus-box">
                                            </div>
                                        </td>
                                        <td class="cart-product-subtotal">$298.00</td>
                                    </tr>
                                    
                                    <tr class="cart-coupon-row">
                                        <td colspan="6">
                                            <div class="cart-coupon">
                                                <input type="text" name="cart-coupon" placeholder="Coupon code">
                                                <button type="submit" class="btn theme-btn-2 btn-effect-2">Apply Coupon</button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn theme-btn-2 btn-effect-2-- disabled">Update Cart</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="shoping-cart-total mt-50">
                            <h4>Cart Totals</h4>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Cart Subtotal</td>
                                        <td>$618.00</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping and Handing</td>
                                        <td>$15.00</td>
                                    </tr>
                                    <tr>
                                        <td>Vat</td>
                                        <td>$00.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Total</strong></td>
                                        <td><strong>$633.00</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="btn-wrapper text-right text-end">
                                <a href="checkout.html" class="theme-btn-1 btn btn-effect-1">Proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->

    <!-- FEATURE AREA START ( Feature - 3) -->
    <!-- FEATURE AREA END -->

    <!-- FOOTER AREA START -->
    <?php include_once('footer.php'); ?>
    <!-- FOOTER AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
  
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/cart.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
</html>

