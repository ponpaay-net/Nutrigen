import re
import sys

def get_bounds(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # We want to find the rough min and max X and Y.
    # We can just extract all numbers and look at their ranges?
    # No, numbers in SVG path 'd' are usually x,y pairs or commands.
    # It's hard to distinguish X from Y in a simple regex without a full SVG parser.
    # But wait, we just want the overall min and max of ALL coordinates.
    # The max coordinate will give us the lower bound of max_x and max_y.
    # The min coordinate will give us the upper bound of min_x and min_y.
    
    # Let's extract all paths
    paths = re.findall(r'd="([^"]+)"', content)
    all_numbers = []
    for p in paths:
        # replace commands with spaces
        p_cleaned = re.sub(r'[a-zA-Z]', ' ', p)
        nums = [float(n) for n in re.findall(r'-?\d+\.?\d*', p_cleaned)]
        all_numbers.extend(nums)
        
    if not all_numbers:
        print("No numbers found in paths")
        return
        
    print(f"File: {filepath}")
    print(f"Min number: {min(all_numbers)}")
    print(f"Max number: {max(all_numbers)}")

get_bounds('public/images/universities/usk.svg')
