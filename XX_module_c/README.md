# WorldSkills 2024 - Module C: Lyon Heritage Sites

## Architecture
This project is built using **Vanilla Web Technologies** and **Pure PHP**. 
It utilizes a Front Controller pattern (`index.php`) to handle all routing without a database.

## System Requirements
- Web Server (Apache/Nginx)
- PHP 7.4 or higher
- Mod Rewrite enabled (for .htaccess routing)

## Execution Guide
1. Place the project folder into your web server's document root (e.g., `htdocs` for XAMPP).
2. Ensure the URL path maps correctly to the routing structure.
3. Access the site via: `http://localhost/XX_module_c/`
4. The system dynamically reads files from `Module C Media Files/content-pages/`.

## Features Included
- ✅ Recursive File Scanning & Parsing
- ✅ Front-matter Extraction
- ✅ Tag Filtering & Multi-keyword Search (OR logic)
- ✅ CSS Spotlight Cover Effect & Image Zooming
- ✅ Full Accessibility & Open Graph Tags