<?php
session_start();
include_once("config.php");
$imitation = new imitation();

$ip_address = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$os = php_uname('s');

$con = array('ip_address' => $ip_address);
$checkIP = $imitation->get('visitors', '*', NULL, $con);

if(count($checkIP) == 0) {
    $varray = array(
        "ip_address"       => $ip_address, 
        "browser"          => $browser,
        "operating_system" => $os,
    ); 
    $vresult = $imitation->insert("visitors", $varray);
}

if(isset($_POST['type'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    if($_POST['type'] == 'removeItem') {
        $proId = $_POST['proId'];
        $proCon = array("user_id" => $user_id, "pro_id" => $proId);
        $deletePro = $imitation->delete("tmp_cart", $proCon);

        if($deletePro) {
            $status = 'success';
        } else {
            $status = 'failed';
        }

        $userCon = array('user_id' => $user_id);
        $tmpResult = $imitation->get('tmp_cart', '*', NULL, $userCon);

        $resultArry = [
            'status' => $status,
            'totalProduct' => count($tmpResult)
        ];
        echo json_encode($resultArry);
        exit;
    }

    if($_POST['type'] == 'showCart') {
        $html = '';
        $select ="tmp_cart.qty, product.id, product.name, product.primary_img, product.h_price";
        $joins = "LEFT JOIN tmp_cart ON tmp_cart.pro_id = product.id
                    WHERE tmp_cart.user_id='$user_id'
                    GROUP BY product.id
                    ORDER BY tmp_cart.created_at DESC ";
        $product = $imitation->get('product', $select, $joins);

        $total = 0;
        foreach($product as $k => $v) { 
            $tmp = 0;
            $tmp = $v['qty'] * $v['h_price'];
            $html .= '<div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/'. $v['primary_img'] .'" alt="Image" style="height:50px; width:50px;"></a>
                        <span class="mini-cart-item-delete" id="remove" data-proid="'. $v['id'] .'"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">'. $v['name'] .'</a></h6>
                        <span class="mini-cart-quantity">'. $v['qty'] .' x  ₹'. $v['h_price'] .'</span>
                    </div>
                </div>';
            $total += $tmp;
        }

        $resultArry = [
            'html' => $html,
            'total' => $total
        ];

        echo json_encode($resultArry);
        exit;
    }
    if($_POST['type'] == 'addCart') {
        $con = array('user_id' => $user_id, 'pro_id' => $_POST['proId']);
        $checkPro = $imitation->get('tmp_cart', '*', NULL, $con);

        if(count($checkPro) >= 1) {
            $array = array("qty" => $_POST['qty']);
            $updateResult = $imitation->update("tmp_cart", $array, $con);
            echo 'added';
            exit;
        } else {
            $array = array(
                "user_id" => $user_id, 
                "pro_id"  => isset($_POST['proId']) ? $_POST['proId'] : null,
                "qty"     => isset($_POST['qty']) ? $_POST['qty'] : null,
            ); 
            $result = $imitation->insert("tmp_cart", $array);
    
            if($result) {
                $userCon = array('user_id' => $user_id);
                $tmpResult = $imitation->get('tmp_cart', '*', NULL, $userCon);

                $resultArry = [
                    'status' => 'success',
                    'totalProduct' => count($tmpResult)
                ];
                echo json_encode($resultArry);
                exit;
            } else {
                echo 'failed';
            }
        }

        exit;
    }

    if($_POST['type'] == 'addWishlist') {
        $con = array('user_id' => $user_id, 'pro_id' => $_POST['proId']);
        $checkPro = $imitation->get('wishlist', '*', NULL, $con);

        if(count($checkPro) >= 1) {
            echo 'added';
            exit;
        } else {
            $array = array(
                "user_id" => $user_id, 
                "pro_id"  => isset($_POST['proId']) ? $_POST['proId'] : null, 
            ); 
            $result = $imitation->insert("wishlist", $array);
    
            if($result) {
                echo 'success';
            } else {
                echo 'failed';
            }
        }
        exit;
    }

    if($_POST['type'] == 'getProductImg') {
        $select = "image";
        $condition = array('id' => $_POST['proImgId']);
        $result = $imitation->get('product_image', $select, NULL, $condition);

        echo $result[0]['image'];
        exit;
    }

    if($_POST['type'] == 'getProduct') {
        $proId = $_POST['proId'];
        $condition = array('id' => $_POST['proId']);
        $result = $imitation->get('product', '*', NULL, $condition);
        $price = $result[0]['h_price'] * 10 /100;

        
        $html = '<div class="ltn__quick-view-modal-inner">
                    <div class="modal-product-item">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="modal-product-img">
                                <img src="img/product/'. $result[0]['primary_img'] .'" alt="#" id="product-img">
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="modal-product-info">
                                <h3>'. $result[0]['name'] .'</h3>
                                <div class="product-price">
                                    <span>₹ ' . $result[0]['h_price'] . '.00</span>
                                    <del>₹ '. round($result[0]['h_price'] + $price) .'.00</del>
                                </div>
                                <div class="color-categories">';
                        
                        $con = array('pro_id' => $proId);
                        $proVarient = $imitation->get('product_image', '*', NULL, $con);
                        
                        if(count($proVarient) >= 1) {
                            foreach($proVarient as $k => $v) {
                                $html .= '<div class="color-box" data-proimg="'. $v['id'] .'" style="background-color: '. $v['color'] .';"></div>';
                            }
                        }

                        $html .= '</div>
                                  <div class="ltn__product-details-menu-2">
                                    <ul>
                                        <li>
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1" inputmode="numeric" min="1" name="qtybutton" class="cart-plus-minus-box" id="qty">
                                            </div>
                                        </li>
                                        <li>
                                            <a class="theme-btn-1 btn btn-effect-1" title="Add to Cart">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span class="addProduct" data-proid="'. $result[0]['id'] .'">ADD TO CART</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>';

        echo $html;
        exit;
    }
}
?>

<!doctype html>
<html class="no-js" lang="zxx">


<head>
    <?php include_once('links.php'); ?>
</head>

<body>
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
            <div class="mini-cart-product-area ltn__scrollbar" id="cartDetails"></div>
            <div class="mini-cart-footer">
                <div class="mini-cart-sub-total">
                    <h5>Subtotal: <span id="total"></span></h5>
                </div>
                <div class="btn-wrapper">
                <a href="checkout.php" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                </div>
            </div>

        </div>
    </div>
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- IMAGE SLIDER AREA START (img-slider-3) -->
    <div class="ltn__img-slider-area">
        <div class="container-fluid">
            <div class="row ltn__image-slider-4-active slick-arrow-1 slick-arrow-1-inner ltn__no-gutter-all" data-slick='{"autoplay": true, "autoplaySpeed": 5000, "pause": 5000}'>
                <?php
                    $orderBy = "pro_order ASC";
                    $slider = $imitation->get("slider", "*", NULL, NULL, $orderBy);

                    foreach($slider as $key => $val) { ?>
                        <div class="col-lg-12">
                            <div class="ltn__img-slide-item-4">
                                <a href="img/product/<?php echo $val['path']; ?>" data-rel="lightcase:myCollection">
                                    <img src="img/product/<?php echo $val['path']; ?>" alt="Image" style="height:250px; width:380px; object-fit: cover;">
                                </a>
                            </div>
                        </div>
                <?php 
                    }
                ?>
            </div>
        </div>
    </div>
    <!-- IMAGE SLIDER AREA END -->
    
    <!-- CATEGORY AREA START -->
    <div class="ltn__category-area section-bg-1--- pt-30 pb-85">
        <div class="container">
            <div class="row ltn__category-slider-active--- slick-arrow-1">
                <a href="shop.php?catid=1">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <div class="ltn__category-item ltn__category-item-3 text-center">
                            <div class="ltn__category-item-img">
                                <img src="img/icons/icon-img/earrings.png" alt="Image" style="height:50px; width:50px;">
                            </div>
                            <div class="ltn__category-item-name">
                                <h5><a href="shop.php?catid=1">Earring</a></h5>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="ltn__category-item ltn__category-item-3 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.php?catid=2">
                                <img src="img/icons/icon-img/bracelet.png" alt="Image" style="height:50px; width:50px;">
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5><a href="shop.php?catid=2">Bracelets</a></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="ltn__category-item ltn__category-item-3 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.php?catid=3">
                                <img src="img/icons/icon-img/rings.png" alt="Image" style="height:50px; width:50px;">
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5><a href="shop.php?catid=3">Rings</a></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="ltn__category-item ltn__category-item-3 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.php?catid=4">
                                <img src="img/icons/icon-img/necklace.png" alt="Image" style="height:50px; width:50px;">
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5><a href="shop.php?catid=4">Necklace</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CATEGORY AREA END -->

    <!-- FEATURE AREA START ( Feature - 3) -->
    <div class="ltn__feature-area mt-100 mt--65 d-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__feature-item-box-wrap ltn__feature-item-box-wrap-2 ltn__border section-bg-6">
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/8-trolley.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Free shipping</h4>
                                <p>On all orders over $49.00</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/9-money.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>15 days returns</h4>
                                <p>Moneyback guarantee</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/10-credit-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Secure checkout</h4>
                                <p>Protected by Paypal</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/11-gift-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Offer & gift here</h4>
                                <p>On all orders over</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area pt-120 pb-120 d-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-img-wrap about-img-left">
                        <img src="img/others/6.png" alt="About Us Image">
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-info-wrap">
                        <div class="section-title-area ltn__section-title-2">
                            <h6 class="section-subtitle ltn__secondary-color">Know More About Shop</h6>
                            <h1 class="section-title">Trusted Organic <br class="d-none d-md-block">  Food  Store</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</p>
                        </div>
                        <p>sellers who aspire to be good, do good, and spread goodness. We
                                democratic, self-sustaining, two-sided marketplace which thrives
                                on trust and is built on community and quality content.</p>
                        <div class="about-author-info d-flex">
                            <div class="author-name-designation  align-self-center mr-30">
                                <h4 class="mb-0">Jerry Henson</h4>
                                <small>/ Shop Director</small>
                            </div>
                            <div class="author-sign  align-self-center">
                                <img src="img/icons/icon-img/author-sign.png" alt="#">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->

    <!-- PRODUCT TAB AREA START (product-item-3) -->
    <div class="ltn__product-tab-area ltn__product-gutter">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Our Products</h1>
                    </div>
                    <div class="ltn__tab-menu ltn__tab-menu-2 ltn__tab-menu-top-right-- text-uppercase text-center">
                        <div class="nav">
                            <a class="active show" data-bs-toggle="tab" href="#liton_tab_3_1">Earrings</a>
                            <a data-bs-toggle="tab" href="#liton_tab_3_2" class="">Bracelets</a>
                            <a data-bs-toggle="tab" href="#liton_tab_3_3" class="">Rings</a>
                            <a data-bs-toggle="tab" href="#liton_tab_3_4" class="">Necklace</a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_tab_3_1">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                    <?php
                                        $order = "id DESC";
                                        $limit = "10";
                                        $condition = array('cat_id' => '1');
                                        $earrings = $imitation->get('product', '*', NULL, $condition, $order, $limit);

                                        foreach($earrings as $key => $val) { ?>
                                            <div class="col-lg-12">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="product-details.php?id=<?php echo base64_encode($val['id']); ?>">
                                                            <img src="img/product/<?php echo $val['primary_img']?>" alt="#" style="margin-top:10px; height:200px; width:200px;">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title"><a href="#"><?php echo $val['name']; ?></a></h2>
                                                        <span>P. <?php echo $val['code']; ?></span>
                                                        <div class="product-price">
                                                            <span>₹ <?php echo $val['h_price']; ?>.00</span>
                                                            <del>₹ <?php $price = $val['h_price'] * 10 /100; echo round($val['h_price'] + $price); ?>.00</del>
                                                        </div>
                                                        <span class="wishlist-cart-item-delete wishlist" data-proid="<?php echo $val['id']?>"><i class="far fa-heart"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                            }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_tab_3_2">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                    <?php 
                                        $order = "id DESC";
                                        $limit = "10";
                                        $ringcondition = array('cat_id' => '2');
                                        $ring = $imitation->get('product', '*', NULL, $ringcondition, $order, $limit);

                                        foreach($ring as $key => $val) { ?>
                                            <div class="col-lg-12">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="product-details.php?id=<?php echo base64_encode($val['id']); ?>">
                                                            <img src="img/product/<?php echo $val['primary_img']?>" alt="#" style="margin-top:10px; height:200px; width:200px;">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title"><a href="#"><?php echo $val['name']; ?></a></h2>
                                                        <span>P. <?php echo $val['code']; ?></span>
                                                        <div class="product-price">
                                                            <span>₹ <?php echo $val['h_price']; ?>.00</span>
                                                            <del>₹ <?php $price = $val['h_price'] * 10 /100; echo round($val['h_price'] + $price); ?>.00</del>
                                                        </div>
                                                        <span class="wishlist-cart-item-delete wishlist" data-proid="<?php echo $val['id']?>"><i class="far fa-heart"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                            }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_tab_3_3">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                    <?php 
                                        $order = "id DESC";
                                        $limit = "10";
                                        $braccondition = array('cat_id' => '3');
                                        $brac = $imitation->get('product', '*', NULL, $braccondition, $order, $limit);

                                        foreach($brac as $key => $val) { ?>
                                            <div class="col-lg-12">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="product-details.php?id=<?php echo base64_encode($val['id']); ?>">
                                                            <img src="img/product/<?php echo $val['primary_img']?>" alt="#" style="margin-top:10px; height:200px; width:200px;">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title"><a href="#"><?php echo $val['name']; ?></a></h2>
                                                        <span>P. <?php echo $val['code']; ?></span>
                                                        <div class="product-price">
                                                            <span>₹ <?php echo $val['h_price']; ?>.00</span>
                                                            <del>₹ <?php $price = $val['h_price'] * 10 /100; echo round($val['h_price'] + $price); ?>.00</del>
                                                        </div>
                                                        <span class="wishlist-cart-item-delete wishlist" data-proid="<?php echo $val['id']?>"><i class="far fa-heart"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                            }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_tab_3_4">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                <?php 
                                    $order = "id DESC";
                                    $limit = "10";
                                    $neccondition = array('cat_id' => '4');
                                    $nec = $imitation->get('product', '*', NULL, $neccondition, $order, $limit);

                                    foreach($nec as $key => $val) { ?>
                                        <div class="col-lg-12">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="product-details.php?id=<?php echo base64_encode($val['id']); ?>">
                                                            <img src="img/product/<?php echo $val['primary_img']?>" alt="#" style="margin-top:10px; height:200px; width:200px;">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title"><a href="#"><?php echo $val['name']; ?></a></h2>
                                                        <span>P. <?php echo $val['code']; ?></span>
                                                        <div class="product-price">
                                                            <span>₹ <?php echo $val['h_price']; ?>.00</span>
                                                            <del>₹ <?php $price = $val['h_price'] * 10 /100; echo round($val['h_price'] + $price); ?>.00</del>
                                                        </div>
                                                        <span class="wishlist-cart-item-delete wishlist" data-proid="<?php echo $val['id']?>"><i class="far fa-heart"></i></span>
                                                    </div>
                                                </div>
                                            </div>            
                                <?php 
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_tab_3_5">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/7.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">-19%</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        <li class="review-total"> <a href="#"> (24)</a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Carrots Group Scal</a></h2>
                                                <div class="product-price">
                                                    <span>$32.00</span>
                                                    <del>$46.00</del>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/13.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Poltry Farm Meat</a></h2>
                                                <div class="product-price">
                                                    <span>$78.00</span>
                                                    <del>$85.00</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/5.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">-19%</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        <li class="review-total"> <a href="#"> (24)</a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Carrots Group Scal</a></h2>
                                                <div class="product-price">
                                                    <span>$32.00</span>
                                                    <del>$46.00</del>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/15.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Orange Sliced Mix</a></h2>
                                                <div class="product-price">
                                                    <span>$150.00</span>
                                                    <del>$180.00</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/9.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Orange Fresh Juice</a></h2>
                                                <div class="product-price">
                                                    <span>$75.00</span>
                                                    <del>$92.00</del>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/14.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">-19%</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        <li class="review-total"> <a href="#"> (24)</a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Carrots Group Scal</a></h2>
                                                <div class="product-price">
                                                    <span>$32.00</span>
                                                    <del>$46.00</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/12.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Poltry Farm Meat</a></h2>
                                                <div class="product-price">
                                                    <span>$78.00</span>
                                                    <del>$85.00</del>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/10.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Orange Fresh Juice</a></h2>
                                                <div class="product-price">
                                                    <span>$75.00</span>
                                                    <del>$92.00</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/15.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Fresh Butter Cake</a></h2>
                                                <div class="product-price">
                                                    <span>$150.00</span>
                                                    <del>$180.00</del>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/6.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">-19%</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        <li class="review-total"> <a href="#"> (24)</a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Carrots Group Scal</a></h2>
                                                <div class="product-price">
                                                    <span>$32.00</span>
                                                    <del>$46.00</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/7.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Orange Sliced Mix</a></h2>
                                                <div class="product-price">
                                                    <span>$150.00</span>
                                                    <del>$180.00</del>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="img/product/11.png" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">-19%</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                                <i class="far fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        <li class="review-total"> <a href="#"> (24)</a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="product-details.html">Carrots Group Scal</a></h2>
                                                <div class="product-price">
                                                    <span>$32.00</span>
                                                    <del>$46.00</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT TAB AREA END -->

    <!-- COUNTER UP AREA START -->
    <div class="ltn__counterup-area pt-115" style="background-color:black;">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/customer.png" alt="#" style="height:50px; width:50px;"> </div>
                        <h1><span class="counter">500</span><span class="counterUp-icon">+</span> </h1>
                        <h6>Happy Clients</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/design.png" alt="#" style="height:50px; width:50px;"> </div>
                        <h1><span class="counter">1</span><span class="counterUp-letter">K</span><span class="counterUp-icon">+</span> </h1>
                        <h6>Imitation Designs</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/secure-payment.png" alt="#" style="height:50px; width:50px;"> </div>
                        <h1><span class="counter">100</span><span class="counterUp-icon">%</span> </h1>
                        <h6>Payment Secure</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/city.png" alt="#" style="height:50px; width:50px;"> </div>
                        <h1><span class="counter">25</span><span class="counterUp-icon">+</span> </h1>
                        <h6>City Cover</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COUNTER UP AREA END -->

    <!-- PRODUCT AREA START (product-item-3) -->
    <div class="ltn__product-area ltn__product-gutter pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Top Selling Items</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__tab-product-slider-one-active--- slick-arrow-1">
                <!-- ltn__product-item -->
                <?php
                    $orderBy = "RAND()";
                    $limit = "8";
                    $topResult = $imitation->get("product", "*", NULL, NULL, $orderBy, $limit);

                    foreach($topResult as $key => $val) { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                            <div class="ltn__product-item ltn__product-item-3 text-left">
                                <div class="product-img">
                                    <a href="product-details.php?id=<?php echo base64_encode($val['id']); ?>" style="display: flex; justify-content: center;">
                                        <img src="img/product/<?php echo $val['primary_img']; ?>" alt="#" style="margin-top:10px; height:200px; width:200px; object-fit: cover;">
                                    </a>
                                    <div class="product-badge">
                                        <ul>
                                            <li class="sale-badge">New</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h2 class="product-title" style="text-align:center;"><a href="product-details.html"><?php echo $val['name']; ?></a></h2>
                                    <div class="product-price" style="text-align:center;">
                                        <span>₹ <?php echo $val['h_price']; ?>.00</span>
                                        <del>₹ <?php $price = $val['h_price'] * 10 /100; echo round($val['h_price'] + $price); ?>.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- PRODUCT AREA END -->

    <!-- FOOTER AREA START -->
    <?php include_once('footer.php'); ?>
    <!-- FOOTER AREA END -->

    <!-- MODAL AREA START (Quick View Modal) -->
    <div class="ltn__modal-area ltn__quick-view-modal-area">
        <div class="modal fade" id="quick_view_modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body product-detail">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Add To Cart Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="add_to_cart_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closeViewModal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-info">
                                            <p class="added-cart" id="cart"><i class="fa fa-check-circle"></i>  Successfully added to your Cart</p>
                                            <div class="btn-wrapper">
                                                <a class="theme-btn-1 btn btn-effect-1" id="viewCart">View Cart</a>
                                                <a href="checkout.php" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Wishlist Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="liton_wishlist_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-info">
                                            <p class="added-cart" id="wishlist"><i class="fa fa-check-circle"></i>  Successfully added to your Wishlist</p>
                                            <div class="btn-wrapper">
                                                <a href="wishlist.php" class="theme-btn-1 btn btn-effect-1">View Wishlist</a>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- preloader area start -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
</body>
<script>
    var user_id = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";
    
    $('.view').click(function() {
        var proid = $(this).data("proid");
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: {
                type: "getProduct",
                proId: proid
            },
            success: function(response){
                $('.product-detail').html(response);
            }
        });
    });

    $(document).on('click', '.color-box', function() {
        var proimgid = $(this).data("proimg");
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: {
                type: "getProductImg",
                proImgId: proimgid
            },
            success: function(response){
                $('#product-img').attr('src', 'img/product/' + response);
            }
        });
    });

    $(document).on('click', '.wishlist', function() {
        if(user_id == '') {
            window.location.href = 'login.php';
        } else {
            var proid = $(this).data("proid");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: {
                    type: "addWishlist",
                    proId: proid
                },
                success: function(response){
                    $('#liton_wishlist_modal').modal('show');
                    if(response == 'added') {
                        $('#wishlist').text('Already added this product.');
                    } else {
                        $('#wishlist').html('<i class="fa fa-check-circle"></i>  Successfully added to your Wishlist');
                    }
                }
            });
        }
    });

    $(document).on('click', '.addProduct', function() {
        if(user_id == '') {
            window.location.href = 'login.php';
        } else {
            var proid = $(this).data("proid");
            var qty = $('#qty').val();
            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    type: "addCart",
                    proId: proid,
                    qty: qty
                },
                success: function(response){
                    $('#add_to_cart_modal').modal('show');
                    if(response.status == 'added') {
                        $('#cart').text('Already added this product.');
                    } else {
                        $('.totalPro').text(response.totalProduct);
                        $('#cart').html('<i class="fa fa-check-circle"></i>  Successfully added to your Cart');
                    }
                }
            });
        }
    });

    $(document).on('click', '#showCart', function() {
        $.ajax({
            url: 'index.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: "showCart",
            },
            success: function(response){
                $('#cartDetails').html(response.html);
                $('#total').html('₹' + response.total);
            }
        });
    });

    $(document).on('click', '#viewCart', function() {
        $('#showCart').click();
        $('.closeViewModal').click();
    });

    $(document).on('click', '#remove', function() {
        var proid = $(this).data("proid");
        $.ajax({
            url: 'index.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: "removeItem",
                proId: proid
            },
            success: function(response){
                if(response.totalProduct >= 1) {
                    $('.totalPro').text(response.totalProduct);
                } else {
                    $('.totalPro').text('');
                }
                $('#showCart').click();
            }
        });
    });
</script>

<!-- Mirrored from tunatheme.com/tf/html/Heer's Imitation-preview/Heer's Imitation/index-9.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:02 GMT -->
</html>

