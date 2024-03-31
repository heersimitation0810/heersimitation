<?php
session_start();
include_once("config.php");
$imitation = new imitation();

if(!isset($_GET['catid'])) {
    header("Location:index.php");
}

if(isset($_POST['type'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

    if($_POST['type'] == 'wishlist') {
        $_SESSION['page'] = 'shop.php?catid=' . $_POST['catId'] . '&page=' . $_POST['currentpage'];
    }

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
            
            $userCon = array('user_id' => $user_id);
            $tmpResult = $imitation->get('tmp_cart', '*', NULL, $userCon);

            $resultArry = [
                'status' => 'success',
                'totalProduct' => count($tmpResult)
            ];

            echo json_encode($resultArry);
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


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/shop.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:15 GMT -->
<head>
    <?php include_once('links.php'); ?>
</head>
<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="wrapper">

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

    <!-- PRODUCT DETAILS AREA START -->
    <div class="ltn__product-area ltn__product-gutter mt-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                                <div class="row">
                                    <!-- ltn__product-item -->
                                    <?php 
                                        $catId = $_GET['catid'];
                                        $items_per_page = 20;
                                        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $offset = ($current_page - 1) * $items_per_page;
                                        $condition = array('cat_id' => $catId);
                                        $orderBy = "id DESC LIMIT $offset, $items_per_page";
                                        
                                        $total_records = count($imitation->get('product', 'id', NULL, $condition));
                                        $total_pages = ceil($total_records / $items_per_page);

                                        $condition = array('cat_id' => $catId);
                                        $earrings = $imitation->get('product', '*', NULL, $condition, $orderBy);

                                        foreach($earrings as $key => $val) { ?>
                                            <div class="col-xl-3 col-lg-4 col-sm-6 col-6">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="product-details.php?id=<?php echo base64_encode($val['id']); ?>">
                                                            <img src="img/product/<?php echo $val['primary_img']; ?>" alt="#" style="margin-top:10px; height:200px; width:200px;">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title"><a><?php echo $val['name']; ?></a></h2>
                                                        <span>P. <?php echo $val['code']; ?></span>
                                                        <div class="product-price">
                                                            <span>₹ <?php echo $val['h_price']; ?>.00</span>
                                                            <del>₹ <?php $price = $val['h_price'] * 10 /100; echo round($val['h_price'] + $price); ?>.00</del>
                                                        </div>
                                                        <span class="wishlist-cart-item-delete wishlist" data-proid="<?php echo $val['id']?>"><i class="far fa-heart"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            <ul>
                                <li><a href="?catid=<?php echo $_GET['catid']?>&page=<?php echo ($current_page > 1) ? $current_page - 1 : 1; ?>"><i class="fas fa-angle-double-left"></i></a></li>
                                <?php
                                    $page = 0; 
                                    for($i = 1; $i <= $total_pages; $i++) { 
                                    $page = $i;
                                ?>
                                    <li <?php if($i == $current_page) echo 'class="active"'; ?>>
                                        <a href="?catid=<?php echo $_GET['catid']?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php } ?>
                                <li><a href="?catid=<?php echo $_GET['catid']?>&page=<?php echo ($current_page < $total_pages) ? $current_page + 1 : $total_pages; ?>"><i class="fas fa-angle-double-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->

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

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
  
</body>
<script>
    var user_id = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";
    var catid = "<?php echo isset($_GET['catid']) ? $_GET['catid'] : ''; ?>";
    var page = "<?php echo $page; ?>";

    $('.view').click(function() {
        var proid = $(this).data("proid");
        $.ajax({
            url: 'shop.php?catid=' + catid,
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
            url: 'shop.php?catid=' + catid,
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
            $.ajax({
                url: 'shop.php?catid=' + catid,
                method: 'POST',
                data: {
                    type: "wishlist",
                    catId: catid,
                    currentpage: page
                },
                success: function(response){
                    window.location.href = 'login.php';
                }
            });
        } else {
            var proid = $(this).data("proid");
            $.ajax({
                url: 'shop.php?catid=' + catid,
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
                url: 'shop.php?catid=' + catid,
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
            url: 'shop.php?catid=' + catid,
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
            url: 'shop.php?catid=' + catid,
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
<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/shop.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:15 GMT -->
</html>

