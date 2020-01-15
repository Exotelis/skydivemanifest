module.exports = {
  preset: '@vue/cli-plugin-unit-jest',
  'collectCoverage': true,
  'collectCoverageFrom': ['src/**/*.{js,vue}', '!(src/main.js)', '!(src/registerServiceWorker.js)'],
  'coverageReporters': ['html', 'text-summary']
};
