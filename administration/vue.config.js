module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? process.env.VUE_APP_FRONTEND_PATH_ASSETS || '/skydivemanifest/administration/dist' : '/',
  productionSourceMap: false,
  devServer: {
    https: true,
    host: '0.0.0.0',
    port: 5000,
    proxy: process.env.VUE_APP_DEV_PROXY || ''
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
