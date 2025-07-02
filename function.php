<?php

/**
 * Embed a message in a file based on file type.
 * Supports image formats like JPEG, PNG, GIF, BMP, and WebP.
 */
function embedMessage($filePath, $message, $key) {
    $mimeType = mime_content_type($filePath);

    // Check if the file is an image and handle accordingly
    if (strpos($mimeType, 'jpeg') !== false || strpos($mimeType, 'jpg') !== false) {
        return embedInImage($filePath, $message, $key);  // Handle JPEG
    } elseif (strpos($mimeType, 'png') !== false) {
        return embedInImage($filePath, $message, $key);  // Handle PNG
    } elseif (strpos($mimeType, 'gif') !== false) {
        return embedInImage($filePath, $message, $key);  // Handle GIF
    } elseif (strpos($mimeType, 'bmp') !== false) {
        return embedInImage($filePath, $message, $key);  // Handle BMP
    } elseif (strpos($mimeType, 'webp') !== false) {
        return embedInImage($filePath, $message, $key);  // Handle WebP
    } else {
        throw new Exception('Unsupported file type. Only JPEG, PNG, GIF, BMP, and WebP are supported for embedding.');  // Unsupported file type
    }
}

/**
 * Extract a message from a file based on file type.
 * Supports image formats like JPEG, PNG, GIF, BMP, and WebP.
 */
function extractMessage($filePath, $key) {
    $mimeType = mime_content_type($filePath);

    // Check if the file is an image and handle accordingly
    if (strpos($mimeType, 'jpeg') !== false || strpos($mimeType, 'jpg') !== false) {
        return extractFromImage($filePath, $key);  // Handle JPEG
    } elseif (strpos($mimeType, 'png') !== false) {
        return extractFromImage($filePath, $key);  // Handle PNG
    } elseif (strpos($mimeType, 'gif') !== false) {
        return extractFromImage($filePath, $key);  // Handle GIF
    } elseif (strpos($mimeType, 'bmp') !== false) {
        return extractFromImage($filePath, $key);  // Handle BMP
    } elseif (strpos($mimeType, 'webp') !== false) {
        return extractFromImage($filePath, $key);  // Handle WebP
    } else {
        throw new Exception('Unsupported file type. Only JPEG, PNG, GIF, BMP, and WebP are supported for extraction.');  // Unsupported file type
    }
}

/**
 * Encrypt a message using AES-256-CBC.
 */
function encryptMessage($message, $key) {
    $method = 'aes-256-cbc';
    $key = hash('sha256', $key, true);  // Create a 256-bit key
    $iv = openssl_random_pseudo_bytes(16);  // Generate a random 128-bit IV

    // Encrypt the message
    $encryptedMessage = openssl_encrypt($message, $method, $key, OPENSSL_RAW_DATA, $iv);
    
    if (!$encryptedMessage) {
        throw new Exception("Encryption failed.");
    }

    // Return the base64-encoded message with IV
    return base64_encode($iv . $encryptedMessage);  // Prepend IV to the encrypted message
}

/**
 * Validate and decode a message with a key.
 */
function validateMessage($message, $key) {
    // Decode the base64-encoded message
    $data = base64_decode($message);
    if ($data === false) {
        throw new Exception("Base64 decoding failed.");
    }

    // Extract the IV and ciphertext from the decoded data
    $iv = substr($data, 0, 16);  // The first 16 bytes are the IV
    $ciphertext = substr($data, 16);  // The rest is the ciphertext

    // Generate the decryption key from the provided key
    $derivedKey = generateKey($key);

    // Decrypt the ciphertext using AES-256-CBC
    $decryptedMessage = openssl_decrypt($ciphertext, 'aes-256-cbc', $derivedKey, OPENSSL_RAW_DATA, $iv);

    if ($decryptedMessage === false) {
        throw new Exception("Invalid key or corrupted message.");
    }

    return $decryptedMessage;  // Return the decrypted message
}

/**
 * Embed a message into an image using Least Significant Bit (LSB) steganography.
 */
