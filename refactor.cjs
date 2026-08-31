const fs = require('fs');

function processFile(filePath) {
    let content = fs.readFileSync(filePath, 'utf8');

    // Remove Alpine reveal stuff
    content = content.replace(/x-data="\{ shown: false \}"/g, '');
    content = content.replace(/x-intersect\.once="shown = true"/g, '');
    content = content.replace(/:class="shown \? 'is-visible' : ''"/g, '');

    // Replace reveal-element with data-aos
    content = content.replace(/class="([^"]*)reveal-element([^"]*)"/g, 'class="$1$2" data-aos="fade-up"');

    // Clean up spaces in class
    content = content.replace(/class="\s+/g, 'class="');
    content = content.replace(/\s+"/g, '"');
    content = content.replace(/class="([^"]*)\s\s+([^"]*)"/g, 'class="$1 $2"');

    // Replace inline transition delay with data-aos-delay
    content = content.replace(/style="transition-delay:\s*(\d+)ms;?"/g, 'data-aos-delay="$1"');
    content = content.replace(/style="animation-delay:\s*(\d+)ms;?"/g, 'data-aos-delay="$1"');

    // Update colors to the new theme
    content = content.replace(/emerald-500/g, 'nutrigen-green');
    content = content.replace(/emerald-600/g, 'nutrigen-green-dark');
    content = content.replace(/emerald-400/g, 'nutrigen-green-light');
    content = content.replace(/emerald-/g, 'nutrigen-green-');

    content = content.replace(/teal-500/g, 'nutrigen-cyan');
    content = content.replace(/teal-600/g, 'nutrigen-cyan-dark');
    content = content.replace(/teal-400/g, 'nutrigen-cyan-light');
    content = content.replace(/teal-/g, 'nutrigen-cyan-');

    // Add hover scale & shadow to general cards
    content = content.replace(/class="([^"]*bg-white[^"]*rounded-[^"]*p-[^"]*border[^"]*)"/g, function(match, classes) {
        if (!classes.includes('hover:scale-') && !classes.includes('hover:-translate-y-1')) {
            return 'class="' + classes + ' hover:-translate-y-1 hover:shadow-xl hover:shadow-nutrigen-green-100 transition-all duration-300"';
        }
        return match;
    });

    // Update final CTA button to use the new colors (Welcome page specifically)
    content = content.replace(/bg-gradient-to-b from-slate-800 to-slate-950/g, 'bg-gradient-to-r from-nutrigen-green-dark to-nutrigen-cyan-dark');
    content = content.replace(/hover:from-slate-700 hover:to-slate-900/g, 'hover:from-nutrigen-green hover:to-nutrigen-cyan');
    content = content.replace(/focus:ring-slate-900\/20/g, 'focus:ring-nutrigen-green/30');

    // Additional cleanup
    content = content.replace(/animate-fade-in-up/g, 'data-aos="fade-up"');

    fs.writeFileSync(filePath, content);
    console.log('Updated', filePath);
}

processFile('c:/Projects/nutrigen/resources/views/welcome.blade.php');
processFile('c:/Projects/nutrigen/resources/views/team.blade.php');
