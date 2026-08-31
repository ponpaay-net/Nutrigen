import fs from 'fs';
import getBounds from 'svg-path-bounds';

const uin = fs.readFileSync('public/images/universities/uin-arraniry.svg', 'utf8');

// The second path is the white part. Let's find its bounds
const paths = [];
const pathRegex = /d="([^"]+)"/g;
let match;
while ((match = pathRegex.exec(uin)) !== null) {
    paths.push(match[1]);
}

if (paths.length > 1) {
    let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
    // skip the first path which is background
    for (let i = 1; i < paths.length; i++) {
        try {
            const bounds = getBounds(paths[i]);
            if (bounds[0] < minX) minX = bounds[0];
            if (bounds[1] < minY) minY = bounds[1];
            if (bounds[2] > maxX) maxX = bounds[2];
            if (bounds[3] > maxY) maxY = bounds[3];
        } catch(e) {}
    }
    console.log(`UIN Inner Logo Bounds: minX=${minX}, minY=${minY}, maxX=${maxX}, maxY=${maxY}`);
}
