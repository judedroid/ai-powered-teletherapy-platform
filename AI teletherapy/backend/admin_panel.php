<?php
session_start();

// Simple authentication (replace with a more secure method in production)
$adminUsername = 'admin';
$adminPassword = 'password123';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if ($_POST['username'] ?? '' === $adminUsername && $_POST['password'] ?? '' === $adminPassword) {
        $_SESSION['logged_in'] = true;
    } else {
        echo '<!DOCTYPE html>
              <html>
              <head>
                  <title>Admin Login</title>
                  <style>
                      body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; }
                      form { max-width: 400px; width: 100%; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
                      h2 { text-align: center; color: #007BFF; }
                      label { display: block; margin-bottom: 10px; font-weight: bold; }
                      input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
                      button { width: 100%; padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
                      button:hover { background-color: #0056b3; }
                  </style>
              </head>
              <body>
                  <form method="POST">
                      <h2>Admin Login</h2>
                      <label>Username:</label>
                      <input type="text" name="username" required>
                      <label>Password:</label>
                      <input type="password" name="password" required>
                      <button type="submit">Login</button>
                  </form>
              </body>
              </html>';
        exit;
    }
}

// Paths to data files
$messagesFile = __DIR__ . '/messages.json';
$usersFile = __DIR__ . '/users.json';
$appointmentsFile = __DIR__ . '/appointments.json';

// Handle actions
$action = $_GET['action'] ?? '';
if ($action === 'view_messages') {
    if (file_exists($messagesFile)) {
        $messages = json_decode(file_get_contents($messagesFile), true) ?? [];
        echo '<div class="content"><h2>Messages</h2>';
        foreach ($messages as $message) {
            echo '<div class="card"><pre>' . print_r($message, true) . '</pre></div>';
        }
        echo '</div>';
    } else {
        echo '<div class="content"><p>No messages found.</p></div>';
    }
} elseif ($action === 'send_message') {
    $recipient = $_POST['recipient'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    if ($recipient && $subject && $message) {
        echo "<div class='content'><p>Message sent to $recipient with subject '$subject'.</p></div>";
    } else {
        echo '<div class="content"><p>All fields are required to send a message.</p></div>';
    }
} elseif ($action === 'view_users') {
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true) ?? [];
        echo '<div class="content"><h2>Registered Users</h2>';
        foreach ($users as $user) {
            echo '<div class="card"><pre>' . print_r($user, true) . '</pre></div>';
        }
        echo '</div>';
    } else {
        echo '<div class="content"><p>No users found.</p></div>';
    }
} elseif ($action === 'add_user') {
    $newUser = [
        'username' => $_POST['username'] ?? '',
        'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
    ];
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
    $users[] = $newUser;
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    echo '<div class="content"><p>User added successfully.</p></div>';
} elseif ($action === 'delete_user') {
    $usernameToDelete = $_POST['username'] ?? '';
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
    $users = array_filter($users, fn($user) => $user['username'] !== $usernameToDelete);
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    echo '<div class="content"><p>User deleted successfully.</p></div>';
} elseif ($action === 'view_appointments') {
    if (file_exists($appointmentsFile)) {
        $appointments = json_decode(file_get_contents($appointmentsFile), true) ?? [];
        echo '<div class="content"><h2>Booked Appointments</h2>';
        foreach ($appointments as $appointment) {
            echo '<div class="card">';
            echo '<p><strong>Therapist:</strong> ' . htmlspecialchars($appointment['therapist']) . '</p>';
            echo '<p><strong>Date:</strong> ' . htmlspecialchars($appointment['date']) . '</p>';
            echo '<p><strong>Time:</strong> ' . htmlspecialchars($appointment['time']) . '</p>';
            echo '<p><strong>Booked On:</strong> ' . htmlspecialchars($appointment['timestamp']) . '</p>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="content"><p>No appointments found.</p></div>';
    }
} elseif ($action === 'logout') {
    session_destroy();
    header('Location: admin_panel.php');
    exit;
}

// Admin panel menu
echo '<!DOCTYPE html>
      <html>
      <head>
          <title>Admin Panel</title>
          <style>
              body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f9; }
              .navbar { background-color: #007BFF; padding: 15px; color: white; text-align: center; }
              .navbar a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
              .navbar a:hover { text-decoration: underline; }
              .content { padding: 20px; }
              h2 { color: #007BFF; }
              .card { background: white; padding: 15px; margin-bottom: 15px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
              form { margin-bottom: 20px; }
              label { display: block; margin-bottom: 5px; font-weight: bold; }
              input, textarea, button { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; }
              button { background-color: #007BFF; color: white; border: none; font-size: 16px; cursor: pointer; }
              button:hover { background-color: #0056b3; }
          </style>
      </head>
      <body>
          <div class="navbar">
              <a href="?action=view_messages">View Messages</a>
              <a href="?action=view_users">View Users</a>
              <a href="?action=view_appointments">View Appointments</a>
              <a href="?action=logout">Logout</a>
          </div>
          <div class="content">
              <h2>Admin Panel</h2>
              <form method="POST" action="?action=send_message">
                  <h3>Send Message</h3>
                  <label>Recipient:</label>
                  <input type="text" name="recipient">
                  <label>Subject:</label>
                  <input type="text" name="subject">
                  <label>Message:</label>
                  <textarea name="message"></textarea>
                  <button type="submit">Send Message</button>
              </form>
              <form method="POST" action="?action=add_user">
                  <h3>Add User</h3>
                  <label>Username:</label>
                  <input type="text" name="username">
                  <label>Password:</label>
                  <input type="password" name="password">
                  <button type="submit">Add User</button>
              </form>
              <form method="POST" action="?action=delete_user">
                  <h3>Delete User</h3>
                  <label>Username:</label>
                  <input type="text" name="username">
                  <button type="submit">Delete User</button>
              </form>
          </div>
      </body>
      </html>';
?>
