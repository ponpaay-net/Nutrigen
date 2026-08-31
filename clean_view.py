import re

with open('resources/views/puskesmas/balita-show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Layout
content = content.replace("@extends('layouts.app')", "@extends('layouts.puskesmas')")
content = content.replace("route('balita.index')", "route('puskesmas.balita')")

# 2. Remove Top Header Actions (Edit & Delete)
content = re.sub(r'<div class="flex items-center gap-2 sm:gap-4">.*?</div>', '', content, flags=re.DOTALL)

# 3. Remove Action Button (Ukur Sekarang) on Profile Card
content = re.sub(r'<!-- Action Button -->.*?</div>\s*</div>\s*<!-- Integrated Growth Status', '</div>\n\n            <!-- Integrated Growth Status', content, flags=re.DOTALL)

# 4. Remove Ukur button in the Ringkasan Tab
content = re.sub(r'<button onclick="openMeasurementModal\(\)".*?</button>', '', content, flags=re.DOTALL)

# 5. Remove @push('modals') and everything after
content = re.sub(r'@push\(\'modals\'\).*', '', content, flags=re.DOTALL)

# 6. Remove <x-measurement-modal> just in case
content = re.sub(r'<x-measurement-modal.*?</x-measurement-modal>', '', content, flags=re.DOTALL)

# 7. Add @endsection at the end since we stripped push modals which was before endsection
content = content.strip() + "\n\n@endsection\n"

with open('resources/views/puskesmas/balita-show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done cleaning.')
