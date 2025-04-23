# WordPress-Theme-Plugin-Checker
A lightweight PHP tool by Muzamil Attiq (Senior Full Stack Developer) to check active WordPress themes and plugins across multiple websites. Enter URLs, and get a detailed report of detected themes and plugins, along with usage stats across all scanned sites.

Developed by **Muzamil Attiq – Senior Full Stack Developer**

## Overview

This is a lightweight and efficient PHP-based tool that allows users to check the active WordPress theme and plugins across multiple websites in one go. It fetches site HTML and intelligently parses it to extract the required details, displaying everything in a neat, responsive web interface.

---

##  Features

- Check active WordPress theme from any given site
- Detect all visible plugins used on the frontend
- Scan multiple websites at once by submitting a list of URLs
- Display usage counts for themes and plugins across all scanned websites
- Responsive UI with real-time processing feedback

---

##  Live Demo (Optional)

You can deploy this script on any PHP-supported server or localhost to use it interactively via a web browser.

---

##  How to Use

1. Clone or download the repository.
2. Host it on a PHP-enabled server or localhost environment (like XAMPP, LAMP, etc.).
3. Open the script in your browser.
4. Enter the list of WordPress site URLs (one per line).
5. Click "Check" and wait for the results.
6. View the active theme and plugins along with their usage statistics.

---

##  Project Structure

- `index.php` – Main script file containing both backend and frontend logic
- No dependencies required – fully standalone

---

##  Notes

- This tool works by parsing the publicly available HTML of a website.  
  It cannot detect plugins that do not expose themselves via frontend assets (e.g., admin-only or backend plugins).
- Error handling is included for invalid or unreachable URLs.

---

## Author

**Muzamil Attiq**  
Senior Full Stack Developer  
https://pk.linkedin.com/in/muzamilattiq-website-developer-seo

---

##  License

This project is open-source and free to use. Feel free to contribute or adapt it as per your needs.
