<?php
/**
 * WhatsApp Helper using Waha (WhatsApp HTTP API)
 */

class WhatsAppHelper
{
    private static $apiUrl = 'https://waha.mirafa01.web.id'; // Default
    private static $apiKey = ''; // API Key
    private static $defaultSession = 'default';

    /**
     * Set the API URL dynamically
     */
    public static function setApiUrl($url)
    {
        self::$apiUrl = rtrim($url, '/');
    }

    /**
     * Send a text message
     * 
     * @param string $to Phone number (e.g. 628123456789)
     * @param string $message Message content
     * @return array|bool Response from API or false on failure
     */
    public static function sendText($to, $message)
    {
        // Try to get URL and Key from DB if defaults are used
        if (self::$apiUrl == 'https://waha.mirafa01.web.id' || self::$apiUrl == 'http://localhost:3000') {
            global $koneksi;
            if (isset($koneksi)) {
                $q = mysqli_query($koneksi, "SELECT waha_api_url, waha_api_key FROM identitas LIMIT 1");
                if ($q && mysqli_num_rows($q) > 0) {
                    $d = mysqli_fetch_assoc($q);
                    if (!empty($d['waha_api_url'])) {
                        self::$apiUrl = rtrim($d['waha_api_url'], '/');
                    }
                    if (!empty($d['waha_api_key'])) {
                        self::$apiKey = $d['waha_api_key'];
                    }
                }
            }
        }

        $url = self::$apiUrl . '/api/sendText';

        // Format phone number: ensure it ends with @c.us if not present, 
        // but Waha usually takes just the number. 
        // Best practice for Waha: just digits. 
        // If it starts with 0, replace with 62.
        $to = self::formatPhoneNumber($to);

        $data = [
            'chatId' => $to . '@c.us',
            'text' => $message,
            'session' => self::$defaultSession
        ];

        return self::makeRequest($url, $data);
    }

    private static function formatPhoneNumber($number)
    {
        // Remove non-digits
        $number = preg_replace('/[^0-9]/', '', $number);

        // Replace leading 0 with 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }

    private static function makeRequest($url, $data)
    {
        $ch = curl_init($url);

        $payload = json_encode($data);

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        // Add API Key if set
        if (!empty(self::$apiKey)) {
            $headers[] = 'X-Api-Key: ' . self::$apiKey;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($result, true);
        } else {
            // Log error or handle it
            error_log("WhatsApp API Error ($httpCode): " . $result);
            return false;
        }
    }
}
