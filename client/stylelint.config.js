module.exports = {
  extends: ['stylelint-config-standard', 'stylelint-config-html', 'stylelint-config-prettier'],
  overrides: [
    {
      files: ['**/*.{js,jsx,ts,tsx}'],
      customSyntax: '@stylelint/postcss-css-in-js',
    },
  ],
};
