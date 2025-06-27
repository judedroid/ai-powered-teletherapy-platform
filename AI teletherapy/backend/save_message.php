<?php
// Path to the JSON file where messages will be stored
$messagesFile = __DIR__ . '/messages.json';
$messagesLogFile = __DIR__ . '/messages_log.txt'; // New text file for logging messages

// Get the form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// Validate the form data
if ($name && $email && $subject && $message) {
    // Create a new message entry
    $newMessage = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Read existing messages from the JSON file
    $messages = [];
    if (file_exists($messagesFile)) {
        $messages = json_decode(file_get_contents($messagesFile), true) ?? [];
    }

    // Add the new message to the list
    $messages[] = $newMessage;

    // Save the updated messages back to the JSON file
    file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));

    // Log the message to the text file
    $logEntry = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message\nTimestamp: " . date('Y-m-d H:i:s') . "\n\n";
    file_put_contents($messagesLogFile, $logEntry, FILE_APPEND);

    // Redirect back to the contact page with a success message
    header('Location: ../contact%20us.html?success=1');
    exit;
} else {
    // Redirect back to the contact page with an error message
    header('Location: ../contact%20us.html?error=1');
    exit;
}
?>
