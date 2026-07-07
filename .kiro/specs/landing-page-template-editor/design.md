# Design Document — Landing Page Template Editor (UI Only)

## Overview

Add a **Landing Page Content** editor section to the existing `edit.blade.php` view. The section is a tab-based form that mirrors every editable field in `dynamic.blade.php`. All interactions are client-side only: fields are pre-populated with the same fallback defaults the landing page currently shows, and clicking "Save" fires the existing `showToast()` helper for visual feedback. No backend changes are made.

---

## Architecture

### Affected File

| File | Change |
|---|---|
| `resources/views/admin/businesses/edit.blade.php` | Append a new `<section>` card after the existing Products/Rooms section, before the closing `</div>` of the main content wrapper. Extend the existing `<script>` block with tab-switching logic and the save handler. |

No new routes, controllers, models, migrations, or partials are needed.

---

## UI Structure

### Placement in `edit.blade.php`

```
@section('content')
  <div class="space-y-8 ...">

    [Page heading]
    [Save-all header card]
    [Business information form + sidebar summary]    ← existing
    [Products / Rooms section]                       ← existing

    ──────────────────────────────────────────────── ← NEW below
    [Landing Page Content section]
      └─ Tab bar: Hero | About | Feature Cards | Gallery | Room Modal
      └─ Tab panels (one visible at a time)
      └─ Footer: preview note + Save button
    ────────────────────────────────────────────────

  </div>
@endsection
```

---

## Component Design

### Section Card Shell

```html
<section id="landing-editor" class="card bg-base-100 shadow-sm">
  <div class="card-body gap-6 p-6 lg:p-8">

    <!-- Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between border-b border-gray-100 pb-6">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Landing Page Content</h2>
        <p class="mt-1 text-sm text-gray-500">
          Edit the text and labels shown on the public-facing page at /{{ $currentSlug }}.
        </p>
      </div>
      <a href="/{{ $currentSlug }}" target="_blank"
         class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-700 hover:bg-[#f5f3ff] shrink-0">
        Preview public page ↗
      </a>
    </div>

    <!-- Tab bar -->
    <div id="lp-tabs" role="tablist" class="flex flex-wrap gap-1.5">
      <!-- 5 tab buttons — Hero active by default -->
    </div>

    <!-- Tab panels -->
    <div id="lp-panels">
      <!-- 5 panels, only one visible at a time -->
    </div>

    <!-- Footer -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-t border-gray-100 pt-6">
      <p class="text-xs text-gray-400">
        Changes are preview-only until the backend is connected.
      </p>
      <button type="button" id="lp-save-btn"
              class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9]">
        Save landing page content
      </button>
    </div>

  </div>
</section>
```

---

### Tab Bar

Five tab buttons. Active tab uses `bg-[#1e293b] text-white`, inactive uses `bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]`. Clicking a tab hides all panels and shows only the matching one.

| Tab label | `data-tab` value | Panel ID |
|---|---|---|
| Hero | `hero` | `lp-panel-hero` |
| About | `about` | `lp-panel-about` |
| Feature Cards | `features` | `lp-panel-features` |
| Gallery | `gallery` | `lp-panel-gallery` |
| Room Modal | `room-modal` | `lp-panel-room-modal` |

Tab button pattern:
```html
<button type="button" role="tab"
        class="lp-tab btn btn-sm rounded-full ..."
        data-tab="hero" aria-selected="true">
  Hero
</button>
```

---

### Panel: Hero

Fields and their pre-populated default values (matching `dynamic.blade.php` fallbacks):

| Field label | Input type | Default value |
|---|---|---|
| Badge Label | `<input>` | `AI-Powered Business Platform` |
| Headline | `<textarea rows="2">` | `Experience All Our Services in One Smart Platform` |
| Tagline | `<textarea rows="2">` | `Explore services, locations, offers, and FAQs through a single polished business experience.` |
| Description | `<textarea rows="3">` | `Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.` |
| Primary CTA Label | `<input>` | `Explore Businesses` |
| Primary CTA URL | `<input>` | `#businesses` |
| Secondary CTA Label | `<input>` | `Chat with AI` |
| Assistant Title | `<input>` | `One AI assistant connecting multiple businesses` |
| Assistant Description | `<textarea rows="2">` | `Ask once and get clear answers about services, locations, offers, and FAQs.` |
| Cover Photo | read-only `<input>` + disabled button | `dakong-balay-cover.jpg` (or slug-specific) |

Cover photo row uses the same `grid gap-3 md:grid-cols-[minmax(0,1fr)_auto]` pattern already used for the image field in the existing Business information form.

Layout: two-column grid (`md:grid-cols-2`) for short fields; single column for textareas.

---

### Panel: About

| Field label | Input type | Default value |
|---|---|---|
| About Badge | `<input>` | `About Your Business` |
| About Headline | `<input>` | `One platform. One assistant. Multiple businesses.` |
| About Description | `<textarea rows="3">` | *(same as business description default)* |
| About Quote | `<textarea rows="2">` | `Fast answers, consistent information, and a smoother customer journey.` |

---

### Panel: Feature Cards

Three sub-groups, each inside a `rounded-3xl bg-[#f8fafc] p-5` container with a divider between them.

Sub-group heading: `text-sm font-bold text-gray-700` — e.g., "Feature Card 1".

Each sub-group fields:

