const { colors } = require('tailwindcss/defaultTheme');

module.exports = {
  theme: {
    extend: {
      // fontFamily: {
      //   sans: [
      //     'Nunito', 
      //     '-apple-system',
      //     'BlinkMacSystemFont',
      //     '"Segoe UI"',
      //     'Roboto',
      //     '"Helvetica Neue"',
      //     'Arial',
      //     '"Noto Sans"',
      //     'sans-serif',
      //     '"Apple Color Emoji"',
      //     '"Segoe UI Emoji"',
      //     '"Segoe UI Symbol"',
      //     '"Noto Color Emoji"',
      //   ]
      // },
      screens: {
        'xxl': '1600px'
      },
      colors: {
        primary: colors.orange,
        secondary: colors.gray,
        success: colors.green,
        warning: colors.yellow,
        danger: colors.red,
        error: colors.red,
      },
      width: {
        '72': '18rem',
        '80': '20rem',
        '88': '22rem',
        '96': '24rem',
        '104': '26rem',
        '112': '28rem',
        '120': '30rem',
      }
    },
    customForms: theme => ({
      default: {
        'input, textarea, multiselect, select': {
          fontSize: theme('fontSize.sm'),
        },
        'input' : {
          width: theme('width.full'),
          paddingTop: theme('spacing.1'),
          paddingBottom: theme('spacing.1'),
          lineHeight: theme('lineHeight.relaxed'),
        },
      },
    })
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms'),
  ]
}
