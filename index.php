<?php
$arrContextOptions = [
    "ssl" => [
        "verify_peer" => FALSE,
        "verify_peer_name" => FALSE,
    ],
    'http' => [
        'header' => 'Connection: close\r\n',
    ],
];

$api_key = 'wj0uMxwdUVX7PBEIjTB6h0GQ1lyJYkkg';


$urn_data = [
    'apiKey' => $api_key,
    'limit'=> 100
];
$urn = http_build_query($urn_data);
$url_api = 'https://dimm.retailcrm.ru/api/v5/orders?' . $urn;

$json_content = file_get_contents($url_api, FALSE, stream_context_create($arrContextOptions));

$data = json_decode($json_content, TRUE);

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
    echo "Топ товар по сумме в заказах: " . $topProductAmount . " - " . $maxAmount . " <br>";
} else {
    echo "Ошибка получения данных\n";
}



$urn_data = [
    'apiKey' => $api_key,
    'task[text]' => "Проверить тестовое задание \n  Нарчилевич Мария исполнителя \n  ",
    'task[performerId]' => 6
];
$urn = http_build_query($urn_data);
$url_api = 'https://dimm.retailcrm.ru/api/v5/tasks/create?' . $urn;

$json_content = file_get_contents($url_api, FALSE, stream_context_create($arrContextOptions));

$data = json_decode($json_content, TRUE);

var_dump($data);
?>
