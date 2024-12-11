# 🔐 GatePin: Legacy Version

## 📝 Overview

**GatePin Legacy** is a simple, lightweight PHP-based directory protection script designed for minimal, static PIN-based access control.

### 🌟 Key Features
- **Static PIN Authentication**: Uses a fixed PIN for directory access
- **Minimal Configuration**: Simple, straightforward implementation
- **Lightweight Design**: Ideal for basic security needs

## 🛠 Technical Details

### PHP Configuration
```php
$correctPin = "2900"; // Static PIN value for the legacy version
```

### Authentication Mechanism
- 4-digit PIN input
- Character-by-character input validation
- Automatic form submission on complete PIN entry

### Supported Functionalities
- Directory listing
- PHP file access control
- Basic error messaging

## 🚧 Limitations
- **No Dynamic PIN**: Fixed PIN value
- **Limited Customization**: Minimal configuration options
- **Basic Security**: Not recommended for sensitive directories

## 💡 Usage Instructions

### Installation
1. Place the `index.php` in your target directory
2. Set the `$correctPin` to your desired 4-digit PIN
3. Ensure PHP is configured on your server

### Authentication Flow
1. Enter the 4-digit PIN
2. Automatically move between PIN input fields
3. Submit form when all 4 digits are entered
4. Access directory contents if PIN is correct

## 🔍 Code Breakdown

### PIN Input Mechanism
```php
// Automatic focus and submission logic
function moveFocus(i) {
    var curr = document.getElementById("pin" + i);
    var next = document.getElementById("pin" + (i + 1));
    
    // Auto-submit when last digit is entered
    if (last digit && complete) {
        submit form
    }
    // Move to next input automatically
    else if (current input is filled) {
        focus next input
    }
}
```

### Security Considerations
- **⚠️ Warning**: Not suitable for protecting highly sensitive information
- Recommended for:
  - Personal projects
  - Development environments
  - Non-critical directory access

## 📋 Changelog
- **v1.0** (September 23, 2023)
  - Initial legacy version release
  - Static PIN implementation
  - Basic directory listing

## 🤝 Contributing
Suggestions and improvements are welcome! 

## 📧 Contact
**Toni (Jarus) Maxx**
- Email: tonimaxx@gmail.com
- Website: [toimaxx.com](https://toimaxx.com)

## 📜 License
Open-source. Use at your own discretion.

*Disclaimer: This is a legacy version with limited security features.*