<?php
session_start();

$jsonFile = 'users.json';

// Function to get profile data from the JSON file
function getProfileData($jsonFile) {
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        return $data ? $data : null;
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the uploaded file is an image
    if (getimagesize($_FILES["profile_picture"]["tmp_name"])) {
        // Validate file extension (only allow jpg, png, jpeg)
        if ($imageFileType === 'jpg' || $imageFileType === 'png' || $imageFileType === 'jpeg') {
            // Validate file size (max 5MB)
            if ($_FILES["profile_picture"]["size"] <= 5000000) {
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    // Update the profile picture path in the JSON data
                    $profileData[0]['profile_picture'] = $target_file;
                    updateProfileData($jsonFile, $profileData);
                    $image_message = 'Profile picture uploaded successfully.';
                    $profile_picture = $target_file; // Update the profile picture variable
                } else {
                    $image_message = 'Sorry, there was an error uploading your file.';
                }
            } else {
                $image_message = 'File is too large. Maximum size is 5MB.';
            }
        } else {
            $image_message = 'Only JPG, PNG, and JPEG files are allowed.';
        }
    } else {
        $image_message = 'The file is not an image.';
    }
}

// Function to update the profile data in the JSON file
function updateProfileData($jsonFile, $data) {
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}

// Get profile data
$profileData = getProfileData($jsonFile);

// Initialize variables with fallback values
$username = 'Unknown';
$email = 'Unknown';
$profile_picture = 'https://via.placeholder.com/150';

// Check if profile data is available and set the variables accordingly
if ($profileData && isset($profileData[0])) {
    $username = $profileData[0]['username'] ?? 'Unknown';
    $email = $profileData[0]['email'] ?? 'Unknown';
    $profile_picture = $profileData[0]['profile_picture'] ?? 'https://via.placeholder.com/150';
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($profileData && password_verify($current_password, $profileData[0]['password'])) {
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            // Update the password in the data
            $profileData[0]['password'] = $hashed_password;
            updateProfileData($jsonFile, $profileData);
            $message = 'Password successfully updated!';
        } else {
            $message = 'New password and confirmation do not match.';
        }
    } else {
        $message = 'Current password is incorrect.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="shortcut icon" type="x-icon" href="https://logos-download.com/wp-content/uploads/2016/05/Kempinski_logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }
        #navbar .container {
            width: 80%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #navbar ul {
            list-style: none;
            padding: 0;
        }
        #navbar ul li {
            display: inline;
            margin-left: 20px;
        }
        #navbar ul li a {
            color: #fff;
            text-decoration: none;
        }
        #navbar ul li a.current {
            font-weight: bold;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        .profile {
            background-color: #fff;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
        }
        .profile-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .profile-info {
            text-align: center;
        }
        .profile-image {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .profile-info h2 {
            margin-top: 10px;
            color: #333;
        }
        .profile-info p {
            font-size: 16px;
            color: #777;
        }
        .password-reset {
            margin-top: 30px;
            width: 100%;
            max-width: 500px;
        }
        .password-reset h2 {
            text-align: center;
            color: #333;
        }
        .password-reset form {
            display: flex;
            flex-direction: column;
        }
        .password-reset input {
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .password-reset button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .password-reset button:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 30px;
        }
        .profile-image {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<!-- Header with Navbar -->
<header>
    <nav id="navbar">
        <div class="container">
            <h1 class="logo"><a href="index.html"> <img src="https://images-ext-1.discordapp.net/external/_iLQIl3rD86aWGKTI-kRI2ZRYMXGN3FU8hdqntG7KIk/https/i.ibb.co/VQ6JBy2/Untitled-1.png?format=webp&quality=lossless&width=1025&height=404" style="width:200px;height:auto;"></a></h1>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="../about.html">About</a></li>
                <li><a href="../ContactUs.html">Contact Us</a></li>
                <li><a href="../booking.html">Booking</a></li>
                <li><a href="../services.html">Services</a></li>
            </ul>
        </div>
    </nav>
</header>

<!-- Profile Section -->
<section class="container profile">
    <div class="profile-header">
        <h1>User Profile</h1>
    </div>
    <div class="profile-content">
        <div class="profile-info">
            <!-- Profile Image -->
            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-image" id="profileImage">
            <h2><?php echo $username; ?></h2>
            <p>Email: <?php echo $email; ?></p>
            <p>Username: <?php echo $username; ?></p>

            <!-- Profile Image Upload -->
            <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
                <input type="file" name="profile_picture" accept="image/*" required>
                <button type="submit">Upload New Profile Picture</button>
            </form>

            <?php if (isset($image_message)) echo "<p>$image_message</p>"; ?>
        </div>

        <!-- Change Password Section -->
        <div class="password-reset">
            <h2>Reset Password</h2>
            <form action="" method="POST">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" required>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" required>

                <button type="submit" name="reset_password">Reset Password</button>
            </form>

            <?php if (isset($message)) echo "<p>$message</p>"; ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Kempinski Nile Hotel. All Rights Reserved.</p>
</footer>

<script>
    // JavaScript to hide the image after upload
    const uploadForm = document.getElementById('uploadForm');
    const profileImage = document.getElementById('profileImage');
    
    uploadForm.addEventListener('submit', function() {
        profileImage.classList.add('hidden');
    });
</script>

</body>
</html>
