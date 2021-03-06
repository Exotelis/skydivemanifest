module.exports = {
  preset: '@vue/cli-plugin-unit-jest/presets/typescript-and-babel',
  'collectCoverage': true,
  'collectCoverageFrom': [
    'src/**/*.{js,vue}',
    'src/filters/Datatable*.ts',
    'src/mixins/configs/*Mixin.ts',
    '!(src/main.js)',
    '!(src/registerServiceWorker.js)'],
  'coverageReporters': ['html', 'text-summary'],
  setupFilesAfterEnv: [
    './jestSetupAfterEnv.ts'
  ]
};
