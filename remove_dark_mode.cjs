const fs = require('fs');
const path = require('path');

const files = [
    'resources/views/welcome.blade.php',
    'resources/views/team.blade.php',
    'resources/views/layouts/guest.blade.php',
    'resources/views/auth/login.blade.php'
];

files.forEach(file => {
    const fullPath = path.join(__dirname, file);
    if (!fs.existsSync(fullPath)) return;
    
    let content = fs.readFileSync(fullPath, 'utf8');
    
    // Remove all classes starting with dark:
    content = content.replace(/\s+dark:[a-zA-Z0-9\-\/\[\]\.]+/g, '');
    
    // Specifically remove the script added in head
    content = content.replace(/<!-- Adaptive Theme Script -->\s*<script>\s*if \(localStorage\.theme === 'dark' \|\| \(!\('theme' in localStorage\) && window\.matchMedia\('\(prefers-color-scheme: dark\)'\)\.matches\)\) \{\s*document\.documentElement\.classList\.add\('dark'\);\s*\} else \{\s*document\.documentElement\.classList\.remove\('dark'\);\s*\}\s*<\/script>\s*/g, '');

    // Specifically remove the theme toggle dropdown in the navbar
    const toggleRegex = /<!-- Theme Toggle -->\s*<div class="relative ml-2" x-data="\{ open: false, theme: localStorage\.theme \|\| 'system' \}">[\s\S]*?<\/div>\s*<\/div>\s*@auth/g;
    content = content.replace(toggleRegex, '@auth');
    
    fs.writeFileSync(fullPath, content, 'utf8');
    console.log(`Processed ${file}`);
});