function embedInImage($filePath, $message, $key) {
    $mimeType = mime_content_type($filePath);
    $image = null;

    // Handle different image formats
    if (strpos($mimeType, 'jpeg') !== false || strpos($mimeType, 'jpg') !== false) {
        $image = imagecreatefromjpeg($filePath);  // Handle JPEG
    } elseif (strpos($mimeType, 'png') !== false) {
        $image = imagecreatefrompng($filePath);  // Handle PNG
    } elseif (strpos($mimeType, 'gif') !== false) {
        $image = imagecreatefromgif($filePath);  // Handle GIF
    } elseif (strpos($mimeType, 'bmp') !== false) {
        $image = imagecreatefrombmp($filePath);  // Handle BMP
    } elseif (strpos($mimeType, 'webp') !== false) {
        $image = imagecreatefromwebp($filePath);  // Handle WebP
    } else {
        throw new Exception('Unsupported file type. Only JPEG, PNG, GIF, BMP, and WebP are supported for embedding.');  // Unsupported file type
    }

    if (!$image) {
        throw new Exception('Failed to load image.');
    }

    // Encrypt and embed the message
    $encryptedMessage = encryptMessage($message, $key);
    $binaryMessage = toBinary($encryptedMessage) . str_repeat('0', 8);  // Add terminator (8 zeros)

    $messageLength = strlen($binaryMessage);
    $width = imagesx($image);  // Get image width
    $height = imagesy($image);  // Get image height

    $index = 0;
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($index >= $messageLength) break 2;  // Exit loop once message is embedded

            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $b = ($b & ~1) | $binaryMessage[$index++];  // Embed bit into the blue channel
            $color = imagecolorallocate($image, $r, $g, $b);  // Recreate the color with modified blue
            imagesetpixel($image, $x, $y, $color);  // Set the pixel color
        }
    }

    $outputPath = $filePath . '_steg.png';  // Save with _steg suffix
    imagepng($image, $outputPath);  // Save the modified image as PNG
    imagedestroy($image);  // Free memory

    return $outputPath;  // Return the path to the modified image
}

/**
 * Extract a message from an image.
 */
function extractFromImage($filePath, $key) {
    $mimeType = mime_content_type($filePath);
    $image = null;

    // Handle different image formats
    if (strpos($mimeType, 'jpeg') !== false || strpos($mimeType, 'jpg') !== false) {
        $image = imagecreatefromjpeg($filePath);  // Handle JPEG
    } elseif (strpos($mimeType, 'png') !== false) {
        $image = imagecreatefrompng($filePath);  // Handle PNG
    } elseif (strpos($mimeType, 'gif') !== false) {
        $image = imagecreatefromgif($filePath);  // Handle GIF
    } elseif (strpos($mimeType, 'bmp') !== false) {
        $image = imagecreatefrombmp($filePath);  // Handle BMP
    } elseif (strpos($mimeType, 'webp') !== false) {
        $image = imagecreatefromwebp($filePath);  // Handle WebP
    } else {
        throw new Exception('Unsupported file type. Only JPEG, PNG, GIF, BMP, and WebP are supported for extraction.');  // Unsupported file type
    }

    if (!$image) {
        throw new Exception('Failed to load image.');
    }

    $width = imagesx($image);  // Get image width
    $height = imagesy($image);  // Get image height

    $binaryMessage = '';
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $b = $rgb & 0xFF;
            $binaryMessage .= ($b & 1);  // Get least significant bit (LSB) of the blue channel
        }
    }

    imagedestroy($image);  // Free memory

    $decodedMessage = fromBinary($binaryMessage);  // Convert binary to message
    return validateMessage($decodedMessage, $key);  // Validate and decrypt the message
}

/**
 * Convert a string to binary.
 */
function toBinary($data) {
    $binary = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $binary .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);  // Convert each character to 8-bit binary
    }
    return $binary;
}

/**
 * Convert binary to a string.
 */
function fromBinary($binary) {
    $data = '';
    for ($i = 0; $i < strlen($binary); $i += 8) {
        $char = chr(bindec(substr($binary, $i, 8)));  // Convert each 8-bit chunk back to a character
        if ($char === "\0") break;  // Stop at the terminator
        $data .= $char;
    }
    return $data;
}

/**
 * Generate a cryptographic key from a user-provided key.
 */
function generateKey($key) {
    return hash('sha256', $key, true);  // Derive a 256-bit key using SHA-256
}

/**
 * Decrypt an encrypted message using AES-256-CBC.
 */
function decryptMessage($encryptedMessage, $key) {
    // Decode the base64-encoded encrypted message
    $data = base64_decode($encryptedMessage);
    if ($data === false) {
        throw new Exception("Base64 decoding failed.");
    }

    // Extract the IV and ciphertext from the decoded data
    $iv = substr($data, 0, 16);  // The first 16 bytes are the IV
    $ciphertext = substr($data, 16);  // The rest is the ciphertext

    // Generate the decryption key from the provided key
    $derivedKey = generateKey($key);

    // Decrypt the ciphertext using AES-256-CBC
    $decryptedMessage = openssl_decrypt($ciphertext, 'aes-256-cbc', $derivedKey, OPENSSL_RAW_DATA, $iv);

    if ($decryptedMessage === false) {
        throw new Exception("Decryption failed. Invalid key or corrupted message.");
    }

    return $decryptedMessage;
}

?>
