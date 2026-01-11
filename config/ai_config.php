<?php
/**
 * AI Configuration File
 * Konfigurasi untuk AI API
 * 
 * PILIH SALAH SATU PROVIDER:
 * 
 * 1. GROQ (Recommended - Free, Fast, Generous Limits)
 *    - Gratis: ~14,400 requests/hari
 *    - Dapatkan API key di: https://console.groq.com/keys
 * 
 * 2. GEMINI (Google)
 *    - Dapatkan API key di: https://aistudio.google.com/apikey
 */

// ========== PILIH PROVIDER ==========
// Pilihan: 'groq' atau 'gemini'
define('AI_PROVIDER', 'gemini');

// ========== GROQ SETTINGS (Recommended) ==========
// Dapatkan API key GRATIS di: https://console.groq.com/keys
define('GROQ_API_KEY', 'AIzaSyBc_GoKoTecwj2K3Y0bMdp-oLrfM6XWbRA');
define('GROQ_MODEL', 'llama-3.1-8b-instant'); // Fast & free

// ========== GEMINI SETTINGS ==========
define('GEMINI_API_KEY', 'AIzaSyBc_GoKoTecwj2K3Y0bMdp-oLrfM6XWbRA');
define('GEMINI_MODEL', 'gemini-2.5-flash');
