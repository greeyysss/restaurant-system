<?php
session_start();
include 'db.php';

// Auto-detect page background files and add body classes
$body_classes = 'page-menu';
if (file_exists(__DIR__ . '/assets/images/bg-menu.jpg')) {
    $body_classes .= ' has-bg';
}
if (file_exists(__DIR__ . '/assets/images/bg-forms.jpg')) {
    $body_classes .= ' forms-has-bg';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu - Restaurant</title>
    <link rel="stylesheet" href="/restaurant_system/assets/styles.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="<?php echo $body_classes; ?>">
    <div class="site-container">
    <h2>Choose Your Orders</h2>
    <form method="POST" action="place_order.php">
        <div class="menu-grid">
            <p><a href="my_orders.php">View My Orders</a></p>

            <?php
            // Static menu items since your DB doesn't store names/prices
            $menu = [
                "Milktea" => 39,
                "Fries" => 20,
                "Burger" => 90,
                "Coke" => 10,
                "Shawarma" => 60,
                "Takoyaki" => 60,
                "Frappe" => 99,
                "Siomai" => 10
            ];

            foreach ($menu as $food => $price) {
                // sanitize a name-safe input key
                $key = htmlspecialchars($food, ENT_QUOTES);
                echo "<div class='menu-card' data-name='{$key}' data-price='{$price}'>";
                // Generic product image lookup: check assets/images/{slug}.{ext} or project root, then fallback to placeholder.
                $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($food));
                $found = false;
                $candidates = ['jpg','jpeg','png','svg'];
                $imgSrc = '/restaurant_system/assets/images/' . $slug . '.svg'; // default fallback to a same-named svg in assets
                foreach ($candidates as $ext) {
                    $path1 = __DIR__ . '/assets/images/' . $slug . '.' . $ext;
                    $path2 = __DIR__ . '/' . $slug . '.' . $ext;
                    if (file_exists($path1)) {
                        $imgSrc = '/restaurant_system/assets/images/' . $slug . '.' . $ext;
                        $found = true;break;
                    } elseif (file_exists($path2)) {
                        $imgSrc = '/restaurant_system/' . $slug . '.' . $ext;
                        $found = true;break;
                    }
                }
                // final fuzzy fallback: if there's a file in the project root or assets/images that contains the slug (useful for misspellings like 'bruger.jpg')
                if (!$found) {
                    // search assets/images and project root for similar filenames (contains first)
                    $matches = glob(__DIR__ . '/assets/images/*' . $slug . '*.{jpg,jpeg,png,svg}', GLOB_BRACE) ?: [];
                    $matches = array_merge($matches, glob(__DIR__ . '/*' . $slug . '*.{jpg,jpeg,png,svg}', GLOB_BRACE) ?: []);
                    if (!empty($matches)) {
                        // pick the first contain-match
                        $m = $matches[0];
                        if (strpos($m, DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR) !== false) {
                            $imgSrc = '/restaurant_system/assets/images/' . basename($m);
                        } else {
                            $imgSrc = '/restaurant_system/' . basename($m);
                        }
                        $found = true;
                    } else {
                        // no contains-match: use Levenshtein fuzzy matching to find a close filename
                        $all = array_merge(glob(__DIR__ . '/assets/images/*.{jpg,jpeg,png,svg}', GLOB_BRACE) ?: [], glob(__DIR__ . '/*.{jpg,jpeg,png,svg}', GLOB_BRACE) ?: []);
                        $best = null; $bestDist = PHP_INT_MAX;
                        foreach ($all as $fpath) {
                            $name = pathinfo($fpath, PATHINFO_FILENAME);
                            // normalize names for comparison
                            $n = preg_replace('/[^a-z0-9]+/i', '', strtolower($name));
                            $dist = levenshtein($slug, $n);
                            if ($dist < $bestDist) { $bestDist = $dist; $best = $fpath; }
                        }
                        // accept near matches (distance threshold 2)
                        if ($best !== null && $bestDist <= 2) {
                            if (strpos($best, DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR) !== false) {
                                $imgSrc = '/restaurant_system/assets/images/' . basename($best);
                            } else {
                                $imgSrc = '/restaurant_system/' . basename($best);
                            }
                            $found = true;
                        }
                    }
                }
                // final fallback to the placeholder we created earlier
                if (!$found && file_exists(__DIR__ . '/assets/images/milktea.svg')) {
                    $imgSrc = '/restaurant_system/assets/images/milktea.svg';
                }
                // If this is the burger, prefer a 'contain' rendering to avoid cropping tall/wide photos
                $extraClass = (strtolower($food) === 'burger') ? ' contain' : '';
                echo "<div class='thumb'><img src='" . $imgSrc . "' alt='".htmlspecialchars($food,ENT_QUOTES)."' class='product-img" . $extraClass . "' loading='lazy'></div>";
                echo "<div><div class='title'>{$food}</div><div class='price'>₱{$price}</div></div>";
                echo "<div class='controls'>";
                echo "<button type='button' class='btn-qty' data-action='dec'>-</button>";
                echo "<div class='qty'>0</div>";
                echo "<button type='button' class='btn-qty' data-action='inc'>+</button>";
                echo "</div>";
                // hidden input to submit to server
                echo "<input type='hidden' name='order[{$key}]' value='0'>";
                echo "</div>";
            }
            ?>
        </div>
        <br>
        <button type="submit">Place Order</button>
    </form>

    <!-- Cart summary panel -->
    <div class="cart-summary" aria-live="polite">
      <h4>Cart</h4>
      <div class="cart-items">
        <div class="muted">No items yet</div>
      </div>
      <div class="cart-total">Total: ₱0.00</div>
    </div>

    </div>
    <script src="/restaurant_system/assets/app.js"></script>
</body>
</html>
