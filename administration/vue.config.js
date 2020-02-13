module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? '/skydivemanifest/administration/dist' : '/',
  productionSourceMap: false,
  devServer: {
    https: false,
    host: '0.0.0.0',
    port: 5000
  },
  pluginOptions: {
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'src/locales',
      enableInSFC: false
    }
  },
  pwa: {
    workboxOptions: {
      exclude: [/.htaccess/]
    },
    themeColor: '#4eba88',
    msTileColor: '#1d2530',
    appleMobileWebAppStatusBarStyle: 'black'
  }
};
