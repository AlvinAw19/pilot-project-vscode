# Koalala Finds - Styling Guide

## Overview
This application now features a modern, professional CSS styling system built with SCSS and compiled using Laravel Mix.

## Color Palette

### Primary Colors
- **Primary Blue**: `#4a90e2` - Main brand color (buttons, links, highlights)
- **Primary Dark**: `#357abd` - Hover states and emphasis
- **Primary Light**: `#6ba4e8` - Light accents

### Secondary Colors
- **Success Green**: `#50c878` - Cart button, success messages
- **Info Blue**: `#3498db` - Order button, info messages
- **Warning Orange**: `#f39c12` - Warning messages
- **Danger Red**: `#e74c3c` - Error messages, delete actions
- **Dark**: `#2c3e50` - Headings, important text
- **Light Gray**: `#ecf0f1` - Backgrounds, subtle elements

## CSS Classes

### Layout Classes
- `.container` - Max-width container with auto margins
- `.content` - White card-style content blocks with shadow
- `.row` - Flexbox row container
- `.column` - Flexbox column (can be sized with modifiers)

### Button Classes
- `.button` - Standard button (primary blue)
- `.button.cart-button` - Green cart button
- `.button.order-button` - Blue order button
- `.button.selected` - Active/selected state
- `.button-outline` - Outlined button style
- `.button-small` - Smaller button variant

### Form Classes
- `.users.form` - Centered form container for login/register
- `.input` - Form input wrapper
- `.search` - Search form container
- `.error-message` - Form error messages

### Message Classes
- `.message.success` - Success message (green)
- `.message.error` - Error message (red)
- `.message.warning` - Warning message (orange)
- `.message.info` - Info message (blue)
- `.message.default` - Default message (gray)

### Table Classes
- `.table-responsive` - Responsive table wrapper
- `.actions` - Table actions column
- `.catalog` - Special styling for product catalog tables

### Navigation Classes
- `.top-nav` - Top navigation bar
- `.side-nav` - Sidebar navigation
- `.pagination` - Pagination controls

### Utility Classes
- `.text-center` - Center-aligned text
- `.text-right` - Right-aligned text
- `.text-muted` - Muted text color
- `.mt-0`, `.mt-1`, `.mt-2`, `.mt-3` - Margin top variants
- `.mb-0`, `.mb-1`, `.mb-2`, `.mb-3` - Margin bottom variants
- `.p-0`, `.p-1`, `.p-2`, `.p-3` - Padding variants

### Badge Classes
- `.badge` - Default badge (primary)
- `.badge-success` - Success badge (green)
- `.badge-danger` - Danger badge (red)
- `.badge-warning` - Warning badge (orange)
- `.badge-info` - Info badge (blue)

## Component Examples

### Card Component
```php
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-body">
        Card content goes here
    </div>
    <div class="card-footer">
        Footer content
    </div>
</div>
```

### Button Group
```php
<div style="display:flex; gap:10px;">
    <?= $this->Html->link('Primary', ['action' => 'index'], ['class' => 'button']) ?>
    <?= $this->Html->link('Cart', ['action' => 'cart'], ['class' => 'button cart-button']) ?>
    <?= $this->Html->link('Orders', ['action' => 'orders'], ['class' => 'button order-button']) ?>
</div>
```

### Form Input
```php
<div class="input">
    <?= $this->Form->control('email', [
        'label' => __('Email'),
        'required' => true,
        'placeholder' => 'your@email.com'
    ]) ?>
</div>
```

## Design Features

### Visual Effects
- **Smooth Transitions**: All interactive elements have 0.3s transitions
- **Hover Effects**: Cards lift on hover, buttons darken
- **Shadows**: Subtle shadows on cards and buttons for depth
- **Rounded Corners**: Consistent border-radius across components

### Responsive Design
- Mobile-friendly navigation (stacks vertically)
- Responsive tables with horizontal scroll
- Flexible grid system
- Optimized for screens 768px and below

### Typography
- **Font Family**: System fonts (Apple, Segoe UI, Roboto)
- **Base Size**: 16px
- **Line Height**: 1.6
- **Headings**: Bold weights with good hierarchy

## Building CSS

### Development Build
```bash
docker-compose exec node npx sass /home/node/app/src/scss/styles.scss /home/node/app/build/css/styles.css
```

### Watch Mode
```bash
docker-compose exec node npm run watch
```

### Production Build
```bash
docker-compose exec node npm run build
```

## File Structure

```
frontend/
├── src/
│   ├── scss/
│   │   └── styles.scss      # Main SCSS file with all styles
│   ├── css/
│   │   ├── cake.css         # CakePHP default styles
│   │   ├── fonts.css        # Font definitions
│   │   ├── normalize.min.css # CSS reset
│   │   └── milligram.min.css # Base framework
│   └── js/
└── build/                   # Compiled assets
    ├── css/
    │   └── styles.css       # Compiled CSS (auto-generated)
    └── js/

app/
├── templates/
│   └── layout/
│       └── default.php      # Main layout file
└── webroot/                 # Public directory
    ├── css/                 # Linked to frontend/build/css
    └── js/
```

## Tips for Templates

1. **Use semantic HTML**: Proper heading hierarchy, semantic tags
2. **Add spacing**: Use margin utility classes for consistent spacing
3. **Wrap tables**: Use `.table-responsive` for mobile compatibility
4. **Button consistency**: Use standard button classes for uniform appearance
5. **Flash messages**: Use appropriate message classes for user feedback
6. **Form structure**: Wrap inputs in `.input` divs for consistent styling

## Customization

To customize colors or spacing, edit the SCSS variables at the top of `/frontend/src/scss/styles.scss`:

```scss
// Color Palette
$primary-color: #4a90e2;
$secondary-color: #50c878;
// ... etc
```

Then recompile the CSS using the build command.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

**Last Updated**: January 9, 2026
**Version**: 1.0.0
