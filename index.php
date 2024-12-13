<?php
/**
 * GatePin: Simple Directory Protection Script
 *
 * Created with love and caffeine by:
 * Toni (Jarus) Maxx
 * tonimaxx@gmail.com
 * Dec 10, 2024 -- San Ramon, California.
 *
 * This script provides PIN-based protection for directory listing.
 * PIN Logic: Base PIN value + current day of month (PST timezone).
 *
 * Features:
 * - Dynamic PIN based on date (because static is boring)
 * - Brute force protection (hackers hate this one trick!)
 * - File search functionality (find things faster than your desktop search)
 * - Mobile responsive design (for admins on the go, or on the couch)
 *
 * Suggested Configuration:
 * - **basePinValue**: Choose a two-digit value (e.g., 20, 40, 99). This keeps the PIN easy to calculate and is sufficient for accidental access prevention.
 * - Note: This script is designed for simplicity and moderate security, not maximum protection. Use stronger solutions for critical applications.
 *
 * P.S. If this script doesn't make you smile, it at least makes your directories safer. Enjoy!
 */

/************************
 * INITIALIZATION
 ***********************/
ob_start();
session_start();

/************************
 * CONFIGURATION
 ***********************/
// PIN Generation
date_default_timezone_set('America/Los_Angeles'); // Set timezone to PST
$basePinValue = 2900; // Choose a four-digit base PIN value (e.g., 2900, 2000, 4000, 9900).
// The final PIN is dynamically generated as:
// basePinValue + current day of the month (1-31).
// For example, if basePinValue = 2900 and today is the 15th:
// The final PIN will be 2900 + 15 = 2915.
// This ensures a unique 4-digit PIN each day for added security.
$currentDay = (int) date('j'); // Get day of month (1-31)
$correctPin = (string) ($basePinValue + $currentDay); // Calculate PIN: base PIN + day

// UI Simplification Configuration
$showToggleView = false; // Show/Hide Grid/List view toggle button
$showCopyPathButton = false; // Show/Hide Copy Path button

// General Settings
$currentFolder = basename(__DIR__);
$error = null;
$maxAttempts = 5; // Maximum login attempts
$attemptResetTime = 3600; // Reset attempts after 1 hour

// Directory Browsing Settings
$allowDirectoryBrowsing = true; // Enable/disable directory browsing
$allowedFileTypes = ['php']; // File types to display
$previewFileTypes = ['txt', 'md', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'css']; // File types allowed for preview

/************************
 * SESSION MANAGEMENT
 ***********************/
// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Initialize attempt tracking
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

/************************
 * PIN VERIFICATION
 ***********************/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Reset attempts if time expired
    if (time() - $_SESSION['last_attempt'] > $attemptResetTime) {
        $_SESSION['attempts'] = 0;
    }

    // Check attempt limit
    if ($_SESSION['attempts'] >= $maxAttempts) {
        $error = 'Too many attempts. Please try again later.';
    } else {
        // Process login attempt
        $_SESSION['attempts']++;
        $_SESSION['last_attempt'] = time();
        if (implode("", $_POST["pin"]) === $correctPin) {
            $_SESSION['authenticated'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
        $error = 'Incorrect PIN. Please try again.';
    }
}

/************************
 * HTML OUTPUT
 ***********************/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GatePin - <?=htmlspecialchars($currentFolder)?></title>
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.16/marked.min.js"></script>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <style>
        /* Custom Styles */
        .pin-input input {
            width: 3rem !important;
        }
        /* List view styling */
        .list-view .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Grid view styling */
        .grid-view .list-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .grid-view .list-group-item {
            text-align: center;
            display: block;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        #filePreviewContent {
            max-height: 90vh;
            overflow-y: auto;
            text-align: left;
            padding: 15px;
        }

        #filePreviewContent img {
            max-width: 100%; /* Ensure the image fits within the modal */
            max-height: 100%; /* Prevent the image from overflowing vertically */
            margin: 0 auto; /* Center the image */
            display: block;
            border-radius: 10px; /* Add some styling */
        }
            </style>
