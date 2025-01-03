<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $check_in = !empty($_POST["check_in_date"]) ? date('Y-m-d', strtotime($_POST["check_in_date"])) : null;
    $check_out = !empty($_POST["check_out_date"]) ? date('Y-m-d', strtotime($_POST["check_out_date"])) : null;
    $number_of_people = htmlspecialchars($_POST["number_of_people"]);
    $contact_number = htmlspecialchars($_POST["contact_number"]);
    $comment = htmlspecialchars($_POST["message"]);

    // Data to be stored in the JSON file
    $data = array(
        'name' => $name,
        'email' => $email,
        'check_in' => $check_in,
        'check_out' => $check_out,
        'number_of_people' => $number_of_people,
        'contact_number' => $contact_number,
        'comment' => $comment
    );

    // Read existing data from the file
    $json_file = 'bookings.json';
    if (file_exists($json_file)) {
        $existing_data = json_decode(file_get_contents($json_file), true);
    } else {
        $existing_data = array();
    }

    // Append new data to the existing array
    $existing_data[] = $data;

    // Write the updated data back to the JSON file
    file_put_contents($json_file, json_encode($existing_data, JSON_PRETTY_PRINT));

    // Redirect to the index page with a success message
    header("Location: /New_Website_TT284/index.html?message=success");
    exit();
    
}
?>
