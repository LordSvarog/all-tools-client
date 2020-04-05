<?php
require_once 'functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curl = curl_init();
    $host = 'http://all-tools-api/api/';
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
}

if (isset($_POST['products-create'])) {
    $go = getInt($_POST['go']);

    curl_setopt($curl, CURLOPT_URL, $host . 'products/admin/');
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'go=' . $go);
    curl_setopt($curl, CURLOPT_POST, 1);

} elseif (isset($_POST['order-create'])) {
    $products = str_replace(' ', '', getStr($_POST['products']));

    curl_setopt($curl, CURLOPT_URL, $host . 'orders/admin/');
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'products=' . $products);
    curl_setopt($curl, CURLOPT_POST, 1);

} elseif (isset($_POST['order-payment'])) {
    $order = getInt($_POST['order']);
    $cost = getInt($_POST['cost']);

    curl_setopt($curl, CURLOPT_URL, $host . 'orders/admin/');
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-HTTP-Method-Override: PUT']);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "order={$order}&cost={$cost}");
    curl_setopt($curl, CURLOPT_POST, 1);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = curl_exec($curl);
    curl_close($curl);

    if ($result) {
        $answer = json_decode($result);
        $_SESSION['answer'] = ($answer instanceof stdClass) ? $answer->error : $answer;

        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Работа с REST API</title>
    <meta name="vieport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="http://faviconka.ru/ico/faviconka_ru_1528.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="http://faviconka.ru/ico/faviconka_ru_1528.ico" type="image/x-icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <form id="products-create" action="" method="post" enctype="application/x-www-form-urlencoded">
        <h3>Генерация товаров</h3>

        <input type="text" id="go" name="go" hidden value="1">

        <input type="submit" class="btn btn-success" name="products-create" value="Сгенерировать">
    </form>

    <form id="order-create" action="" method="post" enctype="application/x-www-form-urlencoded">
        <h3>Создание заказа</h3>

        <div class="form-group row">
            <label for="products" class="col-sm-1 col-form-label">Товары</label>
            <div class="col-sm-6">
                <input type="text" id="products" class="col-sm-3" name="products" placeholder="Введите ID товаров через запятую">
            </div>
        </div>

        <input type="submit" class="btn btn-info" name="order-create" value="Создать">
    </form>

    <form id="order-payment" action="" method="post" enctype="application/x-www-form-urlencoded">
        <h3>Оплата заказа</h3>

        <div class="form-group row">
            <label for="order" class="col-sm-1 col-form-label">Заказ</label>
            <div class="col-sm-6">
                <input type="number" id="order" class="col-sm-3" name="order" step="1" min="1">
            </div>
        </div>

        <div class="form-group row">
            <label for="cost" class="col-sm-1 col-form-label">Стоимость</label>
            <div class="col-sm-6">
                <input type="number" id="cost" class="col-sm-3" name="cost" step="1" min="1">
            </div>
        </div>

        <input type="submit" class="btn btn-warning" name="order-payment" value="Оплатить">
    </form>

<?php if ($_SESSION['answer']) : ?>
    <div id="answer-place">
        <?php echo 'Answer from REST API: ' . $_SESSION['answer']; ?>
    </div>
<?php endif; ?>

</body>
</html>
