import fs from 'fs';
import { svgPathBbox } from 'svg-path-bbox';

function getRealBounds(file) {
    const content = fs.readFileSync(file, 'utf8');
    const pathRegex = /d="([^"]+)"/g;
    let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
    
    let match;
    while ((match = pathRegex.exec(content)) !== null) {
        const d = match[1];
        try {
            const bounds = svgPathBbox(d);
            // bounds is [minX, minY, maxX, maxY]
            if (bounds[0] < minX) minX = bounds[0];
            if (bounds[1] < minY) minY = bounds[1];
            if (bounds[2] > maxX) maxX = bounds[2];
            if (bounds[3] > maxY) maxY = bounds[3];
        } catch(e) {}
    }
    console.log(`${file} Real Bounds: minX=${minX}, minY=${minY}, maxX=${maxX}, maxY=${maxY}`);
}

getRealBounds('public/images/universities/usk.svg');
getRealBounds('public/images/universities/uin-arraniry.svg');
