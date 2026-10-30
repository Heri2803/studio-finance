/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        cream: {
          50: '#FFFEFA',
          100: '#FDFBF7',
          200: '#F8F4EB',
          300: '#EFE8DA',
        },
        taby: {
          charcoal: '#2D2B38',
          muted: '#716D7D',
          border: '#E8E3D9',
          lilac: {
            light: '#F5F3FF',
            DEFAULT: '#EDE9FE',
            border: '#DDD6FE',
            dark: '#6D28D9',
          },
          yellow: {
            light: '#FFFDF0',
            DEFAULT: '#FEF3C7',
            border: '#FDE68A',
            dark: '#B45309',
          },
          mint: {
            light: '#F0FDF4',
            DEFAULT: '#D1FAE5',
            border: '#A7F3D0',
            dark: '#065F46',
          },
          rose: {
            light: '#FFF1F2',
            DEFAULT: '#FEE2E2',
            border: '#FECDD3',
            dark: '#991B1B',
          },
        },
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        display: ['"Nunito"', '"Fredoka"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        'pastel': '0 4px 0 0 rgba(45, 43, 56, 0.08)',
        'pastel-lg': '0 8px 0 0 rgba(45, 43, 56, 0.08), 0 12px 20px -4px rgba(45, 43, 56, 0.06)',
      },
      borderRadius: {
        '2xl': '16px',
        '3xl': '24px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
