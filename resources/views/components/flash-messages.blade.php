@if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session()->has('success'))
            if(window.NutriAlert) window.NutriAlert.toast("{{ session('success') }}", 'success', 'Berhasil');
        @endif
        
        @if(session()->has('error'))
            if(window.NutriAlert) window.NutriAlert.toast("{{ session('error') }}", 'error', 'Terjadi Kesalahan');
        @endif
        
        @if(session()->has('warning'))
            if(window.NutriAlert) window.NutriAlert.toast("{{ session('warning') }}", 'warning', 'Perhatian');
        @endif
        
        @if(session()->has('info'))
            if(window.NutriAlert) window.NutriAlert.toast("{{ session('info') }}", 'info', 'Informasi');
        @endif
    });
</script>
@endif
