<?php
// Path to the text file where login details will be stored
$loginsLogFile = __DIR__ . '/logins_log.txt';

// Simulate login data (replace this with actual login logic)
$email = $_POST['email'] ?? '';
$timestamp = date('Y-m-d H:i:s');

// Validate the login data
if ($email) {
    // Log the login details to the text file
    $logEntry = "Email: $email\nTimestamp: $timestamp\n\n";
    file_put_contents($loginsLogFile, $logEntry, FILE_APPEND);

    // Redirect to the dashboard
    header('Location: ../dashboard.html');
    exit;
} else {
    // Redirect back to the login page with an error message
    header('Location: ../login.html?error=1');
    exit;
}
?>
