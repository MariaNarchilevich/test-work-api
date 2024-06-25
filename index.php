<?php
$apiKey = "wj0uMxwdUVX7PBEIjTB6h0GQ1lyJYkkg";
$url = "https://dimm.retailcrm.ru/api/v5/orders?apiKey=". $apiKey;

$headers = array(
    "Content-Type: application/x-www-form-urlencoded"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$data = json_decode($response, true);

if (isset($data['success']) && $data['success'] == true) {
    $productsCount = [];
    $productsAmount = [];

    foreach ($data['orders'] as $order) {
        foreach ($order['items'] as $item) {
            $productId = $item['offer']['externalId'];
            $productName = $item['offer']['name'];
            $productQuantity = $item['quantity'];
            $productPrice = $item['initialPrice'];

            if (isset($productsCount[$productId])) {
                $productsCount[$productId] += $productQuantity;
                $productsAmount[$productId] += $productPrice * $productQuantity;
            } else {
                $productsCount[$productId] = $productQuantity;
                $productsAmount[$productId] = $productPrice * $productQuantity;
            }
        }
    }

    $maxQuantity = max($productsCount);
    $maxAmount = max($productsAmount);

    $topProductQuantity = array_search($maxQuantity, $productsCount);
    $topProductAmount = array_search($maxAmount, $productsAmount);

    echo "Топ товар по количеству в заказах: " . $topProductQuantity . " - " . $maxQuantity . " штук <br>";
    echo "Топ товар по сумме в заказах: " . $topProductAmount . " - " . $maxAmount . "<br>";
} else {
    echo "Ошибка получения данных <br>";
}

curl_close($ch);
?>
