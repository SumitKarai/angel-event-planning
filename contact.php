<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!is_array($data)) {
    $data = $_POST;
}

$name = isset($data['name']) ? trim($data['name']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';
$number = isset($data['number']) ? trim($data['number']) : '';
$date = isset($data['date']) ? trim($data['date']) : '';

if (empty($name) && empty($message) && empty($number)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Required fields missing"]);
    exit;
}

$to = "angelevent2211@gmail.com";
$subject = "New Inquiry from " . ($name ? $name : "Website Visitor");

$body = "You have received a new consultation inquiry from your website:\n\n";
if ($name) $body .= "Name: " . strip_tags($name) . "\n";
if ($number) $body .= "Phone Number: " . strip_tags($number) . "\n";
if ($date) $body .= "Preferred Date: " . strip_tags($date) . "\n";
if ($message) $body .= "Message:\n" . strip_tags($message) . "\n";
$body .= "\nSubmitted At: " . date("Y-m-d H:i:s") . "\n";

$headers = "From: Angel Event Solution <no-reply@angeleventsolution.in>\r\n" .
           "Reply-To: angelevent2211@gmail.com\r\n" .
           "X-Mailer: PHP/" . phpversion();

$sent = @mail($to, $subject, $body, $headers);

echo json_encode([
    "status" => $sent ? "success" : "sent_attempted",
    "message" => "Inquiry processed successfully."
]);
exit;
?>
