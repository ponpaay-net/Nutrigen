## Context

The current `navigation.blade.php` has an empty search bar and hides the user profile link inside a mobile hamburger menu, omitting it on desktop screens entirely. `profil-kader.blade.php` and `edit-profil-kader.blade.php` use a legacy, plain design without depth or cohesive branding.

## Goals / Non-Goals

**Goals:**
- Provide a consistent Profile dropdown on Desktop (avatar, username, settings links, logout).
- Ensure Mobile hamburger menu has parity with Desktop dropdown links.
- Remove non-functional search UI.
- Redesign Profil and Edit Profil pages with `ui-ux-pro-max` (glassmorphism, subtle shadows, `slate-50` background, Teal accents).
- Execute the work in 3 distinct reviewable phases to prevent breaking navigation logic.

**Non-Goals:**
- Backend controller logic changes (e.g., authentication, saving profile data).
- Modifying other pages (Dashboard, Balita, Laporan) which are already refined.

## Decisions

- **Desktop Dropdown Avatar**: We will implement a standard Tailwind CSS absolute dropdown anchored to a relative wrapper around the user's avatar. Alpine.js is preferred if available in the layout, otherwise we will use standard CSS hover/focus group or simple vanilla JS toggle, based on existing standard in `navigation.blade.php`.
- **Navbar Styling**: We will preserve the existing layout structure but remove the search input div. The space saved will allow the profile dropdown to sit flush right, next to the notification bell.
- **Teal Branding in Profil**: The Profil pages will use `bg-slate-50` as the main canvas, and `bg-white` floating cards. The primary actions (e.g., Edit, Save) will use `bg-teal-600` buttons.

## Risks / Trade-offs

- **Risk**: Breaking mobile responsiveness of the navbar during redesign.
  **Mitigation**: We will test the navbar at 375px before completing Phase 1.
- **Risk**: Missing logout `POST` form implementation.
  **Mitigation**: We will carefully copy the existing logout form code (with `@csrf`) into both the desktop dropdown and mobile hamburger menu.
