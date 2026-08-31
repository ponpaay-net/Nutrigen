import xml.etree.ElementTree as ET
import re
import sys
import glob

def parse_svg_bounds(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception as e:
        print(f"Error reading {filepath}: {e}")
        return

    # Find viewBox
    vb_match = re.search(r'viewBox="([^"]+)"', content)
    if vb_match:
        print(f"[{filepath}] Original viewBox: {vb_match.group(1)}")
    else:
        print(f"[{filepath}] No viewBox found.")
        w_match = re.search(r'width="([^"]+)"', content)
        h_match = re.search(r'height="([^"]+)"', content)
        if w_match and h_match:
            print(f"[{filepath}] Has width={w_match.group(1)} height={h_match.group(1)}")

    # Basic bounding box logic for typical paths
    # Note: SVGs can have transforms, which makes raw d="..." parsing inaccurate.
    # We will try to parse <path d="..."> and check transforms.
    
    # Actually, the quickest way to fix padding is just manually setting viewBox 
    # to crop out empty space. I will just output the min/max of raw path data to get a rough idea.
    
    # regex to match any number
    numbers = re.findall(r'-?\d+\.?\d*', content)
    
    # To be safer, let's just use regular expressions to find all X,Y pairs from d attributes if possible
    paths = re.findall(r'd="([^"]+)"', content)
    all_x = []
    all_y = []
    for p in paths:
        # A very crude approach: assume numbers come in pairs or are interspersed with commands
        # Just grab all numbers from path
        nums = [float(n) for n in re.findall(r'-?\d+\.?\d*', p)]
        if not nums: continue
        # This won't work well because some commands only have 1 number (H, V) or are relative.
        # But for absolute paths (C, M, Z) with no relative commands, it gives a rough idea.

print("Use a browser to see visual padding or just inspect the viewBox visually.")
