<?php
require 'config/koneksi.php';

$slug = 'mi-raden-fatah-01';
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE slug_sekolah='$slug'");
$school = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Image Test</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #f5f5f5;
        }

        .test-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .test-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .img-test {
            border: 2px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            background: white;
        }

        .img-test img {
            display: block;
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
        }

        .path {
            background: #f8f9fa;
            padding: 8px;
            border-left: 3px solid #007bff;
            font-family: monospace;
            font-size: 12px;
            margin: 5px 0;
        }

        .success {
            border-left-color: green;
            background: #d4edda;
        }

        .error {
            border-left-color: red;
            background: #f8d7da;
        }
    </style>
</head>

<body>
    <h1>🔍 Image Rendering Test</h1>

    <div class="test-box">
        <h3>1. Logo Test</h3>
        <p><strong>DB Value:</strong> <code><?= $school['logo_sekolah'] ?></code></p>

        <div class="img-test">
            <p><strong>Method A:</strong> Absolute path with leading slash</p>
            <div class="path">&lt;img src="/
                <?= $school['logo_sekolah'] ?>"&gt;
            </div>
            <img src="/<?= $school['logo_sekolah'] ?>" alt="Logo Method A"
                onerror="this.parentElement.classList.add('error'); this.nextElementSibling.style.display='block'">
            <p style="display:none; color:red;">❌ Failed to load</p>
        </div>

        <div class="img-test">
            <p><strong>Method B:</strong> Relative path (no leading slash)</p>
            <div class="path">&lt;img src="
                <?= $school['logo_sekolah'] ?>"&gt;
            </div>
            <img src="<?= $school['logo_sekolah'] ?>" alt="Logo Method B"
                onerror="this.parentElement.classList.add('error'); this.nextElementSibling.style.display='block'">
            <p style="display:none; color:red;">❌ Failed to load</p>
        </div>

        <div class="img-test">
            <p><strong>Method C:</strong> Direct hardcoded path test</p>
            <div class="path">&lt;img src="/assets/img/sekolah/logo_2_1767788540.jpg"&gt;</div>
            <img src="/assets/img/sekolah/logo_2_1767788540.jpg" alt="Logo Hardcoded"
                onerror="this.parentElement.classList.add('error'); this.nextElementSibling.style.display='block'">
            <p style="display:none; color:red;">❌ Failed to load</p>
        </div>
    </div>

    <div class="test-box">
        <h3>2. Hero Image Test</h3>
        <p><strong>DB Value:</strong> <code><?= $school['hero_sekolah'] ?></code></p>

        <div class="img-test">
            <p><strong>Absolute path</strong></p>
            <div class="path">&lt;img src="/
                <?= $school['hero_sekolah'] ?>"&gt;
            </div>
            <img src="/<?= $school['hero_sekolah'] ?>" alt="Hero" style="max-width: 400px;"
                onerror="this.parentElement.classList.add('error'); this.nextElementSibling.style.display='block'">
            <p style="display:none; color:red;">❌ Failed to load</p>
        </div>
    </div>

    <div class="test-box">
        <h3>3. Headmaster Photo Test</h3>
        <p><strong>DB Value:</strong> <code><?= $school['foto_kepala_sekolah'] ?></code></p>

        <div class="img-test">
            <p><strong>Absolute path</strong></p>
            <div class="path">&lt;img src="/
                <?= $school['foto_kepala_sekolah'] ?>"&gt;
            </div>
            <img src="/<?= $school['foto_kepala_sekolah'] ?>" alt="Kepala"
                onerror="this.parentElement.classList.add('error'); this.nextElementSibling.style.display='block'">
            <p style="display:none; color:red;">❌ Failed to load</p>
        </div>
    </div>

    <div class="test-box">
        <h3>Browser Info:</h3>
        <p><strong>Current URL:</strong>
            <?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>
        </p>
        <p><strong>Document Root:</strong>
            <?= $_SERVER['DOCUMENT_ROOT'] ?>
        </p>
        <p><strong>Script Path:</strong>
            <?= __FILE__ ?>
        </p>
    </div>

    <script>
        // Check which images loaded successfully
        window.addEventListener('load', function () {
            const imgs = document.querySelectorAll('img');
            imgs.forEach(img => {
                if (img.complete && img.naturalHeight !== 0) {
                    img.parentElement.classList.add('success');
                }
            });
        });
    </script>
</body>

</html>