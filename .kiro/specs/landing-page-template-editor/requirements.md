# Requirements Document

## Introduction

This feature adds a comprehensive **Landing Page Template Editor** to the admin panel, giving admins and managers full control over every section of the public-facing landing page (`/{slug}`). Currently `dynamic.blade.php` is driven entirely by fallback strings because `$business` is always `null`. This feature wires up a `Business` Eloquent model (backed by a new database migration), exposes all editable content fields through a structured editor in the existing Edit Business Profile page at `/admin/businesses/{slug}/edit`, and ensures saved data is reflected live on the public page.

The editor covers every field already consumed by `dynamic.blade.php` through `data_get($business, ...)`:

- **Hero section** — badge label, headline, tagline, description, primary CTA, secondary CTA, assistant title/description, cover photo
- **About section** — badge, headline, description, blockquote
- **Feature cards** (up to 3) — label, description, 4 bullets, has-rooms toggle
- **Gallery** (3 items) — title, description, image upload
- **Room modal** — modal title, description, inquiry text

---

## Glossary

- **Business**: The Eloquent model (`App\Models\Business`) representing a tenant business (e.g., Dakong Balay, Villa Carmelita, Monclaire Pool), identified by a unique `slug`.
- **Slug**: A URL-safe identifier (e.g., `dakong-balay`) used in both the public route `/{slug}` and the admin route `/admin/businesses/{slug}/edit`.
- **Landing_Page**: The public-facing view rendered at `/{slug}` by `resources/views/landing/dynamic.blade.php`.
- **Template_Editor**: The landing page content editing form, embedded as a new section within the existing Edit Business Profile admin page.
- **Admin**: A platform-level user with full access to all businesses.
- **Manager**: A business-scoped user with access only to their assigned business's admin pages.
- **Feature_Card**: One of up to three "Our Businesses" highlight blocks displayed on the Landing_Page.
- **Gallery_Item**: One of exactly three visual showcase cards displayed in the Gallery section of the Landing_Page.
- **Hero_Section**: The first visible section of the Landing_Page, containing the headline, tagline, CTA buttons, and cover photo.
- **About_Section**: The "About" section of the Landing_Page containing a badge, headline, description, and blockquote quote.
- **Room_Modal**: The DaisyUI dialog on the Landing_Page triggered by the "Check Rooms & Live Availability" button when a Feature_Card has `has_rooms` enabled.
- **Landing_Page_Content**: The aggregate of all fields stored on the Business model that control what appears on the Landing_Page.

---

## Requirements

### Requirement 1: Business Model and Database Schema

**User Story:** As a developer, I want a `Business` Eloquent model backed by a database table, so that landing page content fields can be persisted and retrieved by slug.

#### Acceptance Criteria

1. THE `Business` model SHALL have a unique `slug` column that is used to look up a business record.
2. THE `Business` model SHALL expose all landing page content fields used by `data_get($business, ...)` calls in `dynamic.blade.php` as database columns.
3. THE `Business` model SHALL store the following Hero fields: `name`, `badge_label`, `headline`, `tagline`, `description`, `primary_cta_label`, `primary_cta_url`, `secondary_cta_label`, `secondary_cta_url`, `assistant_title`, `assistant_description`, `cover_photo_path`.
4. THE `Business` model SHALL store the following About fields: `about_badge`, `about_headline`, `about_description`, `about_quote`.
5. THE `Business` model SHALL store the following Feature Card fields for each of three cards: `feature_one_label`, `feature_one_description`, `feature_one_bullet_1` through `feature_one_bullet_4`, `feature_two_label`, `feature_two_description`, `feature_two_bullet_1` through `feature_two_bullet_4`, `feature_three_label`, `feature_three_description`, `feature_three_bullet_1` through `feature_three_bullet_4`, `feature_three_has_rooms`.
5. THE `Business` model SHALL store the following Gallery fields: `gallery_one_title`, `gallery_one_description`, `gallery_one_image_path`, `gallery_two_title`, `gallery_two_description`, `gallery_two_image_path`, `gallery_three_title`, `gallery_three_description`, `gallery_three_image_path`.
6. THE `Business` model SHALL store the following Room Modal fields: `room_business_name`, `room_modal_title`, `room_modal_description`, `room_inquiry_text`.
7. WHEN the `Business` migration is run, THE `Database` SHALL create a `businesses` table with all specified columns, with text columns defaulting to `null` where no default is specified.

