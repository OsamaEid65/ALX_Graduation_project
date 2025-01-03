<?php
// Start session to store login information
session_start();

// Path to the JSON file where users' data is stored
$file = 'users.json';

// Check if the file exists
if (!file_exists($file)) {
    die("No users found!");  // Using die() for better error handling
}

// Read the users data from the JSON file
$users = json_decode(file_get_contents($file), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists
    $found_user = null;
    foreach ($users as $user) {
        if ($user['username'] == $username) {
            $found_user = $user;
            break;
        }
    }

    if ($found_user) {
        // Verify the password
        if (password_verify($password, $found_user['password'])) {
            // Set session variables for logged-in user
            $_SESSION['username'] = $username;  // Store username in session

            // Redirect to profile page or dashboard (e.g., profile.php)
            header("Location: /New_Website_TT284/PHP/profile.php"); // Make sure the target page exists
            exit; // Stop further code execution after redirection
        } else {
            // Incorrect password, show message
            echo "Incorrect password!";
        }
    } else {
        // Username not found, show message
        echo "Username not found!";
    }
}
?>
