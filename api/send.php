<?php
header("Content-Type: application/json; charset=utf-8");

// السماح بالـ CORS إذا أردت إرسال الطلب من frontend فقط (خيار آمن)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Only POST method allowed."]);
    exit;
}

// جلب البيانات
$data = json_decode(file_get_contents("php://input"), true);

$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

// التحقق من البيانات
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit;
}

// إعداد البريد
$to = "moudaseryassin@gmail.com";
$subject = "رسالة جديدة من موقعك الشخصي (Portfolio)";
$body = "الاسم: $name\nالبريد الإلكتروني: $email\nالرسالة:\n$message";
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// محاولة الإرسال
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(["success" => true, "message" => "تم إرسال رسالتك بنجاح!"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "عذرًا، فشل إرسال الرسالة. حاول لاحقًا."]);
}
?>