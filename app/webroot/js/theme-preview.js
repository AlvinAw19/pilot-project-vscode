document.addEventListener('DOMContentLoaded', function () {
    var previewContainer = document.querySelector('.theme-previews');
    if (!previewContainer) return;

    var radios = previewContainer.querySelectorAll('input[name="theme"]');
    var body = document.body || document.getElementsByTagName('body')[0];

    function applyThemeClass(theme) {
        // remove any existing theme-* class
        body.className = body.className.replace(/\btheme-\S+/g, '').trim();
        if (theme) {
            body.classList.add('theme-' + theme);
        }
    }

    // Live preview on change
    radios.forEach(function (r) {
        r.addEventListener('change', function (e) {
            applyThemeClass(e.target.value);
            // mark selected visual state
            radios.forEach(function (other) {
                var label = other.closest('.theme-option');
                if (label) {
                    label.classList.toggle('selected', other.checked);
                }
            });
        });
        // allow click on the image/label to toggle selection too (in case markup differs)
        var label = r.closest('.theme-option');
        if (label) {
            label.addEventListener('click', function () {
                r.checked = true;
                r.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }
    });

    // Ensure the theme from the server (body class) matches selection on load
    var current = (body.className.match(/theme-([a-z0-9-_]+)/i) || [null, null])[1];
    if (current) {
        radios.forEach(function (r) {
            var label = r.closest('.theme-option');
            if (r.value === current) {
                r.checked = true;
                if (label) label.classList.add('selected');
            } else if (label) {
                label.classList.remove('selected');
            }
        });
    }
});
