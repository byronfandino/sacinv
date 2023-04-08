const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    baseUrl:'http://192.168.18.120:3000',
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
