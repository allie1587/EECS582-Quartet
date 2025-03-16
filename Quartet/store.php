<!--
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/12/2025
Revisions:
    03/02/2025 -- Jose Leyba -- Changed UI to look better and dark mode implemented
    03/12/2025 -- Alexandra Stratton -- Added Edit Product menu button that allow users to edit the product page
    03/13/2025 -- Jose Leyba -- Started the Revamped UI of the page
    03/14/2025 --  Alexandra Stratton -- Included the header.php and added Shopping Cart to the menu
    03/16/2025 -- Jose Leyba -- Connected to database, UI now reflects when product gets added to the cart
    03/16/2025 -- Alexandra Stratton -- Connect the add to cart button to the shopping cart
Purpose: Store Page thaat will (later) allow users to see different products up to sale at the barbershop and their price

-->
<?php
// Start the session to remember user info
session_start();

//Connects to database to get the Reviews table information
$mysqli = new mysqli('sql312.infinityfree.com', 'if0_38323969', 'Quartet44', 'if0_38323969_quartet');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the reviews from the table
$productsQuery = "SELECT id, name, description, price, image FROM products";
$productsResult = $mysqli->query($productsQuery);

// Debugging: Check if the query was successful
if (!$productsResult) {
    die("Query Failed: " . $mysqli->error);
}

//Puts the Products table information in an easy to iterate way
$products = [];

if ($productsResult) {
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
}

$mysqli->close();

//Remembers the Cart in your session, if you didn't had one set it to empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

//Adds items to your cart when sending the post requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}
?>
<?php
include('header.php');
?>

<head>
    <!-- Title for Page --> 
    <title>Store</title>
    <!-- Internal CSS for styling the page -->
    <style>
        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px;
            max-width: 1000px;
            margin: auto;
        }
        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #333;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-container:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(255, 255, 255, 0.3);
        }
        .product-container img {
            width: 100%;
            max-width: 200px;
            border-radius: 10px;
            transition: opacity 0.3s ease;
        }
        .product-container img:hover {
            opacity: 0.85;
        }
        .product-name {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        } 
        .popup { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            background: #222; 
            padding: 20px; 
            border-radius: 10px; 
            text-align: center; 
            width: 300px; 
        }
        .popup img { 
            max-width: 100%; 
            border-radius: 10px; 
        }
        .popup button { 
            margin-top: 10px; 
            background: #c4454d; 
            color: white; 
            border: none; 
            padding: 10px; 
            cursor: pointer; 
        }
    </style>
    <!-- Functions needed-->
    <script>
        //Shows the details of a product when clicking it's image
        function showDetails(name, price, image, description) {
            document.getElementById('popup-name').innerText = name;
            document.getElementById('popup-price').innerText = "$" + price;
            document.getElementById('popup-image').src = image;
            document.getElementById('popup-description').innerText = description;
            document.getElementById('popup').style.display = 'block';
        }
        //Closes the popup
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        //Filter the products shown when searching for specific ones
        function filterProducts() {
            let search = document.getElementById('search').value.toLowerCase();
            let products = document.getElementsByClassName('product-container');
            for (let product of products) {
                let name = product.getElementsByClassName('product-name')[0].innerText.toLowerCase();
                product.style.display = name.includes(search) ? '' : 'none';
            }
        }
    </script>
</head>
<body>
    <!--let's user know the current page they are on-->
    <h1>Store</h1>
    <!--Styled grid 3x3 That shows in each space a different product available with a picture and it's name-->
    <input type="text" id="search" onkeyup="filterProducts()" placeholder="Search products...">
    <div class="store-grid">
        <?php foreach ($products as $product) { 
            // Makes sure the special characters don't leak into the SQL query
            $name = htmlspecialchars(addslashes($product['name']));
            $description = htmlspecialchars(addslashes($product['description']));
            $image = htmlspecialchars($product['image']);
            $price = htmlspecialchars($product['price']);
        ?>
            <div class='product-container' onclick="showDetails('<?php echo $name; ?>', '<?php echo $price; ?>', '<?php echo $image; ?>', '<?php echo $description; ?>')">
                <img src='<?php echo $image; ?>' alt='<?php echo $name; ?>'>
                <div class='product-name'><?php echo $product['name']; ?></div>
                <div class='product-price'>$<?php echo $price; ?></div>
                <form action="add_item.php" method="POST" style="display:inline;" onsubmit="event.stopPropagation();">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn add-to-cart-btn" onclick="event.stopPropagation();">Add to Cart</button>
                </form>
            </div>
        <?php } ?>
    </div>
    <!--Popup that appears/dissapears when clicking a product-->
    <div id="popup" class="popup">
        <img id="popup-image" src="" alt="Product Image">
        <h2 id="popup-name"></h2>
        <p id="popup-price"></p>
        <p id="popup-description"></p>
        <button onclick="closePopup()">Close</button>
    </div>
</body>
</html>
