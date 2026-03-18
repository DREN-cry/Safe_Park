<?php

function enviarWhatsApp($numero, $mensaje) {
    $token = "EAASpxqjZBPc0BQw2QcaxXFOSrbSOKMHVLajvM9LbwoZA0W9r2dArCnzOl5IrYL3lq7ZCG1UsOZCoAy8atVLSYIiZCOMqLuKGP97PvwZBeu4Hdy0HQyaryqfSXkckFZCZBJwVhtZC3Tdhrc8YRY7NiU1vafcqHMGxIhyth1pINLvCwDZAqPaBgGvmhX7iYIBQpCbGKcIqo9Qs2imUKxQB8ZB9Wped3BjjBE0XGOiCelpOFmR7uwRpZA13MouYAvit4Nu0vPltw5a7KRhtgcCqbEUvRvmUagZDZD";          // token válido
    $phone_id = "1055556810967521";    

    $url = "https://graph.facebook.com/v19.0/$phone_id/messages";

    $data = [
        "messaging_product" => "whatsapp",
        "to" => $numero,
        "type" => "text",
        "text" => [
            "body" => $mensaje
        ]
    ];

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

