const fs = require('fs');

const uin = fs.readFileSync('public/images/universities/uin-arraniry.svg', 'utf8');
const usk = fs.readFileSync('public/images/universities/usk.svg', 'utf8');

function printBounds(name, content) {
    const paths = content.match(/d="[^"]+"/g);
    if (!paths) return;
    let min = Infinity;
    let max = -Infinity;
    paths.forEach(p => {
        const nums = p.match(/-?\d+\.?\d*/g);
        if (nums) {
            nums.forEach(n => {
                const val = parseFloat(n);
                if (val < min) min = val;
                if (val > max) max = val;
            });
        }
    });
    console.log(`${name} min: ${min}, max: ${max}`);
}

printBounds('UIN', uin);
printBounds('USK', usk);
