/* eslint-disable @typescript-eslint/camelcase */

module.exports = {
  test_settings: {
    chrome: {
      desiredCapabilities: {
        chromeOptions: {
          args: ["--no-sandbox", "--disable-gpu"],
        },
      },
    },
  },
};
