<?php
// Path to the JSON file where users' data will be stored
$file = 'users.json';

// Check if the file exists, and if not, create an empty JSON array to begin with
if (!file_exists($file)) {
    file_put_contents($file, json_encode([])); // Empty array
}

// Get the data from the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password == $confirm_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Read existing users data from the JSON file
        $users = json_decode(file_get_contents($file), true);

        // Check if the username already exists
        foreach ($users as $user) {
            if ($user['username'] == $username) {
                echo "Username already exists!";
                exit;
            }
        }

        // Create a new user entry
        $new_user = [
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ];

        // Add the new user to the existing users array
        $users[] = $new_user;

        // Save the updated users array back to the JSON file
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        echo "User registered successfully!";
    } else {
        echo "Passwords do not match!";
    }
}
?>
