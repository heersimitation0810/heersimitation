<?php

session_start();
include_once("config.php");
$imitation = new imitation();

if(isset($_POST['submit'])) {
    $order_array = array(
        'code'           => $_POST['code'],
        'cat_id'         => $_POST['category'],
        'name'           => $_POST['name'],
        'price'          => $_POST['price'],
        'h_price'        => $_POST['h_price'],
        'primary_img'    => basename($_FILES["primary_img"]["name"])
    );
    $orderResult = $imitation->insert('product', $order_array);
    
    if($orderResult) {
        $targetDir = "img/product/"; 
        $targetFile = $targetDir . basename($_FILES["primary_img"]["name"]);
        move_uploaded_file($_FILES["primary_img"]["tmp_name"], $targetFile);
        
        $order = "id DESC";
        $limit = "1";
        $productsql = $imitation->get('product', '*', NULL, NULL, $order, $limit);
        $productId = $productsql[0]['id'];
    }

    
    $matches = preg_grep('/^form\d+$/', array_keys($_POST));
    $filteredArray = array_intersect_key($_POST, array_flip($matches));

    if($_FILES['form0']['name'][0] != '') {
        $subProduct = array(
            "pro_id" => $productId,
            "image"  => basename($_FILES["primary_img"]["name"]),
            "color"  => $_POST['primary_img_color']
        );
        $subProductResult = $imitation->insert('product_image', $subProduct);
        
        for($i=0; $i < count($filteredArray); $i++) {
            $colorValur = $_POST["form{$i}"][0];
            $targetDir = "img/product/"; 
            $fileName = basename($_FILES["form{$i}"]["name"][0]); 
            $targetFilePath = $targetDir . $fileName; 
    
            $subProduct = array(
                "pro_id" => $productId,
                "image"  => $fileName,
                "color"  => $colorValur
            );
    
            $subProductResult = $imitation->insert('product_image', $subProduct);
    
            if($subProductResult) {
                move_uploaded_file($_FILES["form{$i}"]["tmp_name"][0], $targetFilePath);
            }
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Form</title>
  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>


<div class="container mt-5">
    <div style="text-align:center;">
        <h2>Product Form</h2>
    </div>
    <form name="productFrm" id="productFrm" method="POST" enctype='multipart/form-data'>
        <div class="row">
            <div class="col-md-6 border border-danger">
                    <div class="form-group">
                        <label for="textInput1">Product Code</label>
                        <input type="text" name="code" class="form-control" id="code" placeholder="Enter code">
                    </div>
                    <div class="form-group">
                        <label for="dropdown">Category:</label>
                        <select class="form-control" name="category" id="category">
                            <option value="">Select Category</option>
                            <option value="1">Earring</option>
                            <option value="2">Bracelet</option>
                            <option value="3">Ring</option>
                            <option value="4">Necklace</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="textInput2">Product Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="textInput2">Product Price</label>
                        <input type="text" name="price" class="form-control" id="price" placeholder="Enter Price">
                    </div>
                    <div class="form-group">
                        <label for="textInput2">Product H_Price</label>
                        <input type="text" name="h_price" class="form-control" id="h_price" placeholder="Enter H_Price">
                    </div>
                    <div class="form-group">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="textInput2">Product Primary Image</label>
                                    <input type="file" name="primary_img" class="form-control primaryimg" id="file">
                                    <img class="imagePreview" src="#" alt="Product Image" style="display:none; max-width: 80px; max-height: 80px;">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="primary_color" style="display:none;">
                                        <label for="textInput2">Primary Image Color</label>
                                        <input type="color" name="primary_img_color" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-md-6 border border-danger">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="hidden" id="formID" value="0">
                            <label>Product Image</label>
                            <input type="file" name="form0[]" id="file" class="form-control primary-img">
                            <img class="imagePreview" src="#" alt="Product Image" style="display:none; max-width: 80px; max-height: 80px;">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-contant">
                            <label for="" class="placeholder-name stay-detail">Product Color</label>
                            <input type="color" name="form0[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group" data-aos="fade-down" data-aos-delay="500">
                            <label for="" class="placeholder-name stay-detail"></label><br>
                            <a onClick="addFormField(); return false;" class="btn btn-success"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div id="divTxt"></div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
<!-- Bootstrap JS and jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    var count = 0;
    let currentCount = 0;
    function addFormField() {
        if(currentCount < 50){
            currentCount += 1;
            var id = $("#formID").val();
            if(id == 0) {
                id++;
            }
            var form = '<div class="stay-detail-box newfield" id="form'+ id +'">'
                    form += '<div class="form event-stay-form">'
                        form += '<div class="row input-form">'
                                form += '<div class="col-lg-4">'
                                    form += '<div class="input-contant">'
                                        form += '<label>Product Image</label>'
                                        form += '<input type="file" name="form'+ id +'[]" id="product_image'+ id +'" class="form-control primary-img" required>'
                                        form += '<img class="imagePreview" src="#" alt="Product Image" style="max-width: 80px; max-height: 80px;">'
                                    form += '</div>'
                                form += '</div>'
                                form += '<div class="col-lg-4">'
                                    form += '<div class="input-contant">'
                                        form += '<label for="" class="placeholder-name stay-detail">Product Color</label>'
                                        form += '<input type="color" name="form'+ id +'[]" id="color'+ id +'" class="form-control" required>'
                                    form += '</div>'
                                form += '</div>'
                                form += '<div class="col-lg-4">'
                                    form += '<div class="form-group">'
                                        form += '<label for="" class="placeholder-name stay-detail"></label><br>'
                                        form += '<a onClick="addFormField(); return false;" class="btn btn-success"><i class="fa fa-plus"></i></a>'
                                        form += '<a class="btn btn-danger remove" style="margin-left:5px;" id="form'+ id +'"><i class="fa fa-minus"></i></a>'
                                    form += '</div>'
                                form += '</div>'
                        form += '</div>'
            form += '</div>'
        form += '</div>'
        $("#divTxt").append(form);
        id = id - 1 + 2;
        document.getElementById("formID").value = id;
        } else {
            alert('You can not add more then 50')
        }
    }

    $(document).on("change", ".primary-img", function () {
        $('#primary_color').css('display', '');
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).next('.imagePreview').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    $(document).on("change", ".primaryimg", function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).next('.imagePreview').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    $(document).on('click','.remove',function() {
            $(this).closest("div.row").remove();
        });
</script>
</body>
</html>
