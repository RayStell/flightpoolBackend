<?php

// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=flightpool1;charset=utf8', 'root', null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Токен , который приходит
$token = getallheaders()['Authorization'];

// TODO: по токену получить document_number пользователя

// Получаем document_number пользователя по токену
$stmt = $pdo->prepare('SELECT document_number FROM users WHERE api_token = ?');
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Неверный токен']);
    exit;
}

$document_number = $user['document_number'];
$call = "
";
echo $document_number;
echo $call;
// Получаем места пассажира из таблицы passengers
$stmt = $pdo->prepare('SELECT place_from, place_back FROM passengers WHERE document_number = ?');
$stmt->execute([$document_number]);
$passenger = $stmt->fetch();

if (!$passenger) {
    http_response_code(404);
    echo json_encode(['error' => 'Пассажир не найден']);
    exit;
}

// Формируем ответ
$response = [
    'place_from' => $passenger['place_from'],
    'place_back' => $passenger['place_back']
];

// Отправляем JSON-ответ
header('Content-Type: application/json');
echo json_encode($response);

?>