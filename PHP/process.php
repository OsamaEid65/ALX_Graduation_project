<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['address']) && !empty($_POST['message'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $address = htmlspecialchars($_POST['address']);
        $messageContent = htmlspecialchars($_POST['message']);

        // Data to be saved
        $data = [
            "name" => $name,
            "email" => $email,
            "address" => $address,
            "message" => $messageContent,
            "timestamp" => date("Y-m-d H:i:s")
        ];

        // File to save the data
        $file = 'contact_us_data.json';

        // Check if the file exists and is writable
        if (file_exists($file) && is_writable($file)) {
            // Get existing data and decode it
            $existingData = json_decode(file_get_contents($file), true);

            // Append new data
            if (!is_array($existingData)) {
                $existingData = [];
            }
            $existingData[] = $data;
        } else {
            // If the file doesn't exist or isn't writable, start with an empty array
            $existingData = [$data];
        }

        // Save data back to the file
        if (file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT))) {
            // Redirect with success message
            $successMessage = urlencode("Thank you, $name! Your message has been received.");
            header("Location: /New_Website_TT284/ContactUs.html?message=$successMessage");
            exit();
        } else {
            echo "Error: Unable to save your message. Please try again later.";
        }
    } else {
        echo "All fields are required.";
    }
}   
?>
