var mochaConstants = {
    "MOCHA_UI": 'bdd',
    "MOCHA_REPORTER": 'html',
    "GLOBAL_SCRIPT": 'script*',
    "GLOBAL_JQUERY": 'jquery*'
}

    function mochaStart() {
        mocha.ui(mochaConstants.MOCHA_UI);
        mocha.reporter(mochaConstants.MOCHA_REPORTER);
        expect = chai.expect;
    }

    function mochaSetup() {
        mocha.setup({
            ui: mochaConstants.MOCHA_UI,
            ignoreLeaks: true,
            globals: [mochaConstants.GLOBAL_SCRIPT, mochaConstants.GLOBAL_JQUERY]
        });
    }

    function chooseTestLauncher() {
        if (window.mochaPhantomJS) {
            mochaPhantomJS.run();
        } else {
            mocha.run();
        }
    }
