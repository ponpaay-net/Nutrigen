import re

with open('resources/views/puskesmas/balita.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove the detail overlay and drawer
content = re.sub(r'\{\{--\s*DETAIL MODAL.*?</div>\s*</div>\s*</div>', '', content, flags=re.DOTALL)

# Remove the detail drawer JS
content = re.sub(r'// ── DETAIL DRAWER LOGIC.*?} // END DETAIL DRAWER LOGIC', '', content, flags=re.DOTALL)
content = re.sub(r'// ── DETAIL DRAWER LOGIC.*?function closeDetail.*?}\s*overlay\.addEventListener\(\'click\', closeDetail\);', '', content, flags=re.DOTALL)


# Write back
with open('resources/views/puskesmas/balita.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done stripping drawer.')
