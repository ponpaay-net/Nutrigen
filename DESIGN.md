# NutriGen — Design Tokens (Portal Kader, mobile-first)

> Sumber keputusan visual. Semua nilai di sini adalah standar; jangan pakai hex/ukuran
> di luar daftar di bawah kecuali sangat penting. *Kalau tidak ada alasan, pakai token.*

## Prinsip: "Clean ≠ colorless"
- Basanya netral yang tenang, TAPI data & aksi diberi warna yang hidup (tinted surface + aksen).
- Teal adalah jangkar brand. Area berwarna pekat < ~10–15% layar. 80% netral, sisanya aksen.
- Mobile-first: kader pakai HP. Body ≥14px, label ≥12px, target sentuh ≥44px (min-h-[44px]).

## Palette (Tailwind teal-based, kohesif)
| Token | Value | Dipakai untuk |
|---|---|---|
| Brand primary | `teal-600 #0d9488` | tombol utama, link aktif, ikon aktif |
| Brand deep | `teal-700 #0f766e` / `teal-800 #115e59` | hover gradient, teks penting |
| Brand tint | `teal-50 #f0fdfa` / `teal-100 #ccfbf1` | surface kartu tinted, chip, badge |
| Netral bg | `slate-50 #f8fafc` | latar halaman |
| Netral text | `slate-900 #0f172a` / `slate-600 #475569` / `slate-400 #94a3b8` | hierarki teks |
| Netral border | `slate-200 #e2e8f0` | border kartu (lebih tegas dari sebelumnya) |
| Success | `emerald-600 #059669` + tint `emerald-50 #ecfdf5` | selesai / sehat / track progres |
| Warning | `amber-600 #d97706` + tint `amber-50 #fffbeb` | antrean / perlu tindakan |
| Danger | `rose-600 #e11d48` + tint `rose-50 #fff1f2` | perlu pantauan / risiko |
| Info | `sky-600 #0284c7` + tint `sky-50 #f0f9ff` | jadwal/agenda (netral-aksen) |

*Hue status disinkronkan agar harmonis dengan teal (semua dingin, jenuh terukur).*

## Type scale (bersih, bukan numpuk)
- Display/H1: `text-2xl sm:text-3xl font-semibold` (greeting) — bukan black.
- H2 (section): `text-lg font-semibold` (18).
- Angka KPI: `text-2xl sm:text-3xl font-bold tabular-nums`.
- Body: `text-sm sm:text-base` (14–16) `font-medium`.
- Label/utility: `text-xs font-medium uppercase tracking-wide` (12, minimum).
- Baca: body ids → `leading-relaxed`, `text-slate-600`.

## Spacing (8px grid)
`4 / 8 / 12 / 16 / 20 / 24 / 32 / 40 / 48 / 64` — jangan pakai nilai arbitrary (76/100/120).

## Radius & shadow
- Kartu: `rounded-2xl` (16). Kartu hero: `rounded-3xl` (24). Chip/pill: `rounded-full`.
- Shadow berlapis lembut: `shadow-sm` default, hero `shadow-lg shadow-teal-600/20`,
  kartu penting `shadow-md shadow-slate-900/5`. Border `border border-slate-200`.

## Icon
- Gunakan `x-icon` (Phosphor, sudah dimuat di head). Pilih yang pas konteks.
  Ganti ikon "heart" untuk pemantauan gizi → ikon pertumbuhan (bila ada), bukan hati.

## Audience
Kader posyandu (umum, beberapa senior), mobile-first, alur cepat ("operate").
Prioritas: skannabilitas, konsistensi, touch target besar, kontras cukup (WCAG AA).
