<?php
/**
 * HelloToni: Dynamic Content Display
 * 
 * Created with enthusiasm by:
 * Toni (Jarus) Maxx
 * tonimaxx@gmail.com
 * Dec 10, 2024 — San Ramon, California.
 * 
 * This script demonstrates dynamic content generation with PHP.
 * It includes server details, PST time, and other fun dynamic features.
 */

// Set timezone to PST
date_default_timezone_set('America/Los_Angeles');

// Dynamic Data
$currentTime = date('Y-m-d H:i:s'); // Current PST time
$serverIP = $_SERVER['SERVER_ADDR']; // Server IP address
$clientIP = $_SERVER['REMOTE_ADDR']; // Client IP address
$userAgent = $_SERVER['HTTP_USER_AGENT']; // Client User Agent

// Fun Facts about Moo Deng
$funFacts = [
    "Moo Deng's name was chosen by over 20,000 people in a public poll!",
    "Moo Deng made her SNL debut with Bowen Yang playing her on Weekend Update.",
    "Moo Deng has her own theme song in four languages: Thai, English, Chinese, and Japanese!",
    "Moo Deng correctly predicted Trump's election victory by choosing fruit with his name.", 
    "Moo Deng's popularity doubled her zoo's daily visitor count in September 2024!",
    "Moo Deng has two full siblings named Nadet and Moo Tun.",
    "The zoo had to implement a 5-minute visitor time limit due to Moo Deng's popularity!",
    "Moo Deng's name means 'bouncy pork' or 'bouncy pig' in Thai.",
    "Security cameras were installed around Moo Deng's enclosure to protect her from overenthusiastic fans.",
    "Moo Deng's zoo is working on trademarking her name and launching a livestream channel.",
    "Moo Deng was born on July 10, 2024, at Khao Kheow Open Zoo in Thailand.",
    "Moo Deng's parents are named Tony (father) and Jonah (mother).",
    "Moo Deng has four half-siblings: Ko, Kanya, Phalo, and Moo Wan.",
    "Moo Deng went viral at just two months of age in September 2024.",
    "Moo Deng inspired a whole line of merchandise including clothing designs.",
    "Moo Deng has become a popular subject for fan artists.",
    "The Thai Society for the Prevention of Cruelty to Animals confirms Moo Deng is well cared for.",
    "Moo Deng's species is the Pygmy hippopotamus (Choeropsis liberiensis).",
    "Moo Deng lives in Si Racha, Chonburi, Thailand.",
    "Moo Deng's popularity led to her becoming an internet meme.",
    "Moo Deng's viral fame began with Facebook posts from the zoo.",
    "Moo Deng is known for being more playful and energetic than other hippos.",
    "Moo Deng appeared at Book Expo Thailand 2024 as a cardboard cutout.",
    "Moo Deng's SNL appearance was used to satirize pop-artist Chappell Roan.",
    "Moo Deng's theme song was composed by Mueanphet Ammara.",
    "Moo Deng shares viral animal fame with Hua Hua the panda and Pesto the penguin.",
    "Moo Deng's zoo director is named Narongwit Chodchoi.",
    "Moo Deng's home zoo cares for more than 2,000 animals.",
    "Moo Deng's story has been covered by major news outlets like BBC and The New York Times.",
    "Moo Deng is known for occasionally biting her zookeepers!"
];

// Select a fun fact based on the current 10-second interval
$index = (int)floor((time() % 60) / 10) % count($funFacts);
$funFact = $funFacts[$index];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelloToni - Dynamic Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👋 HelloToni</h1>
            <p>Welcome to a dynamic world, powered by PHP!</p>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                Current Server Details
            </div>
            <div class="card-body">
                <p><strong>Server IP Address:</strong> <?= htmlspecialchars($serverIP) ?></p>
                <p><strong>Client IP Address:</strong> <?= htmlspecialchars($clientIP) ?></p>
                <p><strong>Current PST Time:</strong> <?= htmlspecialchars($currentTime) ?></p>
                <p><strong>User Agent:</strong> <?= htmlspecialchars($userAgent) ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-success text-white">
                Fun Fact About Moo Deng
            </div>
            <div class="card-body">
                <p id="funFact"><?= htmlspecialchars($funFact) ?></p>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <script>
        // Automatically refresh the fun fact every 10 seconds
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
</body>
</html>