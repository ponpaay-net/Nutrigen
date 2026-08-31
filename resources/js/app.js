import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import { animate, stagger, inView, hover } from 'framer-motion/dom';

window.Motion = { animate, stagger, inView, hover };
window.Alpine = Alpine;
Alpine.start();

/**
 * ======================================================
 * NUTRIGEN GLOBAL ALERT & TOAST SYSTEM (NutriAlert)
 * ======================================================
 * Unified, startup-grade notification & modal system.
 * Standardized across all roles (Kader, Puskesmas, Ibu).
 */

const baseSwal = Swal.mixin({
    customClass: {
        popup: '!rounded-[24px] !shadow-[0_20px_50px_rgba(0,0,0,0.14)] !border !border-slate-100 !p-6 sm:!p-7 !max-w-md',
        title: '!text-[18px] !font-bold !text-slate-800 !tracking-tight !mt-2',
        htmlContainer: '!text-[14px] !font-medium !text-slate-500 !mt-2 !mb-6 !leading-relaxed',
        actions: '!gap-3 !mt-4 !w-full !flex !justify-end !items-center',
        confirmButton: '!px-5 !py-2.5 !text-[13px] !font-semibold !text-white !bg-teal-600 hover:!bg-teal-700 !rounded-xl !transition-all !shadow-sm focus:!ring-4 focus:!ring-teal-100',
        cancelButton: '!px-5 !py-2.5 !text-[13px] !font-semibold !text-slate-600 hover:!text-slate-800 !bg-slate-100 hover:!bg-slate-200 !rounded-xl !transition-all !border-0 focus:!ring-4 focus:!ring-slate-100 !mr-2'
    },
    buttonsStyling: false
});

const toastSwal = Swal.mixin({
    toast: true,
    position: window.innerWidth >= 768 ? 'bottom-end' : 'bottom',
    showConfirmButton: false,
    showCloseButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

window.NutriAlert = {
    /**
     * Show a standardized, startup-grade Toast Notification
     * @param {string} message - Message text
     * @param {string} type - 'success' | 'error' | 'warning' | 'info'
     * @param {string} title - Optional title
     */
    toast(message, type = 'success', title = null) {
        const typeTitles = {
            success: 'Berhasil',
            error: 'Terjadi Kesalahan',
            warning: 'Perhatian',
            info: 'Informasi'
        };

        const icons = {
            success: `<div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
            </div>`,
            error: `<div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
            </div>`,
            warning: `<div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
            </div>`,
            info: `<div class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" /></svg>
            </div>`
        };

        const displayTitle = title || typeTitles[type] || 'Notifikasi';
        const iconHtml = icons[type] || icons.info;
        const progressColor = type === 'error' ? '!bg-rose-500' : (type === 'warning' ? '!bg-amber-500' : '!bg-emerald-500');

        toastSwal.fire({
            html: `
                <div class="flex items-center gap-3 w-full text-left">
                    ${iconHtml}
                    <div class="flex flex-col flex-1 min-w-0 pr-2">
                        <span class="text-[13px] font-extrabold text-slate-800 tracking-tight leading-tight">${displayTitle}</span>
                        <span class="text-[12px] font-medium text-slate-500 mt-0.5 leading-snug truncate">${message}</span>
                    </div>
                    <button type="button" onclick="Swal.close()" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                    </button>
                </div>
            `,
            customClass: {
                popup: '!rounded-2xl !shadow-[0_12px_36px_rgba(0,0,0,0.12)] !border !border-slate-200/90 !bg-white/98 !backdrop-blur-md !p-3 !w-auto !min-w-[290px] !max-w-[420px]',
                htmlContainer: '!m-0 !p-0 !w-full',
                timerProgressBar: `${progressColor} !h-[2.5px] !rounded-b-2xl`
            }
        });
    },

    /**
     * Show a Success Alert Modal
     */
    success(title, text) {
        return baseSwal.fire({
            icon: 'success',
            title: title,
            text: text,
            confirmButtonText: 'Tutup'
        });
    },

    /**
     * Show an Error Alert Modal
     */
    error(title, text) {
        return baseSwal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonText: 'Tutup',
            customClass: {
                popup: '!rounded-[24px] !shadow-[0_20px_50px_rgba(0,0,0,0.14)] !border !border-slate-100 !p-6 sm:!p-7 !max-w-md',
                title: '!text-[18px] !font-bold !text-slate-800 !tracking-tight !mt-2',
                htmlContainer: '!text-[14px] !font-medium !text-slate-500 !mt-2 !mb-6 !leading-relaxed',
                actions: '!gap-3 !mt-4 !w-full !flex !justify-end !items-center',
                confirmButton: '!px-5 !py-2.5 !text-[13px] !font-semibold !text-white !bg-rose-600 hover:!bg-rose-700 !rounded-xl !transition-all !shadow-sm focus:!ring-4 focus:!ring-rose-100'
            }
        });
    },
    
    /**
     * Show a Warning Alert Modal
     */
    warning(title, text) {
        return baseSwal.fire({
            icon: 'warning',
            title: title,
            text: text,
            confirmButtonText: 'Mengerti',
            customClass: {
                popup: '!rounded-[24px] !shadow-[0_20px_50px_rgba(0,0,0,0.14)] !border !border-slate-100 !p-6 sm:!p-7 !max-w-md',
                title: '!text-[18px] !font-bold !text-slate-800 !tracking-tight !mt-2',
                htmlContainer: '!text-[14px] !font-medium !text-slate-500 !mt-2 !mb-6 !leading-relaxed',
                actions: '!gap-3 !mt-4 !w-full !flex !justify-end !items-center',
                confirmButton: '!px-5 !py-2.5 !text-[13px] !font-semibold !text-white !bg-amber-600 hover:!bg-amber-700 !rounded-xl !transition-all !shadow-sm focus:!ring-4 focus:!ring-amber-100'
            }
        });
    },

    /**
     * Show a Confirmation Modal for Destructive/Important Actions
     */
    confirm(title, text, confirmText = 'Ya, Lanjutkan', cancelText = 'Batal') {
        return baseSwal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
            customClass: {
                popup: '!rounded-[24px] !shadow-[0_20px_50px_rgba(0,0,0,0.14)] !border !border-slate-100 !p-6 sm:!p-7 !max-w-md',
                title: '!text-[18px] !font-bold !text-slate-800 !tracking-tight !mt-2',
                htmlContainer: '!text-[14px] !font-medium !text-slate-500 !mt-2 !mb-6 !leading-relaxed',
                actions: '!gap-3 !mt-4 !w-full !flex !justify-end !items-center',
                cancelButton: '!px-5 !py-2.5 !text-[13px] !font-semibold !text-slate-600 hover:!text-slate-800 !bg-slate-100 hover:!bg-slate-200 !rounded-xl !transition-all !border-0 focus:!ring-4 focus:!ring-slate-100 !mr-2',
                confirmButton: '!px-5 !py-2.5 !text-[13px] !font-semibold !text-white !bg-rose-600 hover:!bg-rose-700 !rounded-xl !transition-all !shadow-sm focus:!ring-4 focus:!ring-rose-100'
            }
        });
    },
    
    /**
     * Show a generic action confirm Modal (non-destructive)
     */
    action(title, text, confirmText = 'Konfirmasi', cancelText = 'Batal') {
        return baseSwal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
        });
    }
};

