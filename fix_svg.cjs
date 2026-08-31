const fs = require('fs');
const file = 'public/images/universities/uin-arraniry.svg';
let content = fs.readFileSync(file, 'utf8');
const lines = content.split('\n');

// Line 2 is 0-indexed as lines[2], but it might be different if \r\n
let greenPathIndex = lines.findIndex(l => l.includes('fill="#007E00"'));
if(greenPathIndex !== -1) {
    lines.splice(greenPathIndex, 1);
}

let whitePathIndex = lines.findIndex(l => l.includes('fill="#FEFEFE"'));
if(whitePathIndex !== -1) {
    let line = lines[whitePathIndex];
    line = line.replace('M0 0 C179.52 0 359.04 0 544 0 C544 185.46 544 370.92 544 562 C364.48 562 184.96 562 0 562 C0 376.54 0 191.08 0 0 Z ', '');
    line = line.replace('fill="#FEFEFE"', 'fill="#007E00"');
    lines[whitePathIndex] = line;
}

fs.writeFileSync(file, lines.join('\n'));
console.log('Fixed UIN SVG');
