## Why

The current Navbar has a non-functional search button and lacks access to the user profile menu on Desktop. Furthermore, the Profil Kader and Edit Profil Kader pages still use outdated designs and have not been unified with the premium "Teal branding" and modern layout used on the Dashboard and Laporan pages. Redesigning these components ensures a consistent, functional, and premium user experience across the entire portal.

## What Changes

- **Navbar**: Remove the non-functional search button. Add a desktop profile dropdown (avatar, user info, Edit Profil, Pusat Bantuan, Tentang Aplikasi, Logout) that matches the mobile hamburger menu structure. Refine navbar styling to match the premium Teal branding.
- **Profil Kader Page**: Redesign using `ui-ux-pro-max` principles with modern floating cards, subtle depth, and responsive mobile-first layouts.
- **Edit Profil Kader Form**: Upgrade the form layout with improved typography, focused states, and consistent action buttons.

## Capabilities

### New Capabilities
None

### Modified Capabilities
None

*(Note: This change focuses entirely on UI refactoring and bug fixes without altering core spec-level behavior. `skip_specs` will be set to true).*

## Impact

- `resources/views/layouts/navigation.blade.php` (Navbar redesign, Search removal, Desktop dropdown)
- `resources/views/kader/profil-kader.blade.php` (UI Refactor)
- `resources/views/kader/edit-profil-kader.blade.php` (UI Refactor)
