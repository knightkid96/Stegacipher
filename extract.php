<?php
require 'function.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extract Message - StegaCipher</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="assets/indexlogo.png" alt="Read StegImage" class="image">
    </header>
    <div class="container">
        <section class="section">
            <h2>Extract Message</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $file = $_FILES['file'] ?? null;
                $key = $_POST['key'] ?? null;

                if (!$file || !$file['tmp_name']) {
                    echo "<p>Error: No file uploaded or invalid file.</p>";
                    exit;
                }

                if (!$key) {
                    echo "<p>Error: Missing decryption key.</p>";
                    exit;
                }

                $filePath = $file['tmp_name'];
                try {
                    // Validate the file MIME type
                    $mimeType = mime_content_type($filePath);
                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];

                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        throw new Exception("Unsupported file type. Please upload a valid image.");
                    }

                    // Extract the hidden message (encrypted)
                    $encryptedMessage = extractMessage($filePath, $key);

                    // Decrypt the message
                    $message = decryptMessage($encryptedMessage, $key);

                    echo "<h1>Message Extracted Successfully!</h1>";
                    echo "<p><strong>Hidden Message:</strong> $message</p>";
                } catch (Exception $e) {
                    echo "<h1>Error:</h1><p>{$e->getMessage()}</p>";
                }
            }
            ?>
        </section>
    </div>
    <footer>
        <small>By Tejas S, Student ID: 231VMTHR1168, (USN): 231VMTHR1168</small>
    </footer>
</body>
</html>
