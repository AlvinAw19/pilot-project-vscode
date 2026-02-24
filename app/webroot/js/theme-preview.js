document.addEventListener('DOMContentLoaded', function () {
  const options = document.querySelectorAll('.theme-option');
  options.forEach(option => {
    option.addEventListener('click', function (e) {
      const input = option.querySelector('input[type="radio"]');
      if (input) {
        input.checked = true;
        // mark selected visually
        options.forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');
        // apply a temporary live preview by adding theme class to body
        try {
          const themeKey = input.value;
          // remove existing theme- classes
          document.body.classList.forEach(cls => {
            if (cls.startsWith('theme-')) {
              document.body.classList.remove(cls);
            }
          });
          document.body.classList.add('theme-' + themeKey);
        } catch (err) {
          // ignore
        }
      }
    });
  });
});