</head>
<body class="bg-light">
<?php
/************************
 * CONTENT DISPLAY
 ***********************/
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // Display authenticated content
    showAuthenticatedContent($currentFolder, $allowDirectoryBrowsing);
} else {
    // Display login form
    showLoginForm($currentFolder, $error);
}

/**
 * Helper Functions
 */
function showAuthenticatedContent($folderName, $allowDirectoryBrowsing)
{
    // Get and sanitize current directory path
    if (!$allowDirectoryBrowsing && isset($_GET['dir'])) {
        die('Directory browsing is disabled');
    }

    $currentPath = isset($_GET['dir']) ? trim($_GET['dir'], '/') : '';
    $currentPath = str_replace(['..', './'], '', $currentPath); // Remove potential directory traversal
    $fullPath = $currentPath ? "./$currentPath" : '.';

    // Validate directory exists and is within root
    if ($currentPath && (!is_dir($fullPath) || strpos(realpath($fullPath), realpath('.')) !== 0)) {
        die('Invalid directory access');
    }

    // Build breadcrumb paths
    $paths = $currentPath ? explode('/', $currentPath) : [];
    $breadcrumbs = [];
    $cumPath = '';

    // Navigation bar and breadcrumbs
    echo '<nav class="navbar bg-dark mb-0">
            <div class="container-fluid">
                <span class="navbar-brand text-white mb-0 h1">'
    . strtoupper($folderName) .
        '</span>
            </div>
          </nav>';

    // Enhanced Breadcrumb
    echo '<div class="container-fluid bg-light border-bottom">
    <nav class="py-2">
        <button id="toggleView" class="btn btn-sm btn-outline-secondary d-none">
            <i class="bi bi-grid-fill"></i> Switch to Grid View
        </button>
    </nav>
            <nav aria-label="breadcrumb" class="py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="?" class="text-decoration-none">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>';

    // Build breadcrumbs
    if ($currentPath) {
        $parts = explode('/', $currentPath);
        $buildPath = '';
        foreach ($parts as $i => $part) {
            $buildPath .= ($buildPath ? '/' : '') . $part;
            if ($i === count($parts) - 1) {
                // Last item (current directory)
                echo '<li class="breadcrumb-item active" aria-current="page">'
                . htmlspecialchars($part) . '</li>';
            } else {
                // Parent directories
                echo '<li class="breadcrumb-item">
                        <a href="?dir=' . urlencode($buildPath) . '" class="text-decoration-none">'
                . htmlspecialchars($part) . '</a>
                      </li>';
            }
        }
    }

    echo '    </ol>';

    // Add copy path button if in a subdirectory
    if ($currentPath) {
        echo '<button id="copyPathButton" onclick="copyPath(\'' . htmlspecialchars($currentPath) . '\')"
                      class="btn btn-sm btn-outline-secondary position-absolute end-0 me-3 mb-2 d-none">
                <i class="bi bi-clipboard"></i> Copy Path
              </button>';
    }

    echo '  </nav>
          </div>
          <div class="container mt-4">';

    // Search input
    echo '<input type="text" class="form-control mb-4" id="fileSearch"
             onkeyup="filterFiles()" placeholder="Search files...">';

    // Directory listing
    echo '<div class="list-group list-view">';

    // Show "Up" directory link if in subdirectory
    if ($allowDirectoryBrowsing && $currentPath) {
        $parentDir = dirname($currentPath);
        $parentUrl = $parentDir === '.' ? '?' : '?dir=' . urlencode($parentDir);
        echo '<a href="' . $parentUrl . '" class="list-group-item list-group-item-action">
                <i class="bi bi-arrow-up-circle"></i> ../ (Up)
              </a>';
    }

    // List directories first if browsing is allowed
    if ($allowDirectoryBrowsing) {
        foreach (glob("$fullPath/*", GLOB_ONLYDIR) as $dir) {
            $dirName = basename($dir);
            if ($dirName !== '.' && $dirName !== '..') {
                $dirPath = $currentPath ? "$currentPath/$dirName" : $dirName;
                echo '<a href="?dir=' . urlencode($dirPath) . '"
                         class="list-group-item list-group-item-action">
                         📁 ' . htmlspecialchars($dirName) . '/
                      </a>';
            }
        }
    }

    // Then list files (now supporting multiple file types)
    $allFiles = glob("$fullPath/*.*");
    foreach ($allFiles as $file) {
        $fileName = basename($file);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
// Choose icon based on file extension
        $icon = match ($extension) {
            'php' => '🐘',
            'md' => '📝',
            'html', 'htm' => '🌐',
            'css' => '🎨',
            'js' => '📜',
            default => '📄'
        };
        $isMarkdown = $extension === 'md' ? 'true' : 'false';

        if (in_array($extension, $GLOBALS['previewFileTypes'])) {
            $fileUrl = htmlspecialchars($currentPath ? $currentPath . '/' . $fileName : $fileName);
            echo '<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <a href="' . $fileUrl . '" target="_blank">
            ' . $icon . ' ' . htmlspecialchars($fileName) . '
        </a>
        <a href="#" class="text-primary" onclick="previewFile(\'' . $fileUrl . '\', ' . ($extension === 'md' ? 'true' : 'false') . '); return false;">(Preview)</a>
    </div>';
        } else {
            echo '<a href="' . htmlspecialchars($currentPath ? $currentPath . '/' . $fileName : $fileName) . '"
        class="list-group-item list-group-item-action" target="_blank">
        ' . $icon . ' ' . htmlspecialchars($fileName) . '
    </a>';
        }
    }
    echo '</div>';

    // Logout link
    echo '<div class="text-center mt-4">
            <a href="?logout" class="text-muted small">Logout</a>
          </div></div>';
}

