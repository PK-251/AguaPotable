---

name: J.A.S.S. QUILCATA Systematic Design
colors:
  surface: '#10131b'
  surface-dim: '#10131b'
  surface-bright: '#363942'
  surface-container-lowest: '#0b0e16'
  surface-container-low: '#191b24'
  surface-container: '#1d1f28'
  surface-container-high: '#272a33'
  surface-container-highest: '#32343e'
  on-surface: '#e1e2ee'
  on-surface-variant: '#c2c6d8'
  inverse-surface: '#e1e2ee'
  inverse-on-surface: '#2d3039'
  outline: '#8c90a1'
  outline-variant: '#424655'
  surface-tint: '#b1c5ff'
  primary: '#b1c5ff'
  on-primary: '#002c70'
  primary-container: '#0d6efd'
  on-primary-container: '#ffffff'
  inverse-primary: '#0057ce'
  secondary: '#bfc8d0'
  on-secondary: '#293138'
  secondary-container: '#414a52'
  on-secondary-container: '#b0b9c2'
  tertiary: '#ffb599'
  on-tertiary: '#5a1c00'
  tertiary-container: '#cf4b00'
  on-tertiary-container: '#ffffff'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#dae2ff'
  primary-fixed-dim: '#b1c5ff'
  on-primary-fixed: '#001946'
  on-primary-fixed-variant: '#00419e'
  secondary-fixed: '#dbe4ed'
  secondary-fixed-dim: '#bfc8d0'
  on-secondary-fixed: '#141d23'
  on-secondary-fixed-variant: '#3f484f'
  tertiary-fixed: '#ffdbce'
  tertiary-fixed-dim: '#ffb599'
  on-tertiary-fixed: '#370e00'
  on-tertiary-fixed-variant: '#7f2b00'
  background: '#10131b'
  on-background: '#e1e2ee'
  surface-variant: '#32343e'
typography:
  display-lg:
    fontSize: 40px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-md:
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  body-base:
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  body-sm:
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-caps:
    fontSize: 12px
    fontWeight: '700'
    lineHeight: '1'
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base-unit: 0.25rem
  gutter: 1.5rem
  sidebar-width: 280px
  container-max: 1320px

##   margin-page: 2rem

## Brand & Style

The brand personality of this design system is institutional, reliable, and civic-minded. As a water management system, it must project stability and precision. The target audience includes both administrative municipal employees requiring efficient data entry and local residents seeking clarity regarding their utility services. 

The aesthetic adheres to a **Corporate / Modern** style, heavily influenced by the structured utility of Bootstrap 5.3. It utilizes a **Dark Mode** interface to provide a sophisticated, high-contrast environment that reduces eye strain during night shifts or in low-light administrative offices, fostering a sense of modern, transparent public service.

## Colors

The palette is rooted in the "Deep Blue" primary, adapted for a dark interface to symbolize water and official authority without causing glare. This is supported by a functional spectrum of semantic colors: Green for paid balances, Amber for overdue notices, and Red for critical service alerts.

The background utilizes deep neutrals to maintain a professional atmosphere. Typography and UI accents use high-contrast light grays and whites to ensure maximum readability and compliance with WCAG accessibility standards for public information in a dark environment.

## Typography

This design system utilizes **Public Sans** to achieve an institutional and official tone. It is a neutral, highly legible typeface designed for government and administrative interfaces. 

Hierarchies are strictly enforced to ensure users can navigate complex data tables and billing statements easily. Headlines are kept professional and understated, while body text maintains a generous line height to prevent fatigue during data entry.

## Elevation & Depth

Depth is conveyed through **Tonal Layers** rather than heavy shadows, which can become muddy in dark themes. Surfaces "lift" by becoming slightly lighter in color.

- **Level 0 (Surface):** The deepest dark background (#111418).
- **Level 1 (Cards/Containers):** A slightly lighter charcoal surface with a subtle 1px border (#2d3039) to define boundaries.
- **Level 2 (Dropdowns/Modals):** A more pronounced surface elevation with a soft, dark ambient shadow to indicate focus and interactivity.

## Shapes

In alignment with the Bootstrap 5.3 aesthetic, the design system utilizes a soft border radius of **0.25rem (4px)** for primary UI components including buttons, input fields, and cards. This creates a professional look that is modern but stays within the traditional "institutional" framework—avoiding the overly playful nature of fully rounded pill shapes or the harshness of sharp corners.

## Components

- **Buttons:** Use the 0.25rem radius. Primary buttons use the Deep Blue; Secondary buttons use the Slate Gray outline. In dark mode, button labels must maintain high contrast against the button fill.
- **Input Fields:** Utilize "Floating Labels" for a modern, compact form experience. Borders are dark-gray until focused, where they transition to the Primary Blue.
- **Cards:** These are the primary vessel for information. Each card features a 1.5rem padding, a dark-charcoal background, and a subtle border for separation.
- **Data Tables:** High-density, with alternating row fills for legibility. Clear headers and high-contrast text are essential for data-heavy water management tasks.
- **Chips/Badges:** Small labels used for status tracking (e.g., "Paid", "Pending"), using semantic colors with appropriate contrast for the dark theme.
- **Resident Navbar:** A dark, high-contrast navigation bar with a simple logo and clear utility links on the right-hand side.