# GatePin: Simple Directory Protection Script

**Created with love and caffeine by [Toni (Jarus) Maxx](mailto:tonimaxx@gmail.com)** 
**Dec 10, 2024 — San Ramon, California**

![GatePin](https://miro.medium.com/v2/resize:fit:2000/format:webp/1*CrPOU_C70V6-WLljRkaS8A.png)

## About GatePin

GatePin is a lightweight, user-friendly script for adding PIN-based protection to your directories. Designed with simplicity and moderate security in mind, GatePin dynamically generates a PIN based on a configurable base value and the current day of the month (PST timezone). It's perfect for protecting non-critical directories from accidental access.

### Features
- **Dynamic PIN Generation**: Base PIN value + current day of the month.
- **Brute Force Protection**: Lockout after multiple failed attempts.
- **File Search**: Quickly locate files within the directory.
- **Mobile Responsive Design**: Access directories on any device.
- **Customizable UI**: Toggle Grid/List views or show/hide buttons like "Copy Path."

### Use Cases
- Prevent accidental access to sensitive directories.
- Add an extra layer of protection for non-critical web applications.
- Provide controlled directory access for small projects.

---

## Quick Start

### 1. Installation
1. Download the `index.php` file.
2. Place it in the directory you want to protect.
3. Ensure the directory allows PHP execution.

### 2. Configuration
Open the `index.php` file and update the configuration section:
```php
// PIN Configuration
$basePinValue = 2900; // Choose a four-digit base PIN value (e.g., 2900, 2000, 4000, 9900).
date_default_timezone_set('America/Los_Angeles'); // Set timezone to PST.

// UI Configuration
$showToggleView = false; // Show/Hide Grid/List view toggle button.
$showCopyPathButton = false; // Show/Hide "Copy Path" button.

// Security Settings
$maxAttempts = 5; // Maximum login attempts before lockout.
$attemptResetTime = 3600; // Lockout duration in seconds.
```

Example Usage
```
Scenario: Protecting a Directory

1. Place index.php in your secure_folder/ directory.
2. Set $basePinValue = 50 in the configuration.
3. Access secure_folder/ in a browser.
4. The PIN for today (e.g., Dec 10) is: 50 + 10 = 60.
```

Features in Detail
**Dynamic PIN Calculation**
- The PIN is calculated as: `PIN = basePinValue + currentDay`
- Example: If `basePinValue = 40` and today is the 15th, the PIN is 40 + 15 = 55.

**Brute Force Protection**
- Users are locked out after `$maxAttempts` consecutive failed attempts.
- Lockout resets after `$attemptResetTime` seconds.

**File Search and Viewing**
- Search for files within the directory using the search bar.
- Preview `.txt` and `.md` files directly in the browser.
- Download or open other files as usual.

**Mobile Responsive Design**
- Optimized for desktops, tablets, and mobile devices.
- Easily switch between Grid and List views (configurable).

Customization Guide
1. **Changing the Base PIN Value**:
   Modify `$basePinValue` in the configuration:
   ```php
   $basePinValue = 30; // New base PIN value.
   ```

2. **Enabling/Disabling UI Buttons**:
   Toggle the following options to show/hide UI elements:
   ```php
   $showToggleView = true; // Enables Grid/List view button.
   $showCopyPathButton = true; // Enables "Copy Path" button.
   ```

3. **Adjusting Security Settings**:
   Modify these values for custom lockout rules:
   ```php
   $maxAttempts = 3; // Allow 3 attempts before lockout.
   $attemptResetTime = 1800; // Lockout duration of 30 minutes.
   ```

Example Directory Structure
```
/secure_folder
    ├── index.php (GatePin Script)
    ├── file1.txt
    ├── file2.md
    ├── subdirectory/
```

Known Limitations
- **Security**: Not suitable for critical or highly sensitive data.
- **Compatibility**: Requires PHP 7.4+ and a server that supports PHP.

Contributing
Pull requests are welcome! For significant changes, please open an issue to discuss what you'd like to improve.

License
This project is licensed under the MIT License.

Contact
Questions? Suggestions? Drop me a line:
Toni (Jarus) Maxx — [x.com/tonimaxx](https://x.com/tonimaxx) | [facebook.com/tonimaxx](https://facebook.com/tonimaxx) | [medium.com/@tonimaxx](https://medium.com/@tonimaxx) | [tonimaxx.com](https://tonimaxx.com) | [tonimaxx@gmail.com](mailto:tonimaxx@gmail.com)

P.S. While I've provided many ways to reach me, email is where I actually check for messages the most. Happy coding!  

  
![](https://miro.medium.com/v2/resize:fit:1400/format:webp/1*-TwOrWWIeAkaMjwn_o8NEQ.png)
![](https://miro.medium.com/v2/resize:fit:1400/format:webp/1*WY8ICWIehJ7YUmHHHAYpew.png)
