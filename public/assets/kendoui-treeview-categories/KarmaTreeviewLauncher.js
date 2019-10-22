var karmaTest = {
    "KARMA_UI": "tdd",
    "TEST_FRAMEWORK": "mocha",
    "ASSERSTION_LIBRARY": "chai",
    "KARMA_REPORTERS": "progress",
    "BROWSER_CHROME": "Chrome",
    "BROWSER_FIREFOX": "Firefox",
    "BROWSER_PHANTOMJS": "PhantomJS",
    "EMPTY_STRING": "",
    "KARMA_PORT": 9876
}
module.exports = function (config) {
    config.set({
        client: {
            mocha: {
                ui: karmaTest.KARMA_UI
            }
        },
        basePath: karmaTest.EMPTY_STRING,
        frameworks: [karmaTest.TEST_FRAMEWORK, karmaTest.ASSERSTION_LIBRARY],
        files: [
            'node_modules/karma-jquery/jquery/jquery-2.1.0.js',
            'js/kendoui/tree/*.js',
            'test/lib/*.js',
            'node_modules/chai/chai.js'

        ],
        plugins: [
            'karma-mocha',
            'karma-chai',
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-jquery',
            'karma-phantomjs-launcher'
        ],
        exclude: [],
        preprocessors: {},
        reporters: [karmaTest.KARMA_REPORTERS],
        port: karmaTest.KARMA_PORT,
        colors: true,
        logLevel: config.LOG_INFO,
        autoWatch: true,
        browsers: [karmaTest.BROWSER_CHROME, karmaTest.BROWSER_FIREFOX, karmaTest.BROWSER_PHANTOMJS],
        singleRun: false,
    });
};