function showLoginForm($folderName, $error)
{
    echo '<div class="container">
            <div class="row min-vh-100 align-items-center justify-content-center">
                <div class="col-md-6 text-center">
                    <form method="post" id="pinForm">
                        <div class="d-flex justify-content-center gap-2 pin-input mb-3">';

    // Generate PIN input fields
    for ($i = 1; $i <= 4; $i++) {
        echo '<input type="text" name="pin[]" maxlength="1"
                 class="form-control text-center" required '
            . ($i === 1 ? 'autofocus ' : '')
            . 'id="pin' . $i . '" oninput="moveFocus(' . $i . ')"
                 inputmode="numeric" pattern="[0-9]*">';
    }

    echo '</div>';

    // Error message
    if ($error) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }

    echo '<div class="text-muted">Enter PIN to access ' . htmlspecialchars($folderName) . '</div>
          </form></div></div></div>';
}

?>

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel"></h5> 
                <button type="button" id="copyTextButton" class="btn btn-secondary">Copy Text</button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="filePreviewContent">
                Loading content...
            </div>
            <div class="modal-footer">
                <button type="button" id="copyTextButton" class="btn btn-secondary">Copy Text</button>
            </div>
        </div>
    </div>
</div>

<script>
/* JavaScript Functions
 * - PIN input handling
 * - File search functionality
 */
// Handle PIN input focus
function moveFocus(i) {
    const current = document.getElementById("pin" + i);
    const next = document.getElementById("pin" + (i + 1));
    if (current.value && next) next.focus();
    else if (current.value) document.getElementById("pinForm").submit();
}

// Handle file search/filter
function filterFiles() {
    const search = document.getElementById('fileSearch').value.toLowerCase();
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
}

