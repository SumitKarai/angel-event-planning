<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$to = 'sumitkarari4@gmail.com';
$subject = 'New Consultation Request - Angel Event Planning';

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$number = isset($_POST['number']) ? trim($_POST['number']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';

if ($name === '' || $number === '' || $date === '') {
    header('Location: index.html?status=error');
    exit;
}

if (!preg_match('/^[0-9+\-()\s]{7,20}$/', $number)) {
    header('Location: index.html?status=error');
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    header('Location: index.html?status=error');
    exit;
}

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeNumber = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
$safeDate = htmlspecialchars($date, ENT_QUOTES, 'UTF-8');

$message = "You received a new consultation request:\n\n";
$message .= "Name: {$safeName}\n";
$message .= "Number: {$safeNumber}\n";
$message .= "Preferred Date: {$safeDate}\n";
$message .= "Submitted At: " . date('Y-m-d H:i:s') . "\n";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: Angel Event Planning <no-reply@localhost>';
$headers[] = 'Reply-To: no-reply@localhost';
$headersString = implode("\r\n", $headers);

$sent = mail($to, $subject, $message, $headersString);

if ($sent) {
    header('Location: index.html?status=success');
    exit;
}

header('Location: index.html?status=error');
exit;
?>
