import fs from 'fs';
import getBounds from 'svg-path-bounds';

// A simple script to approximate bounds of SVG paths.
// We'll read the path d attributes.

const usk = fs.readFileSync('public/images/universities/usk.svg', 'utf8');

const pathRegex = /d="([^"]+)"/g;
let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;

let match;
while ((match = pathRegex.exec(usk)) !== null) {
    const d = match[1];
    try {
        const bounds = getBounds(d);
        // bounds returns [left, top, right, bottom]
        if (bounds[0] < minX) minX = bounds[0];
        if (bounds[1] < minY) minY = bounds[1];
        if (bounds[2] > maxX) maxX = bounds[2];
        if (bounds[3] > maxY) maxY = bounds[3];
    } catch(e) {
        console.error("Error parsing path bounds");
    }
}

console.log(`USK Path Bounds: minX=${minX}, minY=${minY}, maxX=${maxX}, maxY=${maxY}`);
