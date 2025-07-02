<?php
require 'function.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embed Message - StegaCipher</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            text-align: center;
            font-size: 18px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }
    </style>
    <script>
        // Open the countdown modal with 5-second timer
        function openCountdownModal() {
            var countdown = 5;
            var countdownElement = document.getElementById('countdown');
            var modal = document.getElementById('countdownModal');
            var countdownInterval = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(countdownInterval); // Stop the countdown
                    window.location.href = 'index.php'; // Redirect to homepage
                }
            }, 1000); // Update every second

            modal.style.display = 'block'; // Show the modal
        }

        // Close the modal
        function closeModal() {
            var modal = document.getElementById('countdownModal');
            modal.style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <img src="assets/indexlogo.png" alt="Read StegImage" class="image">
    </header>
    <div class="container">
        <section class="section">
            <h2>Embed Message</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $file = $_FILES['file'];
                $message = $_POST['message'];
                $key = $_POST['key'];

                if (!$file || !$message || !$key) {
                    die("Invalid input.");
                }

                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filePath = $uploadDir . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    try {
                        $encryptedMessage = encryptMessage($message, $key);
                        $outputFile = embedMessage($filePath, $encryptedMessage, $key);

                        echo "<h1>Message Embedded Successfully!</h1>";
                        echo "<a href='$outputFile' download onclick='openCountdownModal()'>Download File</a>";
                        echo "<p>The countdown will appear in the modal shortly.</p>";
                    } catch (Exception $e) {
                        echo "<h1>Error: {$e->getMessage()}</h1>";
                    }
                } else {
                    echo "Failed to upload file.";
                }
            }
            ?>
        </section>
    </div>

    <!-- Countdown Modal -->
    <div id="countdownModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>You will be redirected to the homepage in <span id="countdown">5</span> seconds.</p>
        </div>
    </div>

    <footer>
        <small>By Tejas S, Student ID: 231VMTHR1168, (USN): 231VMTHR1168</small>
    </footer>
</body>
</html>
