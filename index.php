<?php
require_once('database.php');

// Get category ID from the query string or default to 1 if not present
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
if ($category_id === false || $category_id === null) {
    $category_id = 1;
}

// Fetch the current category's details
$queryCategory = 'SELECT categoryName FROM categories WHERE categoryID = :category_id';
$statement1 = $db->prepare($queryCategory);
$statement1->bindValue(':category_id', $category_id);
$statement1->execute();
$category = $statement1->fetch();
$category_name = $category['categoryName'];
$statement1->closeCursor();

// Fetch all categories
$queryAllCategories = 'SELECT * FROM categories ORDER BY categoryID';
$statement = $db->prepare($queryAllCategories);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();

// Fetch products for the selected category
$queryProducts = 'SELECT productID, productCode, productName, description, listPrice, categoryID FROM products WHERE categoryID = :category_id ORDER BY productID';
$statement3 = $db->prepare($queryProducts);
$statement3->bindValue(':category_id', $category_id);
$statement3->execute();
$products = $statement3->fetchAll();
$statement3->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Product Manager</h1>
    </header>
    <main>
        <h1>Product List</h1>
        <aside>
            <h2>Categories</h2>
            <nav>
                <ul>
                    <?php foreach ($categories as $category) : ?>
                        <li><a href=".?category_id=<?php echo $category['categoryID']; ?>">
                            <?php echo htmlspecialchars($category['categoryName']); ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </aside>
        <section>
            <h2><?php echo htmlspecialchars($category_name); ?></h2>
            <table>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th class="right">Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['productCode']); ?></td>
                        <td><?php echo htmlspecialchars($product['productName']); ?></td>
                        <td class="right"><?php echo htmlspecialchars($product['listPrice']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>
                            <form action="modify_product.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['productID']; ?>">
                                <input type="hidden" name="category_id" value="<?php echo $product['categoryID']; ?>">
                                <input type="submit" value="Modify">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Guitar Shop, Inc.</p>
    </footer>
</body>
</html>
