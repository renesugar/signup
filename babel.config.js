module.exports = function (api) {
  api.cache(true);

  // web.dom.iterable is included if useBuiltIns is set
  // but web.dom.iterable no longer exists
  // See https://github.com/babel/babel/issues/9449
  const presets = [
    [
      "@babel/preset-env",
      {
        targets: {
          edge: "18",
          firefox: "68",
          chrome: "76",
          safari: "12.1",
        },
        useBuiltIns: false,
        corejs: 2
      },
    ],
  ];

  const plugins = [
  ];

  return {
    presets,
    plugins
  };
}