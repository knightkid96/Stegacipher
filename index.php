<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StegaCipher - Secure Message Embedding</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section with Ribbon -->
    <header>
    <img src="assets\indexlogo.png" alt="Read StegImage" class="image">
    </header>

    <!-- About Us Section -->
    <section class="section">
        <h2>About Us</h2>
        <p>
            Welcome to StegaCipher! This application enables you to securely embed secret messages into image files 
            and retrieve them using a key. <div> </div>A powerful tool for maintaining confidentiality in communication.
            <div> </div>
            The project "StegaCipher" Supports image formats like JPEG, PNG, GIF, BMP, and WebP.
        </p>
    </section>

    <!-- Main Content -->
    <div class="two-column-container">
        <!-- Left Column -->
        <div class="column">
            <div class="description">
                <h2>Create a StegImage</h2>
                <p>
                    Upload an image, type your secret message, and secure it with a key. <div> </div>The tool embeds the 
                    message into the image, making it completely hidden to the naked eye.
                </p>
            </div>
            <img src="assets\encrypt.png" alt="Create StegImage" class="image">
            
            <div>
            </div>
            <button id="createButton" class="button">Create a StegImage</button>
        </div>

        <!-- Right Column -->
        <div class="column">
            <div class="description">
                <h2>Read a StegImage</h2>
                <p>
                    Select a StegImage and provide the key to retrieve the hidden message.<div> </div> The process ensures 
                    only authorized users can access the data.
                </p>
            </div>
            <img src="assets\decrypt.png" alt="Read StegImage" class="image">
            <div>
            </div>
            <button id="readButton" class="button">Read a StegImage</button>
        </div>
    </div>

    <!-- Hidden Sections for Create and Read -->
    <div class="container">
        <!-- Embed Message Section -->
        <section id="createSection" class="section hidden">
            <h2>Create A StegaImage</h2>
            <form action="embed.php" method="POST" enctype="multipart/form-data" class="form">
                <label for="file">Choose Image File</label>
                <input type="file" name="file" required>
                <small>Supports Images Only (JPEG, PNG, GIF, BMP, and WebP)</small>

                <label for="message">Message</label>
                <textarea name="message" required placeholder="Enter your secret message here..."></textarea>

                <div class="key-input">
                    <label for="key">Enter the Private Key</label>
                    <div class="key-field-wrapper">
                    <div>
                        <input type="password" name="key" id="createKeyField" required placeholder="Enter a secret key">
                        <span class="eye-icon" onclick="toggleKeyVisibility('createKeyField')">👁️</span>
                    </div>
                    </div>
                </div>

                <button type="submit" class="button">Embed Message</button>
            </form>
        </section>

        <!-- Extract Message Section -->
        <section id="readSection" class="section hidden">
            <h2>Read A StegaImage</h2>
            <form action="extract.php" method="POST" enctype="multipart/form-data" class="form">
                <label for="file">Choose Image File</label>
                <input type="file" name="file" required>

                <div class="key-input">
                    <label for="key">Enter the Private Key</label>
                    <div class="key-field-wrapper">
                    <div>
                        <input type="password" name="key" id="readKeyField" required placeholder="Enter the secret key">
                        <span class="eye-icon" onclick="toggleKeyVisibility('readKeyField')">👁️</span>
                    </div>
                    </div>
                </div>

                <button type="submit" class="button">Extract Message</button>
            </form>
        </section>
    </div>

    <!-- How It Works -->
    <section class="section">
        <h2>How It Works?</h2>
        <p>
        This project uses Least Significant Bit (LSB) Steganography to hide and extract secret messages in images without altering their appearance.<div> </div>
        The message is converted into binary and embedded by modifying the least significant bits of the image’s pixel colors,<div> </div>
        ensuring the changes are visually undetectable. To extract the message, we read these bits, reconstruct the text,<div> </div>
        and validate it using a key if encryption is applied. This method securely hides messages in plain sight while keeping the image intact..<div> </div>
        </p>
    </section>

    <!-- Footer Section -->
    <footer>
        <small>By Tejas S , Student ID : 231VMTHR1168, (USN) : 231VMTHR1168</small>
    </footer>

    <script>
        // Toggle visibility of sections
        document.getElementById('createButton').addEventListener('click', () => {
            document.getElementById('createSection').classList.toggle('hidden');
            document.getElementById('readSection').classList.add('hidden');
        });

        document.getElementById('readButton').addEventListener('click', () => {
            document.getElementById('readSection').classList.toggle('hidden');
            document.getElementById('createSection').classList.add('hidden');
        });

        // Toggle key visibility
        function toggleKeyVisibility(fieldId) {
            const keyField = document.getElementById(fieldId);
            if (keyField.type === 'password') {
                keyField.type = 'text';
            } else {
                keyField.type = 'password';
            }
        }
        
    </script>

    <style>
        /* Hide sections by default */
        .hidden {
            display: none;
        }

        /* Two-column layout */
        .two-column-container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin-top: 40px;
        }

        .column {
            flex: 1;
            text-align: center;
        }

        .description {
            margin-bottom: 15px;
        }

        .image {
            max-width: 80%;
            height: auto;
            margin-bottom: 15px;
        }

        /* Eye icon styling */
        .eye-icon {
            cursor: pointer;
            margin-left: 5px;
            font-size: 1.2em;
        }

        /* Flex styling for key input wrapper */
        .key-field-wrapper {
            display: flex;
            align-items: center;
        }
    </style>
</body>
</html>
