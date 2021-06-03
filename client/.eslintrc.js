module.exports = {
  extends: [
    'plugin:@typescript-eslint/recommended',
    'plugin:prettier/recommended',
    'plugin:react/recommended',
    'prettier',
  ],
  overrides: [
    {
      files: ['**/__tests__/*.{j,t}s?(x)', '**/tests/**/*.spec.{j,t}s?(x)'],
      env: {
        jest: true,
      },
    },
  ],
  parser: '@typescript-eslint/parser',
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
    },
    ecmaVersion: 2020,
    sourceType: 'module',
  },
  root: true,
  settings: {
    react: {
      version: 'detect',
    },
  },
};
