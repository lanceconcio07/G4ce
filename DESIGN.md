---
name: G4ce BPO Services
description: Enterprise consultation booking & digital BPO solutions portal
colors:
  primary: "#0267D2"
  primary-hover: "#0252AA"
  navy-900: "#0F172A"
  navy-800: "#1E293B"
  slate-700: "#334155"
  slate-500: "#64748B"
  slate-200: "#E2E8F0"
  neutral-bg: "#F8FAFC"
  surface: "#FFFFFF"
typography:
  display:
    fontFamily: "Manrope, system-ui, sans-serif"
    fontSize: "clamp(2rem, 5vw, 3rem)"
    fontWeight: 700
    lineHeight: 1.15
  headline:
    fontFamily: "Manrope, system-ui, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 700
    lineHeight: 1.3
  body:
    fontFamily: "Manrope, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Manrope, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 600
    letterSpacing: "0.05em"
rounded:
  sm: "8px"
  md: "12px"
  lg: "16px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.surface}"
    rounded: "{rounded.md}"
    padding: "10px 20px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
---

# Design System: G4ce BPO Services

## Overview

**Creative North Star: "The Sovereign Operator"**

The G4ce BPO Services consultation interface is designed to evoke quiet operational competence, enterprise trust, and executive clarity. It deliberately avoids generic BPO tropes—such as floating gradient text, arbitrary kickers, and cluttered stock imagery—in favor of generous whitespace, structured 8pt vertical rhythm, high-contrast typography, and soft ambient elevation.

**Key Characteristics:**
- **Crisp Executive Palette**: Deep slate/navy grounding, stark white elevated cards, and high-contrast electric royal cobalt accents.
- **Precision 8pt Grid**: Systematic increments from 4px micro-gaps to 64px section breathing space.
- **Layered Elevation**: Soft, multi-stop drop shadows simulating natural diffuse light without harsh block borders.
- **Responsive Micro-Interactions**: Tactile button lift, subtle card borders, and smooth focus-visible outlines.

## Colors

A disciplined palette balancing deep architectural neutrals with a high-contrast electric royal cobalt accent.

### Primary
- **Electric Royal Cobalt** (`#0267D2`): Reserved for primary calls-to-action, confirmed step indicators, active selection borders, and brand verification marks.

### Neutral
- **Deep Navy 900** (`#0F172A`): Primary headings, page titles, and chat widget header.
- **Slate 800** (`#1E293B`): Body text on light backgrounds and card titles.
- **Slate 700** (`#334155`): Input labels and secondary controls.
- **Slate 500** (`#64748B`): Descriptive subheadings and muted metadata.
- **Slate 200** (`#E2E8F0`): Subtle 1px structural borders and dividers.
- **Slate 50** (`#F8FAFC`): Global page background canvas.
- **Pure White** (`#FFFFFF`): Elevated card surfaces.

### Named Rules
**The 10% Accent Rule.** The primary royal cobalt is restricted to interactive focal points and active selection states, maintaining high contrast and executive sobriety.

## Typography

**Display Font:** `Manrope` (with `system-ui, sans-serif` fallback)
**Body Font:** `Manrope` (with `system-ui, sans-serif` fallback)

**Character:** A modern geometric grotesque engineered for legibility, balanced numerical figures, and sharp headings.

### Hierarchy
- **Display** (Bold 700, `clamp(2rem, 5vw, 3rem)`, line-height 1.15): Page hero titles (`<h1>`).
- **Headline** (Bold 700, `1.25rem`, line-height 1.3): Wizard card step headers (`<h2>`).
- **Body** (Regular 400 & Medium 500, `0.875rem`, line-height 1.6): Paragraph text, service descriptions, and form inputs.
- **Label** (SemiBold 600, `0.75rem`, letter-spacing `0.05em`, uppercase): Form field labels and category indicators.

### Named Rules
**The Natural Authority Rule.** Headings carry their own visual weight without eyebrows, gradient fills, or decorative underline accents.

## Layout

Structured around an 8pt spatial grid with generous breathing room.
- **Containers**: Hero max width `56rem` (`max-w-4xl`), Wizard max width `56rem` (`max-w-4xl`), Page max width `80rem` (`max-w-7xl`).
- **Rhythm**: Spacing progresses in 8pt increments: 4px (`xs`), 8px (`sm`), 16px (`md`), 24px (`lg`), 32px (`xl`), 48px (`2xl`), 64px (`3xl`).

## Elevation & Depth

Surfaces rely on soft, multi-stop directional shadows simulating soft overhead light.
- **Subtle**: `0 1px 2px 0 rgba(15, 23, 42, 0.04)` (form inputs, pills).
- **Elevation-Low**: `0 1px 3px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04)` (standard cards).
- **Elevation-Md**: `0 4px 14px -1px rgba(15, 23, 42, 0.07), 0 2px 6px -2px rgba(15, 23, 42, 0.04)` (hover states, dropdowns).
- **Elevation-Lg**: `0 12px 28px -4px rgba(15, 23, 42, 0.08), 0 4px 10px -3px rgba(15, 23, 42, 0.04)` (wizard card container).
- **Elevation-Xl**: `0 24px 48px -12px rgba(15, 23, 42, 0.12), 0 8px 16px -4px rgba(15, 23, 42, 0.04)` (floating AI concierge modal).

## Shapes

- **Base Radius**: `8px` (`rounded-lg`) for buttons, badges, and calendar days.
- **Card Radius**: `12px` (`rounded-xl`) for service option cards, timezone chips, and input fields.
- **Container Radius**: `16px` (`rounded-2xl`) for main wizard containers and floating dialogs.
- **Full Radius**: `9999px` (`rounded-full`) for progress indicators and floating action buttons.

## Components

### Buttons
- **Shape**: Rounded (`12px` / `rounded-xl`).
- **Primary**: Background `#0267D2`, white text, padding `10px 20px` (`px-5 py-2.5`).
- **Hover / Focus**: Lifts `-0.5px`, elevation shadow, with `ring-2 ring-brand-600 ring-offset-2` on focus-visible.
- **Secondary**: White background, `#334155` text, 1px `#E2E8F0` border.

### Service Cards
- **Shape**: Rounded-xl with 1px border.
- **Hover**: Lifts `-0.5px` with subtle shadow (`shadow-card-hover`).
- **Selected State**: 1px `#0267D2` border, soft `#F0F7FF` tint fill, and electric brand checkmark.

### Inputs & Fields
- **Shape**: Rounded-xl with 1px `#E2E8F0` border.
- **Focus**: Border shifts to `#0267D2` with subtle ring glow (`focus:ring-2 focus:ring-brand-600/20`).

### AI Concierge Widget
- **Trigger**: Slate 900 floating action button with pulse beacon.
- **Chat Window**: Slate 900 header, soft elevation-xl shadow, and custom scrollbar.

## Do's and Don'ts

### Do:
- **Do** maintain strict 8pt grid increments between all form fields and cards.
- **Do** preserve minimum 4.5:1 text contrast on all secondary and placeholder labels.
- **Do** keep button micro-interactions restrained (`translate-y-0.5` max).

### Don't:
- **Don't** add decorative kickers or eyebrows above section headings.
- **Don't** apply multi-color gradient fills to headline typography.
- **Don't** use harsh offset zero-blur shadows or raw un-themed scrollbars.
