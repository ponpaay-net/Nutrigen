const fs = require('fs');
const path = require('path');

const files = [
    'resources/views/welcome.blade.php',
    'resources/views/team.blade.php',
    'resources/views/layouts/guest.blade.php',
    'resources/views/auth/login.blade.php'
];

const replacements = [
    { regex: /(?<!dark:)bg-white/g, replacement: 'bg-white dark:bg-slate-900' },
    { regex: /(?<!dark:)bg-slate-50/g, replacement: 'bg-slate-50 dark:bg-slate-950' },
    { regex: /(?<!dark:)text-slate-900/g, replacement: 'text-slate-900 dark:text-slate-50' },
    { regex: /(?<!dark:)text-slate-800/g, replacement: 'text-slate-800 dark:text-slate-100' },
    { regex: /(?<!dark:)text-slate-700/g, replacement: 'text-slate-700 dark:text-slate-200' },
    { regex: /(?<!dark:)text-slate-600/g, replacement: 'text-slate-600 dark:text-slate-300' },
    { regex: /(?<!dark:)text-slate-500/g, replacement: 'text-slate-500 dark:text-slate-400' },
    { regex: /(?<!dark:)border-slate-200/g, replacement: 'border-slate-200 dark:border-white/10' },
    { regex: /(?<!dark:)border-slate-300/g, replacement: 'border-slate-300 dark:border-white/20' },
    { regex: /(?<!dark:)hover:bg-slate-50/g, replacement: 'hover:bg-slate-50 dark:hover:bg-slate-800' },
    { regex: /(?<!dark:)hover:bg-slate-100/g, replacement: 'hover:bg-slate-100 dark:hover:bg-slate-800' }
];

files.forEach(file => {
    const fullPath = path.join(__dirname, file);
    if (!fs.existsSync(fullPath)) return;
    
    let content = fs.readFileSync(fullPath, 'utf8');
    
    replacements.forEach(({ regex, replacement }) => {
        content = content.replace(regex, replacement);
    });

    // Clean up duplicates
    content = content.replace(/dark:bg-slate-900 dark:bg-slate-900/g, 'dark:bg-slate-900');
    content = content.replace(/dark:bg-slate-950 dark:bg-slate-950/g, 'dark:bg-slate-950');
    content = content.replace(/dark:text-slate-50 dark:text-slate-50/g, 'dark:text-slate-50');
    content = content.replace(/dark:text-slate-400 dark:text-slate-400/g, 'dark:text-slate-400');
    content = content.replace(/dark:border-white\/10 dark:border-white\/10/g, 'dark:border-white/10');

    fs.writeFileSync(fullPath, content, 'utf8');
    console.log(`Processed ${file}`);
});