---

### Requirement 2: Public Landing Page Wired to Database

**User Story:** As a visitor, I want the public landing page at `/{slug}` to display the business's saved content, so that I see real information instead of placeholder text.

#### Acceptance Criteria

1. WHEN a visitor requests `/{slug}`, THE `LandingController` SHALL look up the `Business` record by `slug` and pass it to `dynamic.blade.php`.
2. IF no `Business` record matches `slug`, THEN THE `LandingController` SHALL return a 404 response.
3. WHEN `$business` is a valid `Business` model instance, THE `Landing_Page` SHALL display field values from the database instead of the hardcoded fallback strings.
4. WHEN a `Business` field is `null` or empty, THE `Landing_Page` SHALL display the existing `data_get` fallback string for that field.

---

### Requirement 3: Landing Page Template Editor — Access and Layout

**User Story:** As an admin or manager, I want a dedicated "Landing Page" tab or section on the Edit Business Profile page, so that I can reach the landing page editor without navigating away from the current admin flow.

#### Acceptance Criteria

1. THE `Template_Editor` SHALL be accessible from the existing `/admin/businesses/{slug}/edit` page as a distinct, clearly labelled section below the existing "Business information" form.
2. THE `Template_Editor` SHALL be visible to both admin and manager roles for their assigned business.
3. THE `Template_Editor` SHALL NOT replace or disrupt the existing Business information form, status toggle, or product/room catalog sections currently on the edit page.
4. WHEN the editor page loads, THE `Template_Editor` SHALL pre-populate every field with the current values stored in the `Business` record for that `slug`.

---

### Requirement 4: Hero Section Editor

**User Story:** As an admin or manager, I want to edit all Hero section content fields, so that visitors see up-to-date headlines, CTAs, and cover imagery on the public page.

#### Acceptance Criteria

1. THE `Template_Editor` SHALL provide text inputs for: `badge_label`, `headline`, `tagline`, `description`, `primary_cta_label`, `primary_cta_url`, `secondary_cta_label`, `secondary_cta_url`, `assistant_title`, `assistant_description`.
2. THE `Template_Editor` SHALL provide a file upload control for `cover_photo_path` that accepts JPEG, PNG, and WebP images up to 5 MB.
3. WHEN a cover photo is uploaded, THE `Template_Editor` SHALL store the file in the `storage/app/public/covers` directory and save the relative path in `cover_photo_path`.
4. WHEN the Hero form is saved, THE `Business` record SHALL reflect the submitted values for all Hero fields.
5. IF a submitted URL field (`primary_cta_url` or `secondary_cta_url`) does not begin with `#`, `/`, `http://`, or `https://`, THEN THE `Template_Editor` SHALL reject the form save and return a descriptive validation message without persisting any changes to the `Business` record.
6. IF the database update fails after Hero form validation passes, THEN THE `Template_Editor` SHALL reject the save and display an error message without partial updates.

---

### Requirement 5: About Section Editor

**User Story:** As an admin or manager, I want to edit the About section content, so that the About section on the public page accurately describes the business.

#### Acceptance Criteria

1. THE `Template_Editor` SHALL provide text inputs for `about_badge`, `about_headline`, `about_description`, and `about_quote`.
2. WHEN the About form is saved, THE `Business` record SHALL reflect the submitted values for all About fields.
3. WHEN `about_description` or `about_quote` is submitted empty, THE `Template_Editor` SHALL reject the submission and display a validation message indicating the field is required.

---

### Requirement 6: Feature Cards Editor

