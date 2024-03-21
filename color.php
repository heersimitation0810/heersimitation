<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Item Details</title>
<style>
    /* Style for modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Style for color categories */
    .color-categories {
        margin-top: 20px;
    }

    .color-box {
        width: 30px;
        height: 30px;
        display: inline-block;
        margin-right: 10px;
        border: 1px solid #000;
    }
</style>
</head>
<body>

<!-- Item 1 -->
<div class="item" onclick="openModal('item1')">
    <img src="item1.jpg" alt="Item 1">
    <p>Item 1</p>
</div>

<!-- Item 2 -->
<div class="item" onclick="openModal('item2')">
    <img src="item2.jpg" alt="Item 2">
    <p>Item 2</p>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Item Details</h2>
        <div id="itemDetails"></div>
        <div class="color-categories">
            <div class="color-box" style="background-color: red;"></div>
            <div class="color-box" style="background-color: blue;"></div>
            <div class="color-box" style="background-color: green;"></div>
        </div>
    </div>
</div>

<script>
    // Function to open the modal and display item details
    function openModal(itemId) {
        var modal = document.getElementById("myModal");
        var itemDetails = document.getElementById("itemDetails");

        // Clear previous item details
        itemDetails.innerHTML = "";

        // Fetch item details based on itemId
        // You can replace this with your own logic to fetch item details
        var details;
        if (itemId === "item1") {
            details = "<p>Details for Item 1</p>";
        } else if (itemId === "item2") {
            details = "<p>Details for Item 2</p>";
        }

        // Display item details in the modal
        itemDetails.innerHTML = details;

        // Show the modal
        modal.style.display = "block";
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
