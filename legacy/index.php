<?php
/**
 * GatePin Legacy Version
 * Created by Toni (Jarus) Maxx
 * Original Creation Date: September 23, 2023
 * 
 * This legacy version features a simple and static PIN-based directory protection system.
 * Unlike the improved version, it lacks dynamic PIN logic and configuration options for base PIN values.
 * Ideal for lightweight, minimal-use cases with basic security needs.
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GatePin: Legacy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #333; color: #fff; text-align: center; padding: 1rem; }
        h1 { margin: 0; font-size: 1.5rem; }
        ul { list-style: none; padding: 0; text-align: center; }
        li { margin: 0.5rem 0; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        .pin-input { text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column; }
        .pin-input input { font-size: 2rem; width: 2rem; margin: 0 0.5rem; padding: 0.5rem; text-align: center; border: 1px solid #ccc; border-radius: 5px; }
        .message { margin-top: 10px; }
    </style>
</head>
<body>
<?php
$correctPin = "2900"; // Static PIN value for the legacy version
$currentFolder = basename(__DIR__);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pin = implode("", $_POST["pin"]);
    if ($pin === $correctPin) {
        echo "<header><h1>" . strtoupper($currentFolder) . "</h1></header><main><ul>";
        foreach (glob('./*.php') as $file) {
            $fileName = basename($file);
            if ($fileName !== 'index.php') echo "<li><a href=\"$fileName\">$fileName</a></li>";
        }
        echo "</ul></main>";
    } else {
        displayForm("Incorrect PIN. Please try again.");
    }
} else {
    displayForm("Enter the correct PIN to access $currentFolder.");
}

function displayForm($message) {
    global $correctPin;
    echo '<div class="pin-input"><form method="post" id="pinForm">';
    for ($i = 1; $i <= strlen($correctPin); $i++) {
        echo "<input type='text' name='pin[]' maxlength='1' required id='pin$i' oninput='moveFocus($i)' " . ($i === 1 ? "autofocus" : "") . ">";
    }
    echo "</form><div class='message'>$message</div></div>";
    echo '<script>
        function moveFocus(i) {
            var curr = document.getElementById("pin" + i), next = document.getElementById("pin" + (i + 1));
            if (i === ' . strlen($correctPin) . ' && curr.value) document.getElementById("pinForm").submit();
            else if (curr.value) next.focus();
        }
    </script>';
}
?>
</body>
</html>