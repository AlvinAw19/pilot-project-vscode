// Small script to allow live preview of theme selections in the profile page.
// Clicking a .theme-option will check its radio and update the body class to
// `theme-<name>` so users can preview before saving.
document.addEventListener('DOMContentLoaded', function () {
  try {
    const options = document.querySelectorAll('.theme-option');
    if (!options.length) return;

    function clearThemeClasses() {
      document.body.className = document.body.className
        .split(/\s+/)
        .filter(c => !c.startsWith('theme-'))
        .join(' ')
        .trim();
    }

    options.forEach(function (opt) {
      const input = opt.querySelector('input[type="radio"]');
      // Allow keyboard change as well
      if (input) {
        input.addEventListener('change', function () {
          if (this.checked) {
            clearThemeClasses();
            document.body.classList.add('theme-' + this.value);
          }
        });
      }

      opt.addEventListener('click', function (e) {
        // Ensure the radio is checked (label click usually does this)
        if (input && !input.checked) {
          input.checked = true;
        }
        if (input) {
          clearThemeClasses();
          document.body.classList.add('theme-' + input.value);
        }
      });
    });
  } catch (e) {
    // Don't break the page if preview script fails
    console && console.error && console.error('theme-preview error', e);
  }
});
