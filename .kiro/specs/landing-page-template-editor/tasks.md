# Tasks

## Task List

- [x] 1. Add Landing Page Content section card to edit.blade.php
  - Append a new `<section id="landing-editor">` card after the Products/Rooms section, inside the main `<div class="space-y-8">` wrapper
  - Include the section heading ("Landing Page Content"), subtitle, and "Preview public page ↗" link
  - Include the tab bar container `<div id="lp-tabs">` with five tab buttons: Hero, About, Feature Cards, Gallery, Room Modal
  - Hero tab should be active by default (dark bg, white text); others should be inactive
  - Include the panel container `<div id="lp-panels">` with five panel divs, only `lp-panel-hero` visible initially (others have `hidden` class)
  - Include the footer row with the "Changes are preview-only" note and the `#lp-save-btn` button
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R1, R2, R8, R9

- [x] 2. Build the Hero tab panel fields
  - Inside `#lp-panel-hero`, render labeled form controls for: Badge Label, Headline (textarea), Tagline (textarea), Description (textarea), Primary CTA Label, Primary CTA URL, Secondary CTA Label, Assistant Title, Assistant Description (textarea)
  - Pre-populate each field with the matching fallback value from `dynamic.blade.php`
  - Add a read-only cover photo row (read-only input showing the slug-specific filename + disabled "Replace image" button) using the `$businessCover` variable already available in the `@php` block
  - Use `grid gap-4 md:grid-cols-2` for short single-line fields; single-column for textareas
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R3

- [x] 3. Build the About tab panel fields
  - Inside `#lp-panel-about`, render labeled form controls for: About Badge (input), About Headline (input), About Description (textarea), About Quote (textarea)
  - Pre-populate each field with the matching fallback values from `dynamic.blade.php`
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R4

- [x] 4. Build the Feature Cards tab panel fields
  - Inside `#lp-panel-features`, render three sub-groups inside `rounded-3xl bg-[#f8fafc] p-5` containers with dividers between them, labelled "Feature Card 1", "Feature Card 2", "Feature Card 3"
  - Each sub-group has: Label (input), Description (textarea), Bullet 1–4 (four inputs with placeholder text)
  - Feature Card 3 sub-group additionally has a DaisyUI `toggle toggle-success` labeled "Show 'Check Rooms & Live Availability' button", checked by default
  - Pre-populate Label and Description with matching fallback values; Bullet fields can be empty with placeholder text
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R5

- [x] 5. Build the Gallery tab panel fields
  - Inside `#lp-panel-gallery`, render three sub-groups with the same `rounded-3xl bg-[#f8fafc] p-5` container pattern, labelled "Gallery Item 1", "Gallery Item 2", "Gallery Item 3"
  - Each sub-group has: Title (input), Description (textarea), a gradient placeholder image box (`h-28 rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0]`), and a disabled "Upload image (coming soon)" button
  - Pre-populate Title and Description with matching fallback values from `dynamic.blade.php`
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R6

- [x] 6. Build the Room Modal tab panel fields
  - Inside `#lp-panel-room-modal`, add an informational note explaining this section controls the room availability modal on Feature Card 3
  - Render labeled form controls for: Room Business Name (input), Modal Title (input), Modal Description (textarea), Inquiry Text (textarea)
  - Pre-populate each field with the matching fallback values from `dynamic.blade.php`
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R7

- [x] 7. Add tab-switching JavaScript and save handler
  - Inside the existing `<script>` block at the bottom of the file, append the tab-switching logic: clicking a `.lp-tab` button deactivates all tabs/panels and activates the clicked one
  - Append the `#lp-save-btn` click handler: disable the button, change its text to "Saving…", wait 600 ms, call the existing `showToast()` with message "Landing page content updated successfully." and title "Content Saved", then re-enable the button and restore its label
  - **File:** `resources/views/admin/businesses/edit.blade.php`
  - **Requirements:** R2, R8
