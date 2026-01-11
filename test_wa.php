<?php
require_once 'config/koneksi.php';
require_once 'includes/whatsapp_helper.php';

// Check if form submitted
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Test the helper
    $result = WhatsAppHelper::sendText($phone, $message);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Test WhatsApp</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .result {
            background: #f0f0f0;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Test WhatsApp Integration</h1>
    <p>Using API:
        <?php echo 'https://waha.mirafa01.web.id'; ?>
    </p>

    <form method="POST">
        <label>Phone Number (e.g. 628123456789):</label><br>
        <input type="text" name="phone" value="62" required><br><br>

        <label>Message:</label><br>
        <textarea name="message" required>Hello from Waha!</textarea><br><br>

        <button type="submit">Send Message</button>
    </form>

    <?php if ($result): ?>
        <div class="result">
            <strong>Result:</strong>
            <pre><?php print_r($result); ?></pre>
        </div>
    <?php endif; ?>
</body>

</html>