| Field label | Input type | Default |
|---|---|---|
| Label | `<input>` | `Featured Business One` / `Two` / `Three` |
| Description | `<textarea rows="2">` | Matching fallback from `dynamic.blade.php` |
| Bullet 1 | `<input>` | empty (placeholder: "e.g. Authentic cuisine") |
| Bullet 2 | `<input>` | empty |
| Bullet 3 | `<input>` | empty |
| Bullet 4 | `<input>` | empty |

Feature Card 3 only — extra toggle row at the bottom of its sub-group:

```html
<div class="flex items-center justify-between rounded-2xl bg-white border border-[#e2e8f0] px-4 py-3">
  <div>
    <div class="text-sm font-medium text-gray-700">Show "Check Rooms & Live Availability" button</div>
    <p class="text-xs text-gray-500 mt-0.5">Displays the room availability modal on the public page.</p>
  </div>
  <input type="checkbox" class="toggle toggle-success" checked>
</div>
```

---

### Panel: Gallery

Three sub-groups using the same `rounded-3xl bg-[#f8fafc] p-5` container pattern.

Each sub-group:

| Field label | Input type | Default |
|---|---|---|
| Title | `<input>` | e.g., `Warm Spaces` / `Dining Moments` / `Poolside Views` |
| Description | `<textarea rows="2">` | Matching fallback from `dynamic.blade.php` |

Image area per sub-group:
```html
<div class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
  <span class="text-xs text-gray-400">No image uploaded</span>
</div>
<button type="button" disabled
        class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed mt-2">
  Upload image (coming soon)
</button>
```

---

### Panel: Room Modal

Informational note at top:
```html
<div class="rounded-2xl bg-[#f8fafc] border border-[#e2e8f0] px-4 py-3 text-sm text-gray-500">
  These fields control the modal that appears when a visitor clicks "Check Rooms & Live Availability"
  on Feature Card 3.
</div>
```

| Field label | Input type | Default value |
|---|---|---|
| Room Business Name | `<input>` | `Your Business` |
| Modal Title | `<input>` | `Your Business Rooms & Availability` |
| Modal Description | `<textarea rows="2">` | `View real-time room rates and vacancies at Your Business` |
| Inquiry Text | `<textarea rows="2">` | `For booking inquiries, select "Chat with AI" or call support.` |

---

## JavaScript Design

All JS is appended to the **existing `<script>` block** at the bottom of `edit.blade.php` — no separate file needed.

### Tab Switching

```js
const lpTabs   = document.querySelectorAll('.lp-tab');
const lpPanels = document.querySelectorAll('.lp-panel');

lpTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        // Deactivate all
        lpTabs.forEach(t => {
            t.classList.remove('bg-[#1e293b]', 'text-white');
            t.classList.add('bg-[#f8fafc]', 'text-gray-700');
            t.setAttribute('aria-selected', 'false');
        });
        lpPanels.forEach(p => p.classList.add('hidden'));

        // Activate clicked
        tab.classList.add('bg-[#1e293b]', 'text-white');
        tab.classList.remove('bg-[#f8fafc]', 'text-gray-700');
        tab.setAttribute('aria-selected', 'true');
        document.getElementById('lp-panel-' + tab.dataset.tab).classList.remove('hidden');
    });
});
```

### Save Handler

Reuses the existing `showToast()` function already defined in `edit.blade.php`:

```js
const lpSaveBtn = document.getElementById('lp-save-btn');
if (lpSaveBtn) {
    lpSaveBtn.addEventListener('click', () => {
        lpSaveBtn.disabled = true;
        lpSaveBtn.textContent = 'Saving…';

        setTimeout(() => {
            showToast('Landing page content updated successfully.', 'Content Saved');
            lpSaveBtn.disabled = false;
            lpSaveBtn.textContent = 'Save landing page content';
        }, 600);
    });
}
```

---

## Field Layout Pattern

All panels use the same `form-control` + `label` + `label-text` pattern as the existing Business information form:

```html
<label class="form-control">
  <div class="label">
    <span class="label-text font-medium text-gray-700">Headline</span>
  </div>
  <textarea class="textarea textarea-bordered min-h-[72px] w-full bg-base-100"
            placeholder="e.g. Experience All Our Services in One Smart Platform"
  >Experience All Our Services in One Smart Platform</textarea>
</label>
```

Short fields (single-line inputs) are grouped into `grid gap-4 md:grid-cols-2` rows where it makes sense (e.g., CTA Label + CTA URL side by side).

---

## Slug-Specific Cover Photo Defaults

The cover photo read-only field is populated in the `@php` block conditionally, consistent with the existing pattern in the file:

| Slug | Cover photo filename |
|---|---|
| `dakong-balay` | `dakong-balay-cover.jpg` |
| `villa-carmelita` | `villa-carmelita-cover.jpg` |
| `monclaire-pool` | `monclaire-pool-cover.jpg` |

---

## Accessibility

- Tab buttons use `role="tab"` and `aria-selected`.
- Tab panels use `role="tabpanel"`.
- All inputs and textareas have explicit `<label>` associations via the DaisyUI `form-control` pattern.
- The disabled "Upload image" and "Replace image" buttons include `disabled` attribute and `cursor-not-allowed` styling.
- The "Preview public page" link opens in `target="_blank"` with visible `↗` indicator.