/**
 * ======================================================
 * GLOBAL SMART DECIMAL INPUT FORMATTER (Antropometri Balita)
 * ======================================================
 * Otomatis menambahkan titik desimal dan mengganti koma (,) -> (.)
 * Contoh input user:
 *   - '687' pada Tinggi Badan -> otomatis menjadi '68.7'
 *   - '790' pada Berat Badan  -> otomatis menjadi '7.90'
 *   - '79' pada Berat Badan   -> otomatis menjadi '7.9'
 *   - '425' pada Lingkar Kepala -> otomatis menjadi '42.5'
 *   - '325' pada Berat Lahir  -> otomatis menjadi '3.25'
 *   - '495' pada Panjang Lahir -> otomatis menjadi '49.5'
 */
window.formatSmartDecimal = function(value, fieldType = 'generic') {
    if (!value) return value;
    let str = String(value).trim().replace(/,/g, '.');
    
    // Jika sudah ada titik desimal, pertahankan apa adanya
    if (str.includes('.')) return str;

    const num = parseFloat(str);
    if (isNaN(num)) return str;

    // 4 digit tanpa titik (misal 4520 -> 45.20, 4501 -> 45.01, 1055 -> 10.55)
    if (str.length === 4) {
        return (num / 100).toFixed(2);
    }
    // 3 digit tanpa titik (misal 687 -> 68.7, 495 -> 49.5, 325 -> 32.5)
    else if (str.length === 3) {
        return (num / 10).toFixed(1);
    }

    return str;
};

window.initSmartDecimalInputs = function() {
    const selectors = [
        'input[name="berat_badan"]',
        'input[name="tinggi_badan"]',
        'input[name="lingkar_kepala"]',
        'input[name="berat_lahir"]',
        'input[name="panjang_lahir"]',
        'input[name="lingkar_kepala_lahir"]',
        'input#berat',
        'input#tinggi',
        'input#lingkar',
        'input#berat_lahir',
        'input#panjang_lahir',
        'input#lingkar_kepala_lahir',
        '[data-smart-decimal]'
    ];

    document.querySelectorAll(selectors.join(',')).forEach(input => {
        if (input.dataset.smartDecimalInit) return;
        input.dataset.smartDecimalInit = 'true';

        // Ganti koma ke titik saat mengetik
        input.addEventListener('input', () => {
            if (input.value.includes(',')) {
                input.value = input.value.replace(/,/g, '.');
            }
        });

        // Format otomatis saat selesai mengetik / keluar dari field (blur)
        input.addEventListener('blur', () => {
            const fieldName = (input.name || input.id || '').toLowerCase();
            const formatted = window.formatSmartDecimal(input.value, fieldName);
            if (formatted !== input.value) {
                input.value = formatted;
                // Trigger event input & change jika ada validator lain
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    window.initSmartDecimalInputs();
});

