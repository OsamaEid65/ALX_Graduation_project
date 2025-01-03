<?php
// Define file paths
$jsonFile = 'users.json'; // Path to the JSON file containing user data
$users = json_decode(file_get_contents($jsonFile), true); // Decode the JSON file into an array

// Get current user details (this can be retrieved based on session or a predefined user)
session_start();
$currentUser = $_SESSION['user'] ?? null; // Assuming user information is stored in session

if (!$currentUser) {
    echo "User not logged in.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Check if the current password matches
    if (!password_verify($currentPassword, $currentUser['password'])) {
        echo "Current password is incorrect.";
        exit;
    }

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "New passwords do not match.";
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update the password in the user data
    foreach ($users as $index => $user) {
        if ($user['username'] === $currentUser['username']) {
            $users[$index]['password'] = $hashedPassword;
            break;
        }
    }

    // Save updated user data to the JSON file
    file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));

    echo "Password updated successfully.";
} else {
    echo "Invalid request method.";
}
?>
