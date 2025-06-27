<?php
// Path to the appointments file
$appointmentsFile = __DIR__ . '/appointments.json';

// Get appointment details from the form
$therapist = $_POST['therapist'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';

// Validate the input
if ($therapist && $date && $time) {
    // Validate date and time
    $currentDate = date('Y-m-d');
    if ($date < $currentDate) {
        header('Location: ../appointments.html?error=Invalid date selected.');
        exit;
    }

    // Create an appointment entry
    $appointment = [
        'therapist' => $therapist,
        'date' => $date,
        'time' => $time,
        'timestamp' => date('Y-m-d H:i:s'),
    ];

    // Read existing appointments
    $appointments = file_exists($appointmentsFile) ? json_decode(file_get_contents($appointmentsFile), true) : [];
    $appointments[] = $appointment;

    // Save the updated appointments
    file_put_contents($appointmentsFile, json_encode($appointments, JSON_PRETTY_PRINT));

    // Redirect to a success page
    header('Location: ../appointments.html?success=1');
    exit;
} else {
    // Redirect back with an error
    header('Location: ../appointments.html?error=Please fill all fields.');
    exit;
}
?>
