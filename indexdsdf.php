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
        <div class="ribbon">StegaCipher</div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Embed Message Section -->
        <section class="section">
            <h2>Create A StegaImage</h2>
            <form action="embed.php" method="POST" enctype="multipart/form-data" class="form">
                <label for="file">Choose Image File</label>
                <input type="file" name="file" required>
                <small>Supports Images Only (E.g JPEG, PNG, BMP)</small>

                <label for="message">Message</label>
                <textarea name="message" required placeholder="Enter your secret message here..."></textarea>

                <div class="key-input">
                    <label for="key">Key</label>
                    <input type="password" name="key" id="keyField" required placeholder="Enter a secret key">
                    <button type="button" class="toggle-key" onclick="toggleKeyVisibility()">Show/Hide Key</button>
                </div>

                <button type="submit" class="button">Embed Message</button>
            </form>
        </section>

        <!-- Extract Message Section -->
        <section class="section">
            <h2>Read A StegaImage</h2>
            <form action="extract.php" method="POST" enctype="multipart/form-data" class="form">
                <label for="file">Choose Image File</label>
                <input type="file" name="file" required>

                <div class="key-input">
                    <label for="key">Key</label>
                    <input type="password" name="key" id="keyField" required placeholder="Enter the secret key">
                    <button type="button" class="toggle-key" onclick="toggleKeyVisibility()">Show/Hide Key</button>
                </div>

                <button type="submit" class="button">Extract Message</button>
            </form>
        </section>
    </div>

    <!-- Footer Section -->
    <footer>
        <small>by Tejas S</small>
    </footer>

    <script>
        function toggleKeyVisibility() {
            const keyField = document.getElementById('keyField');
            if (keyField.type === 'password') {
                keyField.type = 'text';
            } else {
                keyField.type = 'password';
            }
        }
    </script>
</body>
</html>