**User Story:** As an admin or manager, I want to edit up to three Feature Card blocks, so that the "Our Businesses" section on the public page shows accurate names, descriptions, and bullet points.

#### Acceptance Criteria

1. THE `Template_Editor` SHALL render three Feature Card sub-forms, labelled "Feature Card 1", "Feature Card 2", and "Feature Card 3".
2. EACH Feature Card sub-form SHALL provide inputs for `label`, `description`, and four bullet point fields (`bullet_1` through `bullet_4`).
3. THE third Feature Card sub-form SHALL additionally provide a boolean toggle for `has_rooms`, controlling whether the "Check Rooms & Live Availability" button appears on the Landing_Page.
4. WHEN a Feature Card sub-form is saved, THE `Business` record SHALL reflect the submitted label, description, bullet values, and (for card 3) the `has_rooms` flag.
5. WHEN a Feature Card `label` field is submitted empty, THE `Template_Editor` SHALL reject the submission with a validation message.
6. WHEN bullet fields are left empty, THE `Template_Editor` SHALL save `null` for those fields and THE `Landing_Page` SHALL not render those bullet items.

---

### Requirement 7: Gallery Editor

**User Story:** As an admin or manager, I want to edit three Gallery items including their titles, descriptions, and images, so that the Gallery section on the public page shows relevant visuals.

#### Acceptance Criteria

1. THE `Template_Editor` SHALL render three Gallery sub-forms, labelled "Gallery Item 1", "Gallery Item 2", and "Gallery Item 3".
2. EACH Gallery sub-form SHALL provide text inputs for `title` and `description`, and a file upload control for the item image.
3. WHEN a gallery image is uploaded, THE `Template_Editor` SHALL store the file in `storage/app/public/gallery` and save the relative path in the corresponding `gallery_*_image_path` field.
4. Gallery image upload controls SHALL accept JPEG, PNG, and WebP images up to 5 MB.
5. WHEN a Gallery sub-form is saved with a `title` that is empty, THE `Template_Editor` SHALL reject the submission with a validation message.
6. WHEN a Gallery item has no uploaded image, THE `Landing_Page` SHALL display the gradient placeholder defined in the Blade template.

---

### Requirement 8: Room Modal Editor

**User Story:** As an admin or manager, I want to edit the Room Modal header text and inquiry message, so that the modal shown to guests contains accurate, business-specific information.

#### Acceptance Criteria

1. THE `Template_Editor` SHALL provide text inputs for `room_business_name`, `room_modal_title`, `room_modal_description`, and `room_inquiry_text`.
2. WHEN the Room Modal form is saved, THE `Business` record SHALL reflect the submitted values for all four Room Modal fields.
3. WHEN `room_modal_title` is submitted empty, THE `Template_Editor` SHALL reject the submission with a validation message.

---

### Requirement 9: Save and Feedback

**User Story:** As an admin or manager, I want clear confirmation when my changes are saved or if an error occurred, so that I know the public page has been updated successfully.

#### Acceptance Criteria

1. WHEN the landing page editor form is submitted successfully, THE `Template_Editor` SHALL redirect back to the edit page and display a success flash message confirming the update.
2. IF a server-side validation error occurs, THEN THE `Template_Editor` SHALL redisplay the form with the submitted values preserved and inline error messages next to the failing fields.
3. THE save action SHALL be accessible via a clearly labelled submit button within the Template_Editor section.
4. WHEN changes are saved, THE `Landing_Page` for that `slug` SHALL immediately reflect the updated content on next page load without any additional steps.

---

### Requirement 10: Authorization

**User Story:** As a platform administrator, I want only authorized admins and managers to be able to save landing page content, so that no unauthorized user can alter a public-facing page.

#### Acceptance Criteria

1. WHEN an unauthenticated user sends a POST request to the landing page update route, THE `Router` SHALL redirect the user to the login page.
2. WHEN an authenticated manager sends a POST request to update a business that is not their assigned business, THE `Router` SHALL return a 403 Forbidden response.
3. THE landing page update route SHALL require CSRF token validation on every POST submission.
