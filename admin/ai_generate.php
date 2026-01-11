<?php
/**
 * AI Article Generator Endpoint - Clean Version
 * Model: gemini-2.0-flash (v1beta)
 */

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../config/ai_config.php';
header('Content-Type: application/json; charset=utf-8');

// 1. Get Input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input)
    $input = $_POST;

$type = trim($input['type'] ?? 'kegiatan');
$nama_kegiatan = trim($input['nama_kegiatan'] ?? ''); // Maps to "Topik/Judul" for Berita
$tanggal = trim($input['tanggal'] ?? '');
$tempat = trim($input['tempat'] ?? '');
$peserta = trim($input['peserta'] ?? '');
$deskripsi = trim($input['deskripsi'] ?? ''); // Maps to "Context/Facts" for Berita
$gaya = trim($input['gaya'] ?? 'formal');
$panjang = trim($input['panjang'] ?? 'sedang');

if (empty($nama_kegiatan) || empty($deskripsi)) {
    echo json_encode(['error' => 'Data tidak lengkap']);
    exit;
}

// 2. Prepare Prompt
$style_instruction = "Formal dan profesional.";
if ($gaya == 'santai')
    $style_instruction = "Santai dan komunikatif.";
if ($gaya == 'inspiratif')
    $style_instruction = "Menginspirasi dan memotivasi.";
if ($gaya == 'edukatif')
    $style_instruction = "Edukatif dan informatif.";

$length_instruction = "300-500 kata.";
if ($panjang == 'panjang')
    $length_instruction = "800+ kata (detail dan mendalam).";
if ($panjang == 'pendek')
    $length_instruction = "200-300 kata (singkat dan padat).";

if ($type == 'berita') {
    // PROMPT KHUSUS BERITA
    $prompt = "Kamu adalah jurnalis berita profesional. Tulis artikel berita terkini berdasarkan data berikut:
    
TOPIK/JUDUL: {$nama_kegiatan}
FAKTA UTAMA/KONTEKS: {$deskripsi}
TEMPAT: {$tempat}
TANGGAL: {$tanggal}

Instruksi:
1. Gaya bahasa: {$style_instruction}
2. Panjang: {$length_instruction}
3. Struktur berita standar (Lead, Body, Closing).
4. Tentukan KATEGORI dari: Politik, Ekonomi, Sosial, Pendidikan, Teknologi, Olahraga, Hiburan.
5. Buat 3-5 TAGS relevan.
6. Buat PROMPT GAMBAR (image_prompt) dalam Bahasa Inggris yang relevan dengan berita.
7. Output WAJIB JSON:
{\"judul\": \"Judul Headline Menarik\", \"kategori\": \"Pendidikan\", \"tags\": \"tag1, tag2\", \"image_prompt\": \"A professional photo of...\", \"isi\": \"<p>Lead parags...</p>\"}";

} else {
    // PROMPT KHUSUS KEGIATAN (DEFAULT)
    $prompt = "Kamu adalah jurnalis sekolah profesional. Tulis laporan kegiatan sekolah berdasarkan data berikut:

KEGIATAN: {$nama_kegiatan}
TANGGAL: {$tanggal}
TEMPAT: {$tempat}
PESERTA: {$peserta}
DESKRIPSI/HASIL: {$deskripsi}

Instruksi:
1. Gaya bahasa: {$style_instruction}
2. Panjang: {$length_instruction}
3. Fokus pada jalannya kegiatan dan hasil yang dicapai.
4. Tentukan KATEGORI dari: Berita, Pengumuman, Artikel, Prestasi.
5. Buat 3-5 TAGS relevan.
6. Buat PROMPT GAMBAR (image_prompt) dalam Bahasa Inggris yang mendeskripsikan suasana kegiatan.
7. Output WAJIB JSON:
{\"judul\": \"Judul Artikel Kegiatan\", \"kategori\": \"Berita\", \"tags\": \"tag1, tag2\", \"image_prompt\": \"A professional photo of...\", \"isi\": \"<p>Paragraf 1...</p>\"}";
}

// 3. Prepare API Request (Sesuai Curl User)
$model = defined('GEMINI_MODEL') ? GEMINI_MODEL : 'gemini-1.5-flash';
$url = "https://generativelanguage.googleapis.com/v1beta/models/" . $model . ":generateContent?key=" . GEMINI_API_KEY;

$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => $prompt
                ]
            ]
        ]
    ]
];

// 4. Send Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix XAMPP SSL issue
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch);
$curl_error = curl_error($ch);
curl_close($ch);

// 5. Allow manual debugging via file
file_put_contents('debug_gemini.txt', "Response: " . $response . "\nError: " . $curl_error);

if ($curl_error) {
    echo json_encode(['error' => 'Connection Error: ' . $curl_error]);
    exit;
}

// 6. Parse Response
$json = json_decode($response, true);

if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
    $raw_text = $json['candidates'][0]['content']['parts'][0]['text'];

    // Clean markdown code blocks
    $clean_text = preg_replace('/```json|```/', '', $raw_text);
    $article = json_decode(trim($clean_text), true);

    if (isset($article['judul']) && isset($article['isi'])) {
        echo json_encode([
            'success' => true,
            'judul' => $article['judul'],
            'kategori' => $article['kategori'] ?? 'Berita',
            'tags' => $article['tags'] ?? '',
            'image_prompt' => $article['image_prompt'] ?? '',
            'isi' => $article['isi']
        ]);
    } else {
        // Fallback if JSON parsing fails but text exists
        echo json_encode([
            'success' => true,
            'judul' => $nama_kegiatan,
            'isi' => nl2br($clean_text) // Return raw text as body
        ]);
    }
} else {
    $err_msg = $json['error']['message'] ?? 'Unknown Error';
    echo json_encode(['error' => 'API Error: ' . $err_msg]);
}