// Copy path to clipboard
function copyPath(path) {
    navigator.clipboard.writeText(path).then(() => {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

// Declare fileList and toggleButton globally
const fileList = document.querySelector('.list-group');
const toggleButton = document.getElementById('toggleView');

// Function to update the toggle button's text and icon
function updateToggleButton(view) {
    toggleButton.innerHTML = view === 'grid-view'
        ? '<i class="bi bi-list"></i> Switch to List View'
        : '<i class="bi bi-grid-fill"></i> Switch to Grid View';
}

// Restore view mode from localStorage
const savedView = localStorage.getItem('view');
if (savedView) {
    fileList.classList.add(savedView);
    updateToggleButton(savedView);
} else {
    // Default to list view if no view is saved
    fileList.classList.add('list-view');
    localStorage.setItem('view', 'list-view');
    updateToggleButton('list-view');
}

// Toggle view mode and save preference
toggleButton.addEventListener('click', () => {
    if (fileList.classList.contains('list-view')) {
        fileList.classList.remove('list-view');
        fileList.classList.add('grid-view');
        localStorage.setItem('view', 'grid-view');
        updateToggleButton('grid-view');
    } else {
        fileList.classList.remove('grid-view');
        fileList.classList.add('list-view');
        localStorage.setItem('view', 'list-view');
        updateToggleButton('list-view');
    }
});


const previewFile = (filePath, isMarkdown) => {
    const allowedExtensions = <?=json_encode($previewFileTypes)?>;
    const fileExtension = filePath.split('.').pop().toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
        console.error('Preview not supported for this file type.');
        document.getElementById('filePreviewContent').textContent = 'Preview not supported for this file type.';
        return;
    }
    console.log('Fetching file:', filePath, 'Is Markdown:', isMarkdown);
    fetch(filePath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.blob(); // Fetch as a blob for handling images
        })
        .then(blob => {
            const previewContent = document.getElementById('filePreviewContent');
            const fileExtension = filePath.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

            if (imageExtensions.includes(fileExtension)) {
                // Handle image display
                const imageUrl = URL.createObjectURL(blob);
                previewContent.innerHTML = `<img src="${imageUrl}" alt="Preview" class="img-fluid" style="max-width: 100%; border-radius: 10px;" />`;
            } else if (isMarkdown) {
                // Handle Markdown
                blob.text().then(content => {
                    try {
                        previewContent.innerHTML = marked.parse(content);
                    } catch (error) {
                        console.error('Error rendering Markdown:', error);
                        previewContent.textContent = 'Error rendering Markdown.';
                    }
                });
            } else {
                // Handle other text content
                blob.text().then(content => {
                    previewContent.textContent = content;
                });
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'), {
                backdrop: true  // true = With backdrop + Allow closing modal by clicking outside
                                // 'static' for allow close button only
            });
            modal.show();
        })
        .catch(error => {
            console.error('Error fetching file:', error);
            document.getElementById('filePreviewContent').textContent = `Error loading file: ${error.message}`;
        });
}

document.addEventListener('DOMContentLoaded', () => {
    // Copy Text Button Logic
    const copyTextButton = document.getElementById('copyTextButton');
    const filePreviewContent = document.getElementById('filePreviewContent');
    
    copyTextButton.addEventListener('click', () => {
        const contentToCopy = filePreviewContent.innerText || filePreviewContent.textContent;
        
        navigator.clipboard.writeText(contentToCopy)
            .then(() => {
                copyTextButton.textContent = 'Copied!';
                setTimeout(() => {
                    copyTextButton.textContent = 'Copy Text';
                }, 2000);
            })
            .catch(err => {
                console.error('Error copying text:', err);
                copyTextButton.textContent = 'Failed to Copy';
            });
    });
});


document.addEventListener('DOMContentLoaded', () => {
    // Retrieve configuration values from PHP
    const showToggleView = <?=json_encode($showToggleView)?>;
    const showCopyPathButton = <?=json_encode($showCopyPathButton)?>;

    // Conditionally enable buttons based on configuration
    if (showToggleView) {
        document.getElementById('toggleView').classList.remove('d-none');
    }

    if (showCopyPathButton) {
        const copyPathButton = document.getElementById('copyPathButton');
        copyPathButton.classList.remove('d-none');
        copyPathButton.setAttribute('onclick', `copyPath('${htmlspecialchars($currentPath)}')`);
    }
});

</script>
</body>
</html>
<?php
/************************
 * CLEANUP
 ***********************/
ob_end_flush();
?>