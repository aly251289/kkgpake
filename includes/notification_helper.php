<?php
// includes/notification_helper.php

// Load Waha Helper
require_once dirname(__FILE__) . '/whatsapp_helper.php';

/**
 * Send Notification to Admin 
 */
function notifyAdmin($message)
{
    global $koneksi;

    // Get Admin WA
    $q = mysqli_query($koneksi, "SELECT admin_wa FROM identitas WHERE id=1");
    if ($q && mysqli_num_rows($q) > 0) {
        $d = mysqli_fetch_assoc($q);
        if (!empty($d['admin_wa'])) {
            return WhatsAppHelper::sendText($d['admin_wa'], $message);
        }
    }

    return ['status' => false, 'message' => 'Admin WA not set'];
}

/**
 * Legacy Support for PushWa (Optional, kept for backward compatibility if needed locally)
 */
function sendPushWa($target, $message)
{
    // Redirect to Waha
    return WhatsAppHelper::sendText($target, $message);
}
?>