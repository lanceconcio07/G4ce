# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Enterprise & scaling business leaders, COOs, and operations directors looking to outsource dedicated teams (Customer Support, Back Office, Technical Support, Sales Development) with customized team sizing and NDA protection.

## Product Purpose

Provide a seamless, high-trust digital consultation booking portal that connects prospective enterprise clients with G4ce BPO Solutions Consultants for confidential 30-minute discovery and operational scoping sessions.

## Positioning

High-touch, enterprise-grade BPO partnerships backed by immediate scheduling certainty: multi-timezone dynamic slot resolution, pre-call NDA commitments, automated RFC 5545 calendar invitations, and an integrated Gemini AI concierge to address operational FAQs in real-time.

## Operating Context

- Prospective clients evaluating BPO vendor risk, team capability, headcount elasticity, and confidentiality.
- Discovery call booking flow operating across global timezones (US, Europe, APAC) with real-time conversion to the client's local time.
- Automated email pipeline delivering meeting links and calendar `.ics` invites directly to client inboxes upon confirmation.

## Capabilities and Constraints

- **Confirmed Capabilities**:
  - 3-step reactive booking wizard (`BookingWizard` Livewire component) for service selection, team size tiering, date/time slot picking, and client profiling.
  - Timezone-aware slot generation and consultant availability checking (`BookingService`).
  - Automated reference code generator (`G4CE-BK-XXXXX`).
  - Native socket SMTP email dispatch with RFC 5545 `.ics` calendar invite attachments (`CalendarService`, `SimpleSmtpMailer`).
  - Floating Gemini AI FAQ assistant widget (`FaqWidget`, `GeminiFaqService`).
- **Technical Stack & Constraints**:
  - Laravel 11, Livewire 3.5, Alpine.js, Tailwind CSS 3.4, Vite.
  - Database schema: `services`, `consultants`, `schedules`, `bookings`.
  - Strictly preserve the 30-minute discovery call structure, NDA request toggle, and instant calendar invite pipeline.

## Brand Commitments

- Brand Name: G4ce BPO Services.
- Visual Tone: Professional, trustworthy, clean, modern enterprise tech-enabled BPO.
- Brand Assets: Brand logo in `public/images/logo.webp` / `resources/images/logo.webp`, brand primary blue palette (`#2563EB` / `brand-600`).

## Evidence on Hand

- Fully functional booking engine and models in `app/Models/` and `app/Livewire/`.
- Pre-seeded BPO services and consultant profiles in `database/seeders/`.
- Brand logo asset present at `public/images/logo.webp`.

## Product Principles

1. **Frictionless Trust**: Make confidentiality (NDA) and consulting credentials transparent at every step of the journey.
2. **Scheduling Certainty**: Eliminate timezone confusion through automatic local time conversion and instant `.ics` calendar dispatch.
3. **Respect for Enterprise Time**: Keep discovery qualification sharp, concise (30 minutes), and supported by 24/7 AI-assisted FAQ resolution.
4. **Reliable Craft**: Ensure resilient email delivery, graceful fallbacks for third-party APIs, and clear state feedback on all interactive inputs.

## Accessibility & Inclusion

Ensure high color contrast ratios for enterprise readability, full keyboard navigability across the multi-step wizard, accessible modal/calendar dialog controls, and WCAG AA compliance.
