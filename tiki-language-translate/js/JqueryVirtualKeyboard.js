/**
 * @todo constant are remaining in this file.
 * */
/**
 * This file is for configuration of display virtual keyboard at user side with
 * different language's characters.
 * */
var virtualLanguageConstants = {
    'ZERO_KEY_INDEX': 0,
    'CHECK_OBJECT_ARGUMENTS': '[object Arguments]',
    'CHECK_IS_FUNCTION': 'function',
    'CHECK_SIXTEENTH_KEY_PRESSED': 16,
    'CHECK_SHIFT_KEY_PRESSED': 'shift',
    'CHECK_OBJECT_ARRAY': '[object Array]',
    'KEYBOARD_DIV_ID': 'virtualKeyboard-tab',
    'DEFAULT_ID_VALUE': 'tab',
    'CHECK_KEYBOARD_CAPSLOACK': 'virtualKeyboard-capslock',
    'CAPSLOACK_VALUE': 'caps lock',
    'CHECK_KEYBOARD_RETURN': 'virtualKeyboard-return',
    'RETURN_VALUE': 'return',
    'KEYBOARD_THIRTEEN_KEY': 13,
    'FIRST_KEY_INDEX': 1,
    'KEYBOARD_SHIFT_KEY_VALUE': '-shift',
    'CHECK_KEYBOARD_SPACE': 'virtualKeyboard-space',
    'CHECK_BLANK_VARIABLE': ' ',
    'KEYBOARD_FIFTY_THIRD_KEY': 53,
    'KEYBOARD_OPEN_SPEED': 300,
    'KEYBOARD_CLOSED_SPEED': 100,
    'DEFAULT_KEYBOARD_LANGUAGE': 'en_us',
    'KEYBOARD_FOURTEENTH_KEY': 14,
    'KEYBOARD_TWENTY_EIGHT_KEY': 28,
    'KEYBOARD_FORTIETH_KEY': 40,
    'KEYBOARD_FORTY_ONE_KEY': 41,
    'KEYBOARD_FIFTY_ONE_KEY': 52,
    'CHECK_EMPTY_VARIABLE': '',
    'CHECK_INPUT_OPTION_FALSE': 'false',
    'CHECK_INPUT_OPTION_TRUE': 'true',
    'CHECK_INPUT_OPTION_DEFINE': 'undefined',
    'CHECK_INPUT_TAG': 'input',
    'SLICE_END_OFFSET': -1,
    'RETURN_VALUE_IN_INTEGER': 10,
    'MULTIPLY_WITH_WIDTH': 6,
    'DIVIDE_WITH_WIDTH': 2,
    'CHECK_KEYBOARD_BACKSPACE': 'virtualKeyboard-backspace',
    'BACKSPACE_VALUE': 'delete',
    'KEYBOARD_KEY_VALUE': 'virtualKeyboard-',
    'KEYBOARD_KEY_DATA_VALUE': 'data-virtualKeyboard-',
    'SHIFT_KEY_RIGHT_VALUE': 'right',
    'SHIFT_KEY_LEFT_VALUE': 'left',
    'CHECK_KEY_PRESSED': 'keypress',
    'ACTIVE_KEY': 'active',
    'CHEKC_CLICK_KEYBOARD': 'click.virtualKeyboard',
    'KEYUP_EVENT': 'keyup',
    'FIND_ID': 'id',
    'END_LIST_TAG': '<li/>',
    'END_DIV_TAG': '<div/>',
    'END_UL_TAG': '<ul/>',
    'TYPE_AREA_FOCUS': 'focus',
    'MOUSE_LEAVE_EVENT': 'mouseleave',
    'FIND_BODY_TAG_WITH_ADD_UPDATE_TRANSLATION_WINDOW':'body #addUpdateTranslationWindow',
    'ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID':'#addUpdateTranslationWindow',
    'VIRTUAL_KEYBOARD_LEFT_SHIFT':'#virtualKeyboard-left-shift',
    'VIRTUAL_KEYBOARD_RIGHT_SHIFT':'#virtualKeyboard-right-shift',
    'FIND_TEXTAREA':"textarea[id='textarea']",
    'FIND_ID':"id",
    'VIRTUAL_KEYBOARD':"virtualKeyboard",
    'CSS_DISPLAY_STYLE':'display',
    'CSS_DISPLAY_NONE':'none',
    'CSS_FLOAT_STYLE':"float",
    'CSS_FLOAT_LEFT':"left",
    'VIRTUAL_KEYBOARD_MODIFICATIONS':'virtualKeyboard-modifications',
    'NEW_TRANSLATION_INPUT_TEXTAREA_ID':'#newTranslationInputTextarea',
    'FIND_DISPLAY_VIRTUAL_KEYBOARD_TRANSLATION_SUBMIT_BUTTON_CLASS':'.displayVirtualKeyboard .translationSubmitButton',
    'CSS_PADDING_STYLE':'padding'

};
$jQuery(document).ready(function () {
    function getKeyboardKey(keyParameter) {
        if (Object.prototype.toString.call(keyParameter) == virtualLanguageConstants.CHECK_OBJECT_ARGUMENTS) {
            this.keyboardKeyValue = keyParameter[virtualLanguageConstants.ZERO_KEY_INDEX];
        } else {
            this.keyboardKeyValue = keyParameter;
        }
        this.$keyboardKey = $jQuery(virtualLanguageConstants.END_LIST_TAG);
        this.currentKeyboardKeyValue = null;
    }

    getKeyboardKey.prototype.keyboardRenderKey = function () {
        if (this.keyboardKeyId) {
            this.$keyboardKey.attr(virtualLanguageConstants.FIND_ID, this.keyboardKeyId);
        }
        return this.$keyboardKey;
    };

    getKeyboardKey.prototype.setCurrentValue = function () {
        var keyPreferences = this.preferences;
        if (this.keyboardKeyValue.upperRegister()) {
            this.currentKeyboardKeyValue = this.preferences.upperKey ? this.preferences.upperKey : this.defaultKeyboardKeyValue;
        } else {
            this.currentKeyboardKeyValue = this.preferences.defaultKey ? this.preferences.defaultKey : this.defaultKeyboardKeyValue;
        }

        $jQuery(virtualLanguageConstants.FIND_BODY_TAG_WITH_ADD_UPDATE_TRANSLATION_WINDOW).on(virtualLanguageConstants.KEYUP_EVENT, function (enteredText) {
            var specialTextSymbol = enteredText.keyCode;
            if (specialTextSymbol == virtualLanguageConstants.CHECK_SIXTEENTH_KEY_PRESSED) {
                if ($jQuery(virtualLanguageConstants.VIRTUAL_KEYBOARD_LEFT_SHIFT).hasClass(virtualLanguageConstants.ACTIVE_KEY) && $jQuery(virtualLanguageConstants.VIRTUAL_KEYBOARD_RIGHT_SHIFT).hasClass(virtualLanguageConstants.ACTIVE_KEY)) {
                    $jQuery(virtualLanguageConstants.VIRTUAL_KEYBOARD_LEFT_SHIFT).removeClass(virtualLanguageConstants.ACTIVE_KEY);
                    $jQuery(virtualLanguageConstants.VIRTUAL_KEYBOARD_RIGHT_SHIFT).removeClass(virtualLanguageConstants.ACTIVE_KEY);
                    this.currentKeyboardKeyValue = keyPreferences.defaultKey;
                } else {
                    $jQuery(virtualLanguageConstants.VIRTUAL_KEYBOARD_LEFT_SHIFT).addClass(virtualLanguageConstants.ACTIVE_KEY);
                    $jQuery(virtualLanguageConstants.VIRTUAL_KEYBOARD_RIGHT_SHIFT).addClass(virtualLanguageConstants.ACTIVE_KEY);
                    this.currentKeyboardKeyValue = keyPreferences.upperKey;
                    this.currentKeyboardKeyValue = virtualLanguageConstants.CHECK_SHIFT_KEY_PRESSED;
                }
            }
        });
        this.$keyboardKey.text(this.currentKeyboardKeyValue);
    };

    getKeyboardKey.prototype.setCurrentAction = function () {
        var currentKeyAction = this;
        this.$keyboardKey.unbind(virtualLanguageConstants.CHEKC_CLICK_KEYBOARD);
        this.$keyboardKey.bind(virtualLanguageConstants.CHEKC_CLICK_KEYBOARD, function () {
            currentKeyAction.keyboardKeyValue.currentKeyFocus = true;
            if (typeof (currentKeyAction.preferences.onClick) === virtualLanguageConstants.CHECK_IS_FUNCTION) {
                currentKeyAction.preferences.onClick(currentKeyAction);
            } else {
                currentKeyAction.defaultClickAction();
            }
        });
    };

    getKeyboardKey.prototype.defaultClickAction = function () {
        this.keyboardKeyValue.destroyModifications();
        if (this.is_modificator) {
            this.keyboardKeyValue.deleteCharacter();
            this.keyboardKeyValue.printCharacter(this.currentKeyboardKeyValue);
        } else {
            this.keyboardKeyValue.printCharacter(this.currentKeyboardKeyValue);
        }
        if (this.preferences.m && Object.prototype.toString.call(this.preferences.m) === virtualLanguageConstants.CHECK_OBJECT_ARRAY) {
            this.showModifications();
        }
        if (this.keyboardKeyValue.activeShiftKey) this.keyboardKeyValue.toggleShift(false);
    };

    getKeyboardKey.prototype.showModifications = function () {
        var currentKeyAction = this;
        this.keyboardKeyValue.modifications = [];

        $jQuery.each(this.preferences.m, function (keyboardCharacterKey, keyboardKeyModification) {
            var keyboardKeyValues = new getKeyboardKey(currentKeyAction.keyboardKeyValue);
            keyboardKeyValues.is_modificator = true;
            keyboardKeyValues.preferences = keyboardKeyModification;
            currentKeyAction.keyboardKeyValue.modifications.push(keyboardKeyValues);
        });
        this.keyboardKeyValue.showModifications(this);
    };

    getKeyboardKey.prototype.toggleActiveState = function () {
        if (this.isActive()) {
            this.$keyboardKey.addClass(virtualLanguageConstants.ACTIVE_KEY);
        } else {
            this.$keyboardKey.removeClass(virtualLanguageConstants.ACTIVE_KEY);
        }
    };

    getKeyboardKey.prototype.isActive = function () {
        return false;
    };

    function KeyDelete() {
        getKeyboardKey.call(this, arguments);
        this.keyboardKeyId = virtualLanguageConstants.CHECK_KEYBOARD_BACKSPACE;
        this.defaultKeyboardKeyValue = virtualLanguageConstants.BACKSPACE_VALUE;
    }

    KeyDelete.prototype = new getKeyboardKey();
    KeyDelete.prototype.constructor = KeyDelete;
    KeyDelete.prototype.defaultClickAction = function () {
        this.keyboardKeyValue.deleteCharacter();
    };

    function KeyTab() {
        getKeyboardKey.call(this, arguments);
        this.keyboardKeyId = virtualLanguageConstants.KEYBOARD_DIV_ID;
        this.defaultKeyboardKeyValue = virtualLanguageConstants.DEFAULT_ID_VALUE;
    }

    KeyTab.prototype = new getKeyboardKey();
    KeyTab.prototype.constructor = KeyTab;
    KeyTab.prototype.defaultClickAction = function () {
        this.keyboardKeyValue.$currentKeyboardInput.next(virtualLanguageConstants.FIND_TEXTAREA).focus();
    };

    function KeyCapsLock() {
        getKeyboardKey.call(this, arguments);
        this.keyboardKeyId = virtualLanguageConstants.CHECK_KEYBOARD_CAPSLOACK;
        this.defaultKeyboardKeyValue = virtualLanguageConstants.CAPSLOACK_VALUE;
    }

    KeyCapsLock.prototype = new getKeyboardKey();
    KeyCapsLock.prototype.constructor = KeyCapsLock;
    KeyCapsLock.prototype.isActive = function () {
        return this.keyboardKeyValue.activeCapsLockKey;
    };

    KeyCapsLock.prototype.defaultClickAction = function () {
        this.keyboardKeyValue.toggleCapsLock();
    };

    function KeyReturn() {
        getKeyboardKey.call(this, arguments);
        this.keyboardKeyId = virtualLanguageConstants.CHECK_KEYBOARD_RETURN;
        this.defaultKeyboardKeyValue = virtualLanguageConstants.RETURN_VALUE;
    }

    KeyReturn.prototype = new getKeyboardKey();
    KeyReturn.prototype.constructor = KeyReturn;
    KeyReturn.prototype.defaultClickAction = function () {
        var keyboardTriggerEvent = $jQuery.Event(virtualLanguageConstants.CHECK_KEY_PRESSED, {
            which: virtualLanguageConstants.KEYBOARD_THIRTEEN_KEY,
            keyCode: virtualLanguageConstants.KEYBOARD_THIRTEEN_KEY
        });
        this.keyboardKeyValue.$currentKeyboardInput.trigger(keyboardTriggerEvent);
    };

    function KeyShift() {
        getKeyboardKey.call(this, arguments);
        this.keyboardKeyId = virtualLanguageConstants.KEYBOARD_KEY_VALUE + arguments[virtualLanguageConstants.FIRST_KEY_INDEX] + virtualLanguageConstants.KEYBOARD_SHIFT_KEY_VALUE;
        this.defaultKeyboardKeyValue = virtualLanguageConstants.CHECK_SHIFT_KEY_PRESSED;
    }

    KeyShift.prototype = new getKeyboardKey();
    KeyShift.prototype.constructor = KeyShift;
    KeyShift.prototype.isActive = function () {
        return this.keyboardKeyValue.activeShiftKey;
    };

    KeyShift.prototype.defaultClickAction = function () {
        this.keyboardKeyValue.toggleShift();
    };

    function KeySpace() {
        getKeyboardKey.call(this, arguments);
        this.keyboardKeyId = virtualLanguageConstants.CHECK_KEYBOARD_SPACE;
        this.defaultKeyboardKeyValue = virtualLanguageConstants.CHECK_BLANK_VARIABLE;
    }

    KeySpace.prototype = new getKeyboardKey();
    KeySpace.prototype.constructor = KeySpace;
    var keyboardKeyCount = virtualLanguageConstants.KEYBOARD_FIFTY_THIRD_KEY;

    function keyboardKeySetup(keyboardOptions) {
        this.defaults = {
            layout: virtualLanguageConstants.DEFAULT_KEYBOARD_LANGUAGE,
            activeShiftKey: false,
            activeCapsLockKey: false,
            open_speed: virtualLanguageConstants.KEYBOARD_OPEN_SPEED,
            close_speed: virtualLanguageConstants.KEYBOARD_CLOSED_SPEED,
            enabled: true
        };

        this.keyboardGlobalOptions = $jQuery.extend({}, this.defaults, keyboardOptions);
        this.keyboardOptions = $jQuery.extend({}, {}, this.keyboardGlobalOptions);
        this.keyboardKeys = [];
        this.$keyboardKeyValue = $jQuery(virtualLanguageConstants.END_DIV_TAG).attr(virtualLanguageConstants.FIND_ID, virtualLanguageConstants.VIRTUAL_KEYBOARD);
        $jQuery(this.$keyboardKeyValue).css(virtualLanguageConstants.CSS_FLOAT_STYLE, virtualLanguageConstants.CSS_FLOAT_LEFT).css(virtualLanguageConstants.CSS_DISPLAY_STYLE, virtualLanguageConstants.CSS_DISPLAY_NONE);
        this.$keyboardModificationsHolder = $jQuery(virtualLanguageConstants.END_UL_TAG).addClass(virtualLanguageConstants.VIRTUAL_KEYBOARD_MODIFICATIONS);
        this.$currentKeyboardInput = $jQuery(virtualLanguageConstants.NEW_TRANSLATION_INPUT_TEXTAREA_ID);
    }

    keyboardKeySetup.prototype.initialKeyboardSetup = function () {
        this.$keyboardKeyValue.append(this.renderKeys());
        this.$keyboardKeyValue.append(this.$keyboardModificationsHolder);
        $jQuery(virtualLanguageConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).find(virtualLanguageConstants.FIND_DISPLAY_VIRTUAL_KEYBOARD_TRANSLATION_SUBMIT_BUTTON_CLASS).before(this.$keyboardKeyValue);
        this.setUpKeys();
    };

    keyboardKeySetup.prototype.setUpKeys = function () {
        var currentKeyAction = this;
        this.activeShiftKey = this.keyboardOptions.activeShiftKey;
        this.activeCapsLockKey = this.keyboardOptions.activeCapsLockKey;
        if(virtualKeyboard.layouts[currentKeyAction.keyboardOptions.layout]==undefined){
            currentKeyAction.keyboardOptions.layout=virtualLanguageConstants.DEFAULT_KEYBOARD_LANGUAGE;
        }
        
        $jQuery.each(this.keyboardKeys, function (keyboardCharacterKey, keyboardKeyValues) {
            keyboardKeyValues.preferences = virtualKeyboard.layouts[currentKeyAction.keyboardOptions.layout][keyboardCharacterKey];
            keyboardKeyValues.setCurrentValue();
            keyboardKeyValues.setCurrentAction();
            keyboardKeyValues.toggleActiveState();
        });
    };

    keyboardKeySetup.prototype.renderKeys = function () {
        var $keyHolder = $jQuery(virtualLanguageConstants.END_UL_TAG);

        for (var keyboardCharacterKey = virtualLanguageConstants.ZERO_KEY_INDEX; keyboardCharacterKey <= keyboardKeyCount; keyboardCharacterKey++) {
            var keyboardKeyValues;
            switch (keyboardCharacterKey) {
            case virtualLanguageConstants.KEYBOARD_THIRTEEN_KEY:
                keyboardKeyValues = new KeyDelete(this);
                break;
            case virtualLanguageConstants.KEYBOARD_FOURTEENTH_KEY:
                keyboardKeyValues = new KeyTab(this);
                break;
            case virtualLanguageConstants.KEYBOARD_TWENTY_EIGHT_KEY:
                keyboardKeyValues = new KeyCapsLock(this);
                break;
            case virtualLanguageConstants.KEYBOARD_FORTIETH_KEY:
                keyboardKeyValues = new KeyReturn(this);
                break;
            case virtualLanguageConstants.KEYBOARD_FORTY_ONE_KEY:
                keyboardKeyValues = new KeyShift(this, virtualLanguageConstants.SHIFT_KEY_LEFT_VALUE);
                break;
            case virtualLanguageConstants.KEYBOARD_FIFTY_ONE_KEY:
                keyboardKeyValues = new KeyShift(this, virtualLanguageConstants.SHIFT_KEY_RIGHT_VALUE);
                break;
            case virtualLanguageConstants.KEYBOARD_FIFTY_THIRD_KEY:
                keyboardKeyValues = new KeySpace(this);
                break;
            default:
                keyboardKeyValues = new getKeyboardKey(this);
                break;
            }
            this.keyboardKeys.push(keyboardKeyValues);
            $keyHolder.append(keyboardKeyValues.keyboardRenderKey());
        }

        return $keyHolder;
    };

    keyboardKeySetup.prototype.setUpKeyboardKey = function (keyboardKeyObject) {
        var currentKeyAction = this;
        keyboardKeyObject.bind(virtualLanguageConstants.TYPE_AREA_FOCUS, function () {
            var keyboardInputChanged = !currentKeyAction.$currentKeyboardInput || $jQuery(this)[virtualLanguageConstants.ZERO_KEY_INDEX] !== currentKeyAction.$currentKeyboardInput[virtualLanguageConstants.ZERO_KEY_INDEX];

            if (!currentKeyAction.currentKeyFocus || keyboardInputChanged) {
                if (keyboardInputChanged) currentKeyAction.currentKeyFocus = true;
                currentKeyAction.$currentKeyboardInput = $jQuery(this);
                currentKeyAction.keyboardOptions = $jQuery.extend({}, currentKeyAction.keyboardGlobalOptions, currentKeyAction.inputLocalOptions());
                if (!currentKeyAction.keyboardOptions.enabled) {
                    currentKeyAction.currentKeyFocus = false;
                    return;
                }
                if (currentKeyAction.$currentKeyboardInput.val() !== virtualLanguageConstants.CHECK_EMPTY_VARIABLE) {
                    currentKeyAction.keyboardOptions.activeShiftKey = false;
                }
                currentKeyAction.setUpKeys();
            }
        });
    };

    keyboardKeySetup.prototype.inputLocalOptions = function () {
        var keyboardOptions = {};
        for (var keyboardKeyValues in this.defaults) {
            var keyboardInputOption = this.$currentKeyboardInput.attr(virtualLanguageConstants.KEYBOARD_KEY_DATA_VALUE + keyboardKeyValues);
            if (keyboardInputOption == virtualLanguageConstants.CHECK_INPUT_OPTION_FALSE) {
                keyboardInputOption = false;
            } else if (keyboardInputOption == virtualLanguageConstants.CHECK_INPUT_OPTION_TRUE) {
                keyboardInputOption = true;
            }
            if (typeof keyboardInputOption !== virtualLanguageConstants.CHECK_INPUT_OPTION_DEFINE) {
                keyboardOptions[keyboardKeyValues] = keyboardInputOption;
            }
        }
        return keyboardOptions;
    };

    keyboardKeySetup.prototype.printCharacter = function (keyboardKeyCharacter) {
        var currentKeyValue = this.$currentKeyboardInput.val();
        this.$currentKeyboardInput.val(currentKeyValue + keyboardKeyCharacter);
        this.$currentKeyboardInput.focus().trigger(virtualLanguageConstants.CHECK_INPUT_TAG);
    };

    keyboardKeySetup.prototype.deleteCharacter = function () {
        var currentKeyValue = this.$currentKeyboardInput.val();
        this.$currentKeyboardInput.val(currentKeyValue.slice(virtualLanguageConstants.ZERO_KEY_INDEX, virtualLanguageConstants.SLICE_END_OFFSET));
        this.$currentKeyboardInput.focus().trigger(virtualLanguageConstants.CHECK_INPUT_TAG);
    };

    keyboardKeySetup.prototype.showModifications = function (keyboardModificationCall) {
        var currentKeyAction = this,
            keyHolderPadding = parseInt(currentKeyAction.$keyboardModificationsHolder.css(virtualLanguageConstants.CSS_PADDING_STYLE), virtualLanguageConstants.RETURN_VALUE_IN_INTEGER),
            keyTopPosition, keyLeftPosition, keyWidthPosition;

        $jQuery.each(this.modifications, function (keyboardCharacterKey, keyboardKeyValues) {
            currentKeyAction.$keyboardModificationsHolder.append(keyboardKeyValues.keyboardRenderKey());
            keyboardKeyValues.setCurrentValue();
            keyboardKeyValues.setCurrentAction();
        });

        keyWidthPosition = (keyboardModificationCall.$keyboardKey.keyWidthPosition() * currentKeyAction.modifications.length) + (currentKeyAction.modifications.length * virtualLanguageConstants.MULTIPLY_WITH_WIDTH);
        keyTopPosition = keyboardModificationCall.$keyboardKey.position().keyTopPosition - keyHolderPadding;
        keyLeftPosition = keyboardModificationCall.$keyboardKey.position().keyLeftPosition - currentKeyAction.modifications.length * keyboardModificationCall.$keyboardKey.keyWidthPosition() / virtualLanguageConstants.DIVIDE_WITH_WIDTH;

        this.$keyboardModificationsHolder.one(virtualLanguageConstants.MOUSE_LEAVE_EVENT, function () {
            currentKeyAction.destroyModifications();
        });

        this.$keyboardModificationsHolder.css({
            width: keyWidthPosition,
            top: keyTopPosition,
            left: keyLeftPosition
        }).show();
    };

    keyboardKeySetup.prototype.destroyModifications = function () {
        this.$keyboardModificationsHolder.empty().hide();
    };

    keyboardKeySetup.prototype.upperRegister = function () {
        return ((this.activeShiftKey && !this.activeCapsLockKey) || (!this.activeShiftKey && this.activeCapsLockKey));
    };

    keyboardKeySetup.prototype.toggleShift = function (keyboardKeyState) {
        this.activeShiftKey = keyboardKeyState ? keyboardKeyState : !this.activeShiftKey;
        this.changeKeysState();
    };

    keyboardKeySetup.prototype.toggleCapsLock = function (keyboardKeyState) {
        this.activeCapsLockKey = keyboardKeyState ? keyboardKeyState : !this.activeCapsLockKey;
        this.changeKeysState();
    };

    keyboardKeySetup.prototype.changeKeysState = function () {
        $jQuery.each(this.keyboardKeys, function (keyboardKey, keyboardKeyValues) {
            keyboardKeyValues.setCurrentValue();
            keyboardKeyValues.toggleActiveState();
        });
    };

    $jQuery.fn.virtualKeyboard = function (keyboardOptions) {
        var keyboardKeyValue = new keyboardKeySetup(keyboardOptions);
        keyboardKeyValue.initialKeyboardSetup();

        this.each(function () {
            keyboardKeyValue.setUpKeyboardKey($jQuery(this));
        });
    };
});

/**
 * English[en_us] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.en_us = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: ':',
        defaultKey: ';'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Arabic(Saudi Arabia)[ar_sa] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ar_sa = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'q',
        defaultKey: 'ض'
    }, {
        upperKey: 'w',
        defaultKey: 'ص'
    }, {
        upperKey: 'e',
        defaultKey: 'ث'
    }, {
        upperKey: 'r',
        defaultKey: 'ق'
    }, {
        upperKey: 't',
        defaultKey: 'ف'
    }, {
        upperKey: 'y',
        defaultKey: 'غ'
    }, {
        upperKey: 'u',
        defaultKey: 'ع'
    }, {
        upperKey: 'i',
        defaultKey: 'ه'
    }, {
        upperKey: 'o',
        defaultKey: 'خ'
    }, {
        upperKey: 'p',
        defaultKey: 'ح'
    }, {
        upperKey: ']',
        defaultKey: 'ج'
    }, {
        upperKey: '[',
        defaultKey: 'د'
    }, {
        upperKey: '\\',
        defaultKey: '|'
    }, {}, // Caps lock
    {
        upperKey: 'a',
        defaultKey: 'ش'
    }, {
        upperKey: 's',
        defaultKey: 'س'
    }, {
        upperKey: 'd',
        defaultKey: 'ي'
    }, {
        upperKey: 'f',
        defaultKey: 'ب'
    }, {
        upperKey: 'g',
        defaultKey: 'ل'
    }, {
        upperKey: 'h',
        defaultKey: 'ا'
    }, {
        upperKey: 'j',
        defaultKey: 'ت'
    }, {
        upperKey: 'k',
        defaultKey: 'ت'
    }, {
        upperKey: 'l',
        defaultKey: 'م'
    }, {
        upperKey: ';',
        defaultKey: 'ك'
    }, {
        upperKey: '\'',
        defaultKey: 'ط'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'z',
        defaultKey: 'ئ'
    }, {
        upperKey: 'x',
        defaultKey: 'ء'
    }, {
        upperKey: 'c',
        defaultKey: 'ؤ'
    }, {
        upperKey: 'v',
        defaultKey: 'ر'
    }, {
        upperKey: 'b',
        defaultKey: 'ا'
    }, {
        upperKey: 'n',
        defaultKey: 'ى'
    }, {
        upperKey: 'm',
        defaultKey: 'ة'
    }, {
        upperKey: ',',
        defaultKey: 'و'
    }, {
        upperKey: '.',
        defaultKey: 'ز'
    }, {
        upperKey: '/',
        defaultKey: 'ظ'
    }, {}, // Right shift
    {} // Space
];

/**
 * Bulgarian[bg_bg] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.bg_bg = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'ы',
        defaultKey: ','
    }, {
        upperKey: 'У',
        defaultKey: 'у'
    }, {
        upperKey: 'Е',
        defaultKey: 'е'
    }, {
        upperKey: 'И',
        defaultKey: 'и'
    }, {
        upperKey: 'Ш',
        defaultKey: 'ш'
    }, {
        upperKey: 'Щ',
        defaultKey: 'щ'
    }, {
        upperKey: 'К',
        defaultKey: 'к'
    }, {
        upperKey: 'С',
        defaultKey: 'с'
    }, {
        upperKey: 'Д',
        defaultKey: 'д'
    }, {
        upperKey: 'З',
        defaultKey: 'з'
    }, {
        upperKey: 'Ц',
        defaultKey: 'ц'
    }, {
        upperKey: '§',
        defaultKey: ';'
    }, {
        upperKey: ')',
        defaultKey: '('
    }, {}, // Caps lock
    {
        upperKey: 'Ь',
        defaultKey: 'ь'
    }, {
        upperKey: 'Я',
        defaultKey: 'я'
    }, {
        upperKey: 'А',
        defaultKey: 'а'
    }, {
        upperKey: 'О',
        defaultKey: 'о'
    }, {
        upperKey: 'Ж',
        defaultKey: 'ж'
    }, {
        upperKey: 'Г',
        defaultKey: 'г'
    }, {
        upperKey: 'Т',
        defaultKey: 'т'
    }, {
        upperKey: 'Н',
        defaultKey: 'н'
    }, {
        upperKey: 'В',
        defaultKey: 'в'
    }, {
        upperKey: 'М',
        defaultKey: 'м'
    }, {
        upperKey: 'Ч',
        defaultKey: 'ч'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Ю',
        defaultKey: 'ю'
    }, {
        upperKey: 'Й',
        defaultKey: 'й'
    }, {
        upperKey: 'Ъ',
        defaultKey: 'ъ'
    }, {
        upperKey: 'Э',
        defaultKey: 'э'
    }, {
        upperKey: 'Ф',
        defaultKey: 'ф'
    }, {
        upperKey: 'Х',
        defaultKey: 'х'
    }, {
        upperKey: 'П',
        defaultKey: 'п'
    }, {
        upperKey: 'Р',
        defaultKey: 'р'
    }, {
        upperKey: 'Л',
        defaultKey: 'л'
    }, {
        upperKey: 'Б',
        defaultKey: 'б'
    }, {}, // Right shift
    {} // Space
];

/**
 * Russian[ru_ru] keyboard characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ru_ru = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Й',
        defaultKey: 'й'
    }, {
        upperKey: 'Ц',
        defaultKey: 'ц'
    }, {
        upperKey: 'У',
        defaultKey: 'у'
    }, {
        upperKey: 'К',
        defaultKey: 'к'
    }, {
        upperKey: 'Е',
        defaultKey: 'е'
    }, {
        upperKey: 'Н',
        defaultKey: 'н'
    }, {
        upperKey: 'Г',
        defaultKey: 'г'
    }, {
        upperKey: 'Ш',
        defaultKey: 'ш'
    }, {
        upperKey: 'Щ',
        defaultKey: 'щ'
    }, {
        upperKey: 'З',
        defaultKey: 'з'
    }, {
        upperKey: 'Х',
        defaultKey: 'х'
    }, {
        upperKey: 'Ъ',
        defaultKey: 'ъ'
    }, {
        upperKey: '\\',
        defaultKey: '|'
    }, {}, // Caps Lock
    {
        upperKey: 'Ф',
        defaultKey: 'ф'
    }, {
        upperKey: 'Ы',
        defaultKey: 'ы'
    }, {
        upperKey: 'В',
        defaultKey: 'в'
    }, {
        upperKey: 'А',
        defaultKey: 'а'
    }, {
        upperKey: 'П',
        defaultKey: 'п'
    }, {
        upperKey: 'Р',
        defaultKey: 'р'
    }, {
        upperKey: 'О',
        defaultKey: 'о'
    }, {
        upperKey: 'Л',
        defaultKey: 'л'
    }, {
        upperKey: 'Д',
        defaultKey: 'д'
    }, {
        upperKey: 'Ж',
        defaultKey: 'ж'
    }, {
        upperKey: 'Э',
        defaultKey: 'э'
    }, {}, // Return
    {}, // Left Shift
    {
        upperKey: 'Я',
        defaultKey: 'я'
    }, {
        upperKey: 'Ч',
        defaultKey: 'ч'
    }, {
        upperKey: 'С',
        defaultKey: 'с'
    }, {
        upperKey: 'М',
        defaultKey: 'м'
    }, {
        upperKey: 'И',
        defaultKey: 'и'
    }, {
        upperKey: 'Т',
        defaultKey: 'т'
    }, {
        upperKey: 'Ь',
        defaultKey: 'ь'
    }, {
        upperKey: 'Б',
        defaultKey: 'б'
    }, {
        upperKey: 'Ю',
        defaultKey: 'ю'
    }, {
        upperKey: ',',
        defaultKey: '.'
    }, {}, // Right Shift
    {} // Space
];

/**
 * Chinese(Simplified)[zh_cn] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.zh_cn = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'q',
        defaultKey: '手'
    }, {
        upperKey: 'w',
        defaultKey: '田'
    }, {
        upperKey: 'e',
        defaultKey: '水'
    }, {
        upperKey: 'r',
        defaultKey: '口'
    }, {
        upperKey: 't',
        defaultKey: '廿'
    }, {
        upperKey: 'y',
        defaultKey: '卜'
    }, {
        upperKey: 'u',
        defaultKey: '山'
    }, {
        upperKey: 'i',
        defaultKey: '戈'
    }, {
        upperKey: 'o',
        defaultKey: '人'
    }, {
        upperKey: 'p',
        defaultKey: '心'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'a',
        defaultKey: '日'
    }, {
        upperKey: 's',
        defaultKey: '尸'
    }, {
        upperKey: 'd',
        defaultKey: '木'
    }, {
        upperKey: 'f',
        defaultKey: '火'
    }, {
        upperKey: 'g',
        defaultKey: '土'
    }, {
        upperKey: 'h',
        defaultKey: '竹'
    }, {
        upperKey: 'j',
        defaultKey: '十'
    }, {
        upperKey: 'k',
        defaultKey: '大'
    }, {
        upperKey: 'l',
        defaultKey: '中'
    }, {
        upperKey: ':',
        defaultKey: ';'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'z',
        defaultKey: '重'
    }, {
        upperKey: 'x',
        defaultKey: '内'
    }, {
        upperKey: 'c',
        defaultKey: '金'
    }, {
        upperKey: 'v',
        defaultKey: '女'
    }, {
        upperKey: 'b',
        defaultKey: '月'
    }, {
        upperKey: 'n',
        defaultKey: '弓'
    }, {
        upperKey: 'm',
        defaultKey: '一'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Spanish(Spain)[es_es] keyboard characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.es_es = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'º',
        defaultKey: '´'
    }, {
        upperKey: '¨',
        defaultKey: '`'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ñ',
        defaultKey: 'ñ'
    }, {
        upperKey: ':',
        defaultKey: ';'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '¿',
        defaultKey: ','
    }, {
        upperKey: '?',
        defaultKey: '.'
    }, {
        upperKey: 'Ç',
        defaultKey: 'ç'
    }, {}, // Right shift
    {} // Space
];

/**
 * Catalan[ca_es] keyboard characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ca_es = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '`',
        defaultKey: '^'
    }, {
        upperKey: '+',
        defaultKey: '*'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ñ',
        defaultKey: 'ñ'
    }, {
        upperKey: 'Ç',
        defaultKey: 'ç'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Czech[cs_cz] keyboard characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.cs_cz = [{
        upperKey: '°',
        defaultKey: ';'
    }, {
        upperKey: '1',
        defaultKey: '+'
    }, {
        upperKey: '2',
        defaultKey: 'ě'
    }, {
        upperKey: '3',
        defaultKey: 'š'
    }, {
        upperKey: '4',
        defaultKey: 'č'
    }, {
        upperKey: '5',
        defaultKey: 'ř'
    }, {
        upperKey: '6',
        defaultKey: 'ž'
    }, {
        upperKey: '7',
        defaultKey: 'ý'
    }, {
        upperKey: '8',
        defaultKey: 'á'
    }, {
        upperKey: '9',
        defaultKey: 'í'
    }, {
        upperKey: '0',
        defaultKey: 'é'
    }, {
        upperKey: '=',
        defaultKey: '%'
    }, {
        upperKey: '´',
        defaultKey: 'ˇ'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '/',
        defaultKey: 'ú'
    }, {
        upperKey: '(',
        defaultKey: ')'
    }, {
        upperKey: '\'',
        defaultKey: '¨'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: '"',
        defaultKey: 'ů'
    }, {
        upperKey: '§',
        defaultKey: '!'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Danish[da_dk] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.da_dk = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Å',
        defaultKey: 'å'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Æ',
        defaultKey: 'æ'
    }, {
        upperKey: 'Ø',
        defaultKey: 'ø'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * French(France)[fr_fr] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.fr_fr = [{
        upperKey: '§',
        defaultKey: '²'
    }, {
        upperKey: '1',
        defaultKey: '&'
    }, {
        upperKey: '2',
        defaultKey: 'é'
    }, {
        upperKey: '3',
        defaultKey: '"'
    }, {
        upperKey: '4',
        defaultKey: '\''
    }, {
        upperKey: '5',
        defaultKey: '('
    }, {
        upperKey: '6',
        defaultKey: '-'
    }, {
        upperKey: 'è',
        defaultKey: '7'
    }, {
        upperKey: '8',
        defaultKey: '_'
    }, {
        upperKey: '9',
        defaultKey: 'ç'
    }, {
        upperKey: '0',
        defaultKey: 'à'
    }, {
        upperKey: '+',
        defaultKey: ')'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '¨',
        defaultKey: '^'
    }, {
        upperKey: '£',
        defaultKey: '$'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '%',
        defaultKey: 'ù'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: '?',
        defaultKey: ','
    }, {
        upperKey: '.',
        defaultKey: ';'
    }, {
        upperKey: '/',
        defaultKey: ':'
    }, {
        upperKey: '§',
        defaultKey: '!'
    }, {}, // Right shift
    {} // Space
];

/**
 * German(Germany)[de_de] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.de_de = [{
        upperKey: '°',
        defaultKey: '^'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '·',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '&',
        defaultKey: '6'
    }, {
        upperKey: '/',
        defaultKey: '7'
    }, {
        upperKey: '(',
        defaultKey: '8'
    }, {
        upperKey: ')',
        defaultKey: '9'
    }, {
        upperKey: '=',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '\''
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Ü',
        defaultKey: 'ü'
    }, {
        upperKey: '+',
        defaultKey: '*'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ö',
        defaultKey: 'ö'
    }, {
        upperKey: 'Ä',
        defaultKey: 'ä'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: '>',
        defaultKey: '<'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: ';',
        defaultKey: ','
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {}, // Right shift
    {} // Space
];

/**
 * Galician[gl_es] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.gl_es = [{
        upperKey: 'ª',
        defaultKey: 'º'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '·',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '&',
        defaultKey: '6'
    }, {
        upperKey: '/',
        defaultKey: '7'
    }, {
        upperKey: '(',
        defaultKey: '8'
    }, {
        upperKey: ')',
        defaultKey: '9'
    }, {
        upperKey: '=',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '\''
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '`',
        defaultKey: '^'
    }, {
        upperKey: '+',
        defaultKey: '*'
    }, {
        upperKey: 'Ç',
        defaultKey: 'ç'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ñ',
        defaultKey: 'ñ'
    }, {
        upperKey: '¨',
        defaultKey: '´'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: ';',
        defaultKey: ','
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {}, // Right shift
    {} // Space
];

/**
 * Hungarian[hu_hu] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.hu_hu = [{
        upperKey: 'Í',
        defaultKey: 'í'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: 'Ö',
        defaultKey: 'ö'
    }, {
        upperKey: 'Ü',
        defaultKey: 'ü'
    }, {
        upperKey: 'Ó',
        defaultKey: 'ó'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Ő',
        defaultKey: 'ő'
    }, {
        upperKey: 'Ú',
        defaultKey: 'ú'
    }, {
        upperKey: 'Ű',
        defaultKey: 'ű'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'É',
        defaultKey: 'é'
    }, {
        upperKey: 'Á',
        defaultKey: 'á'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '?',
        defaultKey: ','
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {}, // Right shift
    {} // Space
];

/**
 * Hebrew[he_il] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.he_il = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: '/'
    }, {
        upperKey: 'W',
        defaultKey: '\''
    }, {
        upperKey: 'E',
        defaultKey: 'ק'
    }, {
        upperKey: 'R',
        defaultKey: 'ר'
    }, {
        upperKey: 'T',
        defaultKey: 'א'
    }, {
        upperKey: 'Y',
        defaultKey: 'ט'
    }, {
        upperKey: 'U',
        defaultKey: 'ו'
    }, {
        upperKey: 'I',
        defaultKey: 'ן'
    }, {
        upperKey: 'O',
        defaultKey: 'ם'
    }, {
        upperKey: 'P',
        defaultKey: 'פ'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'ש'
    }, {
        upperKey: 'S',
        defaultKey: 'ד'
    }, {
        upperKey: 'D',
        defaultKey: 'ג'
    }, {
        upperKey: 'F',
        defaultKey: 'כ'
    }, {
        upperKey: 'G',
        defaultKey: 'ע'
    }, {
        upperKey: 'H',
        defaultKey: 'י'
    }, {
        upperKey: 'J',
        defaultKey: 'ח'
    }, {
        upperKey: 'K',
        defaultKey: 'ל'
    }, {
        upperKey: 'L',
        defaultKey: 'ך'
    }, {
        upperKey: ':',
        defaultKey: 'ף'
    }, {
        upperKey: '"',
        defaultKey: ','
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'ז'
    }, {
        upperKey: 'X',
        defaultKey: 'ס'
    }, {
        upperKey: 'C',
        defaultKey: 'ב'
    }, {
        upperKey: 'V',
        defaultKey: 'ה'
    }, {
        upperKey: 'B',
        defaultKey: 'נ'
    }, {
        upperKey: 'N',
        defaultKey: 'מ'
    }, {
        upperKey: 'M',
        defaultKey: 'צ'
    }, {
        upperKey: '<',
        defaultKey: 'ת'
    }, {
        upperKey: '>',
        defaultKey: 'ץ'
    }, {
        upperKey: '?',
        defaultKey: '.'
    }, {}, // Right shift
    {} // Space
];

/**
 * Italian(Italy)[it_it] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.it_it = [{
        upperKey: '\\',
        defaultKey: '|'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '·',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '&',
        defaultKey: '6'
    }, {
        upperKey: '/',
        defaultKey: '7'
    }, {
        upperKey: '(',
        defaultKey: '8'
    }, {
        upperKey: ')',
        defaultKey: '9'
    }, {
        upperKey: '=',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '\''
    }, {
        upperKey: '^',
        defaultKey: '+'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'è',
        defaultKey: 'é'
    }, {
        upperKey: '+',
        defaultKey: '*'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'ç',
        defaultKey: 'ò'
    }, {
        upperKey: '°',
        defaultKey: 'à'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];


/**
 * Japanese[ja_jp] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ja_jp = [{
        upperKey: '~',
        defaultKey: 'ろ'
    }, {
        upperKey: '!',
        defaultKey: 'ぬ'
    }, {
        upperKey: 'ぁ',
        defaultKey: 'ふ'
    }, {
        upperKey: 'ぅ',
        defaultKey: 'あ'
    }, {
        upperKey: 'ぇ',
        defaultKey: 'う'
    }, {
        upperKey: 'ぉ',
        defaultKey: 'え'
    }, {
        upperKey: 'ゃ',
        defaultKey: 'お'
    }, {
        upperKey: 'ゅ',
        defaultKey: 'や'
    }, {
        upperKey: 'ょ',
        defaultKey: 'ゆ'
    }, {
        upperKey: 'を',
        defaultKey: 'よ'
    }, {
        upperKey: 'ー',
        defaultKey: 'わ'
    }, {
        upperKey: '_',
        defaultKey: 'ほ'
    }, {
        upperKey: '+',
        defaultKey: 'へ'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'た'
    }, {
        upperKey: 'W',
        defaultKey: 'て'
    }, {
        upperKey: 'ぃ',
        defaultKey: 'い'
    }, {
        upperKey: 'R',
        defaultKey: 'す'
    }, {
        upperKey: 'T',
        defaultKey: 'か'
    }, {
        upperKey: 'Y',
        defaultKey: 'ん'
    }, {
        upperKey: 'U',
        defaultKey: 'な'
    }, {
        upperKey: 'I',
        defaultKey: 'に'
    }, {
        upperKey: 'O',
        defaultKey: 'ら'
    }, {
        upperKey: 'P',
        defaultKey: 'ぜ'
    }, {
        upperKey: '「',
        defaultKey: '゜'
    }, {
        upperKey: '」',
        defaultKey: 'む'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'ち'
    }, {
        upperKey: 'S',
        defaultKey: 'と'
    }, {
        upperKey: 'D',
        defaultKey: 'し'
    }, {
        upperKey: 'F',
        defaultKey: 'は'
    }, {
        upperKey: 'G',
        defaultKey: 'き'
    }, {
        upperKey: 'H',
        defaultKey: 'く'
    }, {
        upperKey: 'J',
        defaultKey: 'ま'
    }, {
        upperKey: 'K',
        defaultKey: 'の'
    }, {
        upperKey: 'L',
        defaultKey: 'り'
    }, {
        upperKey: ':',
        defaultKey: 'れ'
    }, {
        upperKey: '"',
        defaultKey: 'け'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'っ',
        defaultKey: 'む'
    }, {
        upperKey: 'X',
        defaultKey: 'つ'
    }, {
        upperKey: 'C',
        defaultKey: 'さ'
    }, {
        upperKey: 'V',
        defaultKey: 'そ'
    }, {
        upperKey: 'B',
        defaultKey: 'ひ'
    }, {
        upperKey: 'N',
        defaultKey: 'こ'
    }, {
        upperKey: 'M',
        defaultKey: 'み'
    }, {
        upperKey: '、',
        defaultKey: 'も'
    }, {
        upperKey: '。',
        defaultKey: 'ね'
    }, {
        upperKey: 'め',
        defaultKey: 'る'
    }, {}, // Right shift
    {} // Space
];

/**
 * Norwegian[nb_no] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.nb_no = [{
        upperKey: '~',
        defaultKey: '|'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '&',
        defaultKey: '6'
    }, {
        upperKey: '/',
        defaultKey: '7'
    }, {
        upperKey: '(',
        defaultKey: '8'
    }, {
        upperKey: ')',
        defaultKey: '9'
    }, {
        upperKey: '=',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '+'
    }, {
        upperKey: '`',
        defaultKey: '\\'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Å',
        defaultKey: 'å'
    }, {
        upperKey: '^',
        defaultKey: '¨'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ø',
        defaultKey: 'ø'
    }, {
        upperKey: 'Æ',
        defaultKey: 'æ'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: '<',
        defaultKey: '>'
    }, {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: ';',
        defaultKey: ','
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {}, // Right shift
    {} // Space
];

/**
 * Turkish[tr_tr] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.tr_tr = [{
        upperKey: 'é',
        defaultKey: '"'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Ğ',
        defaultKey: 'ğ'
    }, {
        upperKey: 'Ü',
        defaultKey: 'ü'
    }, {
        upperKey: ',',
        defaultKey: ';'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ş',
        defaultKey: 'ş'
    }, {
        upperKey: 'İ',
        defaultKey: 'i'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: 'Ö',
        defaultKey: 'ö'
    }, {
        upperKey: 'Ç',
        defaultKey: 'ç'
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {}, // Right shift
    {} // Space
];

/**
 * Estonian[et_ee] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.et_ee = [{
        upperKey: '°',
        defaultKey: 'ˇ'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '·',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '&',
        defaultKey: '6'
    }, {
        upperKey: '/',
        defaultKey: '7'
    }, {
        upperKey: '(',
        defaultKey: '8'
    }, {
        upperKey: ')',
        defaultKey: '9'
    }, {
        upperKey: '=',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '+'
    }, {
        upperKey: '`',
        defaultKey: '´'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Ü',
        defaultKey: 'ü'
    }, {
        upperKey: 'Õ',
        defaultKey: 'õ'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ö',
        defaultKey: 'ö'
    }, {
        upperKey: 'Ä',
        defaultKey: 'ä'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: '>',
        defaultKey: '<'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: ';',
        defaultKey: ','
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {}, // Right shift
    {} // Space
];

/**
 * Greek[el_gr] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.el_gr = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    { 
        upperKey: ':',
        defaultKey: ';'
    }, {
        upperKey: '΅',
        defaultKey: 'ς'
    }, {
        upperKey: 'Ε',
        defaultKey: 'ε'
    }, {
        upperKey: 'Ρ',
        defaultKey: 'ρ'
    }, {
        upperKey: 'Τ',
        defaultKey: 'τ'
    }, {
        upperKey: 'Υ',
        defaultKey: 'υ'
    }, {
        upperKey: 'Θ',
        defaultKey: 'θ'
    }, {
        upperKey: 'Ι',
        defaultKey: 'ι'
    }, {
        upperKey: 'Ο',
        defaultKey: 'ο'
    }, {
        upperKey: 'Π',
        defaultKey: 'π'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'Α',
        defaultKey: 'α'
    }, {
        upperKey: 'Σ',
        defaultKey: 'σ'
    }, {
        upperKey: 'Δ',
        defaultKey: 'δ'
    }, {
        upperKey: 'Φ',
        defaultKey: 'φ'
    }, {
        upperKey: 'Γ',
        defaultKey: 'γ'
    }, {
        upperKey: 'Η',
        defaultKey: 'η'
    }, {
        upperKey: 'Ξ',
        defaultKey: 'ξ'
    }, {
        upperKey: 'Κ',
        defaultKey: 'κ'
    }, {
        upperKey: 'Λ',
        defaultKey: 'λ'
    }, {
        upperKey: '¨',
        defaultKey: '΄'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Ζ',
        defaultKey: 'ζ'
    }, {
        upperKey: 'Χ',
        defaultKey: 'χ'
    }, {
        upperKey: 'Ψ',
        defaultKey: 'ψ'
    }, {
        upperKey: 'Ω',
        defaultKey: 'ω'
    }, {
        upperKey: 'Β',
        defaultKey: 'β'
    }, {
        upperKey: 'Ν',
        defaultKey: 'ν'
    }, {
        upperKey: 'Μ',
        defaultKey: 'μ'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Hindi[hi_in] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.hi_in = [{
        upperKey: 'ऒ',
        defaultKey: 'ॊृ'
    }, {
        upperKey: 'ऍ',
        defaultKey: '1'
    }, {
        upperKey: 'ॅ',
        defaultKey: '2'
    }, {
        upperKey: '्',
        defaultKey: '3'
    }, {
        upperKey: 'र्',
        defaultKey: '4'
    }, {
        upperKey: 'ज्ञ',
        defaultKey: '5'
    }, {
        upperKey: 'त्र',
        defaultKey: '6'
    }, {
        upperKey: 'क्ष',
        defaultKey: '7'
    }, {
        upperKey: 'श्र',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: 'ः',
        defaultKey: '-'
    }, {
        upperKey: 'ऋ',
        defaultKey: 'ृ'
    }, {}, // Delete
    {}, // Tab
    { 
        upperKey: 'औ',
        defaultKey: 'ौ'
    }, {
        upperKey: 'ऐ',
        defaultKey: 'ै'
    }, {
        upperKey: 'आ',
        defaultKey: 'ा'
    }, {
        upperKey: 'ई',
        defaultKey: 'ी'
    }, {
        upperKey: 'ऊ',
        defaultKey: 'ू'
    }, {
        upperKey: 'भ',
        defaultKey: 'ब'
    }, {
        upperKey: 'ङ',
        defaultKey: 'ह'
    }, {
        upperKey: 'घ',
        defaultKey: 'ग'
    }, {
        upperKey: 'ध',
        defaultKey: 'द'
    }, {
        upperKey: 'झ',
        defaultKey: 'ज'
    }, {
        upperKey: 'ढ',
        defaultKey: 'ड'
    }, {
        upperKey: 'ञ',
        defaultKey: '़'
    }, {
        upperKey: 'ऑ',
        defaultKey: 'ॉ'
    }, {}, // Caps lock
    {
        upperKey: 'ओ',
        defaultKey: 'ो'
    }, {
        upperKey: 'ए',
        defaultKey: 'े'
    }, {
        upperKey: 'अ',
        defaultKey: '्'
    }, {
        upperKey: 'इ',
        defaultKey: 'ि'
    }, {
        upperKey: 'उ',
        defaultKey: 'ु'
    }, {
        upperKey: 'फ',
        defaultKey: 'प'
    }, {
        upperKey: 'ऱ',
        defaultKey: 'र'
    }, {
        upperKey: 'ख',
        defaultKey: 'क'
    }, {
        upperKey: 'थ',
        defaultKey: 'त'
    }, {
        upperKey: 'छ',
        defaultKey: 'च'
    }, {
        upperKey: 'ठ',
        defaultKey: 'ट'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'ऎ',
        defaultKey: 'ॆ'
    }, {
        upperKey: 'ँ',
        defaultKey: 'ं'
    }, {
        upperKey: 'ण',
        defaultKey: 'म'
    }, {
        upperKey: 'ऩ',
        defaultKey: 'न'
    }, {
        upperKey: 'ऴ',
        defaultKey: 'व'
    }, {
        upperKey: 'ळ',
        defaultKey: 'ल'
    }, {
        upperKey: 'श',
        defaultKey: 'स'
    }, {
        upperKey: 'ष',
        defaultKey: ','
    }, {
        upperKey: '|',
        defaultKey: '.'
    }, {
        upperKey: 'य़',
        defaultKey: 'य'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Hmong Daw[mww_mww] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.mww_mww = [{
        upperKey: '\'',
        defaultKey: '\"'
    }, {
        upperKey: '1',
        defaultKey: 'ຢ'
    }, {
        upperKey: '2',
        defaultKey: 'ຟ'
    }, {
        upperKey: '3',
        defaultKey: 'ໂ'
    }, {
        upperKey: '4',
        defaultKey: 'ຖ'
    }, {
        upperKey: '໌',
        defaultKey: 'ຸ'
    }, {
        upperKey: 'ຼ',
        defaultKey: 'ູ'
    }, {
        upperKey: '5',
        defaultKey: 'ຄ'
    }, {
        upperKey: '6',
        defaultKey: 'ຕ'
    }, {
        upperKey: '7',
        defaultKey: 'ຈ'
    }, {
        upperKey: '8',
        defaultKey: 'ຂ'
    }, {
        upperKey: '9',
        defaultKey: 'ຊ'
    }, {
        upperKey: 'ໍ່',
        defaultKey: 'ໍ'
    }, {}, // Delete
    {}, // Tab
    { 
        upperKey: 'ົ້',
        defaultKey: 'ົ'
    }, {
        upperKey: '0',
        defaultKey: 'ໄ'
    }, {
        upperKey: '*',
        defaultKey: 'ຳ'
    }, {
        upperKey: '_',
        defaultKey: 'ພ'
    }, {
        upperKey: '+',
        defaultKey: 'ະ'
    }, {
        upperKey: 'ິ້',
        defaultKey: 'ິ'
    }, {
        upperKey: 'ີ້',
        defaultKey: 'ີ'
    }, {
        upperKey: 'ຣ',
        defaultKey: 'ຮ'
    }, {
        upperKey: 'ໜ',
        defaultKey: 'ນ'
    }, {
        upperKey: 'ຽ',
        defaultKey: 'ຍ'
    }, {
        upperKey: '-',
        defaultKey: 'ບ'
    }, {
        upperKey: 'ຫຼ',
        defaultKey: 'ລ'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'ັ້',
        defaultKey: 'ັ'
    }, {
        upperKey: ';',
        defaultKey: 'ຫ'
    }, {
        upperKey: '.',
        defaultKey: 'ກ'
    }, {
        upperKey: ',',
        defaultKey: 'ດ'
    }, {
        upperKey: ':',
        defaultKey: 'ເ'
    }, {
        upperKey: '໊',
        defaultKey: 'ເ'
    }, {
        upperKey: '໋',
        defaultKey: '້'
    }, {
        upperKey: '!',
        defaultKey: 'າ'
    }, {
        upperKey: '?',
        defaultKey: 'ສ'
    }, {
        upperKey: '%',
        defaultKey: 'ວ'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: '=',
        defaultKey: 'ງ'
    }, {
        upperKey: '₭',
        defaultKey: 'ຜ'
    }, {
        upperKey: '(',
        defaultKey: 'ປ'
    }, {
        upperKey: 'ຯ',
        defaultKey: 'ແ'
    }, {
        upperKey: 'x',
        defaultKey: 'ອ'
    }, {
        upperKey: 'ຶ້',
        defaultKey: 'ຶ'
    }, {
        upperKey: 'ື້',
        defaultKey: 'ື'
    }, {
        upperKey: 'ໆ',
        defaultKey: 'ທ'
    }, {
        upperKey: 'ໝ',
        defaultKey: 'ມ'
    }, {
        upperKey: '$',
        defaultKey: 'ໃ'
    }, {
        upperKey: ')',
        defaultKey: 'ຝ'
    }, {}, // Right shift
    {} // Space
];

/**
 * Korean[ko_kr] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ko_kr = [{
         upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    { 
        upperKey: 'ㅃ',
        defaultKey: 'ㅂ'
    }, {
        upperKey: 'ㅉ',
        defaultKey: 'ㅈ'
    }, {
        upperKey: 'ㄸ',
        defaultKey: 'ㄷ'
    }, {
        upperKey: 'ㄲ',
        defaultKey: 'ㄱ'
    }, {
        upperKey: 'ㅆ',
        defaultKey: 'ㅅ'
    }, {
        upperKey: 'ㅛ',
        defaultKey: 'ㅛ'
    }, {
        upperKey: 'ㅕ',
        defaultKey: 'ㅕ'
    }, {
        upperKey: 'ㅑ',
        defaultKey: 'ㅑ'
    }, {
        upperKey: 'ㅒ',
        defaultKey: 'ㅐ'
    }, {
        upperKey: 'ㅖ',
        defaultKey: 'ㅔ'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'ㅁ',
        defaultKey: 'ㅁ'
    }, {
        upperKey: 'ㄴ',
        defaultKey: 'ㄴ'
    }, {
        upperKey: 'ㅇ',
        defaultKey: 'ㅇ'
    }, {
        upperKey: 'ㄹ',
        defaultKey: 'ㄹ'
    }, {
        upperKey: 'ㅎ',
        defaultKey: 'ㅎ'
    }, {
        upperKey: 'ㅗ',
        defaultKey: 'ㅗ'
    }, {
        upperKey: 'ㅓ',
        defaultKey: 'ㅓ'
    }, {
        upperKey: 'ㅏ',
        defaultKey: 'ㅏ'
    }, {
        upperKey: 'ㅣ',
        defaultKey: 'ㅣ'
    }, {
        upperKey: ':',
        defaultKey: ';'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'ㅋ',
        defaultKey: 'ㅋ'
    }, {
        upperKey: 'ㅌ',
        defaultKey: 'ㅌ'
    }, {
        upperKey: 'ㅊ',
        defaultKey: 'ㅊ'
    }, {
        upperKey: 'ㅍ',
        defaultKey: 'ㅍ'
    }, {
        upperKey: 'ㅠ',
        defaultKey: 'ㅠ'
    }, {
        upperKey: 'ㅜ',
        defaultKey: 'ㅜ'
    }, {
        upperKey: 'ㅡ',
        defaultKey: 'ㅡ'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {
        upperKey: '',
        defaultKey: ''
    }, {}, // Right shift
    {} // Space
];

/**
 * Latvian[lv_lv] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.lv_lv = [{
         upperKey: '?',
        defaultKey: ''
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '«',
        defaultKey: '2'
    }, {
        upperKey: '»',
        defaultKey: '3'
    }, {
        upperKey: '§',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '/',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '×',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {}, // Delete
    {}, // Tab
    { 
        upperKey: 'Ū',
        defaultKey: 'ū'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'Ē',
        defaultKey: 'ē'
    }, {
        upperKey: 'Č',
        defaultKey: 'č'
    }, {
        upperKey: 'Ž',
        defaultKey: 'ž'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'Š',
        defaultKey: 'š'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: '°',
        defaultKey: '\''
    },{}, // Return
    {}, // Left shift
    {
        upperKey: 'Ģ',
        defaultKey: 'ģ'
    }, {
        upperKey: 'Ņ',
        defaultKey: 'ņ'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'Ī',
        defaultKey: 'ī'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'Ā',
        defaultKey: 'ā'
    }, {
        upperKey: ';',
        defaultKey: ','
    }, {
        upperKey: ':',
        defaultKey: '.'
    }, {
        upperKey: 'Ļ',
        defaultKey: 'ļ'
    }, {}, // Right shift
    {} // Space
];

/**
 * Lithuanian[lv_lv] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.lv_lv = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: 'Ą',
        defaultKey: 'ą'
    }, {
        upperKey: 'Č',
        defaultKey: 'č'
    }, {
        upperKey: 'Ę',
        defaultKey: 'ę'
    }, {
        upperKey: 'Ė',
        defaultKey: 'ė'
    }, {
        upperKey: 'Į',
        defaultKey: 'į'
    }, {
        upperKey: 'Š',
        defaultKey: 'š'
    }, {
        upperKey: 'Ų',
        defaultKey: 'ų'
    }, {
        upperKey: 'Ū',
        defaultKey: 'ū'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: 'Ž',
        defaultKey: 'ž'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: ':',
        defaultKey: ';'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Maltese[mt_mt] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.mt_mt = [{
        upperKey: 'Ċ',
        defaultKey: 'ċ'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '€',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: 'Ġ',
        defaultKey: 'ġ'
    }, {
        upperKey: 'Ħ',
        defaultKey: 'ħ'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: ':',
        defaultKey: ';'
    }, {
        upperKey: '@',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Ż',
        defaultKey: 'ż'
    },
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    },{}, // Right shift
    {} // Space
];

/**
 * Norwegian[nb_no] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.nb_no = [{
        upperKey: '§',
        defaultKey: '|'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '¤',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '&',
        defaultKey: '6'
    }, {
        upperKey: '/',
        defaultKey: '7'
    }, {
        upperKey: '(',
        defaultKey: '8'
    }, {
        upperKey: ')',
        defaultKey: '9'
    }, {
        upperKey: '=',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '+'
    }, {
        upperKey: '`',
        defaultKey: '\\'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: 'å',
        defaultKey: 'Å'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ø',
        defaultKey: 'ø'
    }, {
        upperKey: 'Æ',
        defaultKey: 'æ'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Persian[fa_fa] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.fa_fa = [{
        upperKey: '',
        defaultKey: ''
    }, {
        upperKey: '!',
        defaultKey: '۱'
    }, {
        upperKey: '٬',
        defaultKey: '۲'
    }, {
        upperKey: '٫',
        defaultKey: '۳'
    }, {
        upperKey: 'ریال',
        defaultKey: '۴'
    }, {
        upperKey: '٪',
        defaultKey: '۵'
    }, {
        upperKey: '×',
        defaultKey: '۶'
    }, {
        upperKey: '،',
        defaultKey: '۷'
    }, {
        upperKey: '*',
        defaultKey: '۸'
    }, {
        upperKey: '(',
        defaultKey: '۹'
    }, {
        upperKey: ')',
        defaultKey: '۰'
    }, {
        upperKey: '-',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'ْ',
        defaultKey: 'ض'
    }, {
        upperKey: 'ٌ',
        defaultKey: 'ص'
    }, {
        upperKey: 'ٍ',
        defaultKey: 'ث'
    }, {
        upperKey: 'ً',
        defaultKey: 'ق'
    }, {
        upperKey: 'ُ',
        defaultKey: 'ف'
    }, {
        upperKey: 'ِ',
        defaultKey: 'غ'
    }, {
        upperKey: 'َ',
        defaultKey: 'ع'
    }, {
        upperKey: 'ّ',
        defaultKey: 'ه'
    }, {
        upperKey: '[',
        defaultKey: 'خ'
    }, {
        upperKey: ']',
        defaultKey: 'ح'
    }, {
        upperKey: '{',
        defaultKey: 'ج'
    }, {
        upperKey: '}',
        defaultKey: 'چ'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'ؤ',
        defaultKey: 'ش'
    }, {
        upperKey: 'ئ',
        defaultKey: 'س'
    }, {
        upperKey: 'ي',
        defaultKey: 'ی'
    }, {
        upperKey: 'إ',
        defaultKey: 'ب'
    }, {
        upperKey: 'أ',
        defaultKey: 'ل'
    }, {
        upperKey: 'آ',
        defaultKey: 'ة'
    }, {
        upperKey: 'ة',
        defaultKey: 'ت'
    }, {
        upperKey: '»',
        defaultKey: 'ن'
    }, {
        upperKey: '«',
        defaultKey: 'م'
    }, {
        upperKey: ':',
        defaultKey: 'ک'
    }, {
        upperKey: '؛',
        defaultKey: 'گ'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'ك',
        defaultKey: 'ظ'
    }, {
        upperKey: '',
        defaultKey: 'ط'
    }, {
        upperKey: 'ژ',
        defaultKey: 'ز'
    }, {
        upperKey: '',
        defaultKey: 'ر'
    }, {
        upperKey: '',
        defaultKey: 'ذ'
    }, {
        upperKey: '',
        defaultKey: 'د'
    }, {
        upperKey: '‌‌‌‌‌‌‌ء',
        defaultKey: 'پ'
    }, {
        upperKey: '<',
        defaultKey: 'و'
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '؟',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Portuguese[pt_br] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.pt_br = [{
        upperKey: '~',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ç',
        defaultKey: 'ç'
    }, {
        upperKey: '^',
        defaultKey: '~'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Romanian[ro_ro] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ro_ro = [{
        upperKey: 'Â',
        defaultKey: 'â'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Â',
        defaultKey: 'â'
    }, {
        upperKey: 'Î',
        defaultKey: 'î'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'Ș',
        defaultKey: 'ș'
    }, {
        upperKey: 'Ț',
        defaultKey: 'ț'
    }, {
        upperKey: '^',
        defaultKey: '~'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Slovak[sk_sk] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.sk_sk = [{
        upperKey: '°',
        defaultKey: ';'
    }, {
        upperKey: '1',
        defaultKey: '+'
    }, {
        upperKey: '2',
        defaultKey: 'ľ'
    }, {
        upperKey: '3',
        defaultKey: 'š'
    }, {
        upperKey: '4',
        defaultKey: 'č'
    }, {
        upperKey: '5',
        defaultKey: 'ť'
    }, {
        upperKey: '6',
        defaultKey: 'ž'
    }, {
        upperKey: '7',
        defaultKey: 'ý'
    }, {
        upperKey: '8',
        defaultKey: 'á'
    }, {
        upperKey: '9',
        defaultKey: 'í'
    }, {
        upperKey: '0',
        defaultKey: 'é'
    }, {
        upperKey: '%',
        defaultKey: '='
    }, {
        upperKey: 'ˇ',
        defaultKey: '´'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: '/',
        defaultKey: 'ú'
    }, {
        upperKey: '(',
        defaultKey: 'ä'
    }, {
        upperKey: ')',
        defaultKey: 'ň'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: '"',
        defaultKey: 'ô'
    }, {
        upperKey: '!',
        defaultKey: '§'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Slovenian[sl_si] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.sl_si = [{
        upperKey: '¨',
        defaultKey: '¸'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '?',
        defaultKey: '\''
    }, {
        upperKey: '*',
        defaultKey: '+'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Š',
        defaultKey: 'š'
    }, {
        upperKey: 'Đ',
        defaultKey: 'đ'
    }, {
        upperKey: 'Ž',
        defaultKey: 'ž'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Č',
        defaultKey: 'č'
    }, {
        upperKey: 'Ć',
        defaultKey: 'ć'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {}, // Right shift
    {} // Space
];

/**
 * Swedish[sv_se] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.sv_se = [{
        upperKey: '½',
        defaultKey: '§'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '@',
        defaultKey: '2'
    }, {
        upperKey: '#',
        defaultKey: '3'
    }, {
        upperKey: '$',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: '^',
        defaultKey: '6'
    }, {
        upperKey: '&',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '+'
    }, {
        upperKey: '+',
        defaultKey: '´'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Q',
        defaultKey: 'q'
    }, {
        upperKey: 'W',
        defaultKey: 'w'
    }, {
        upperKey: 'E',
        defaultKey: 'e'
    }, {
        upperKey: 'R',
        defaultKey: 'r'
    }, {
        upperKey: 'T',
        defaultKey: 't'
    }, {
        upperKey: 'Y',
        defaultKey: 'y'
    }, {
        upperKey: 'U',
        defaultKey: 'u'
    }, {
        upperKey: 'I',
        defaultKey: 'i'
    }, {
        upperKey: 'O',
        defaultKey: 'o'
    }, {
        upperKey: 'P',
        defaultKey: 'p'
    }, {
        upperKey: 'Å',
        defaultKey: 'å'
    }, {
        upperKey: '*',
        defaultKey: '\''
    }, {
        upperKey: ',',
        defaultKey: ';'
    }, {}, // Caps lock
    {
        upperKey: 'A',
        defaultKey: 'a'
    }, {
        upperKey: 'S',
        defaultKey: 's'
    }, {
        upperKey: 'D',
        defaultKey: 'd'
    }, {
        upperKey: 'F',
        defaultKey: 'f'
    }, {
        upperKey: 'G',
        defaultKey: 'g'
    }, {
        upperKey: 'H',
        defaultKey: 'h'
    }, {
        upperKey: 'J',
        defaultKey: 'j'
    }, {
        upperKey: 'K',
        defaultKey: 'k'
    }, {
        upperKey: 'L',
        defaultKey: 'l'
    }, {
        upperKey: 'Ö',
        defaultKey: 'ö'
    }, {
        upperKey: 'Ä',
        defaultKey: 'ä'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'Z',
        defaultKey: 'z'
    }, {
        upperKey: 'X',
        defaultKey: 'x'
    }, {
        upperKey: 'C',
        defaultKey: 'c'
    }, {
        upperKey: 'V',
        defaultKey: 'v'
    }, {
        upperKey: 'B',
        defaultKey: 'b'
    }, {
        upperKey: 'N',
        defaultKey: 'n'
    }, {
        upperKey: 'M',
        defaultKey: 'm'
    }, {
        upperKey: '<',
        defaultKey: ','
    }, {
        upperKey: '>',
        defaultKey: '.'
    }, {
        upperKey: '?',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];

/**
 * Thai[th_th] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.th_th = [{
        upperKey: '%',
        defaultKey: '_'
    }, {
        upperKey: '+',
        defaultKey: 'ๅ'
    }, {
        upperKey: '๑',
        defaultKey: '/'
    }, {
        upperKey: '๒',
        defaultKey: '-'
    }, {
        upperKey: '๓',
        defaultKey: 'ภ'
    }, {
        upperKey: '๔',
        defaultKey: 'ถ'
    }, {
        upperKey: 'ุ',
        defaultKey: 'ุ'
    }, {
        upperKey: '฿',
        defaultKey: 'ึ'
    }, {
        upperKey: '๕',
        defaultKey: 'ค'
    }, {
        upperKey: '๖',
        defaultKey: 'ต'
    }, {
        upperKey: '๗',
        defaultKey: 'จ'
    }, {
        upperKey: '๘',
        defaultKey: 'ข'
    }, {
        upperKey: '๙',
        defaultKey: 'ช'
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: '๐',
        defaultKey: 'ๆ'
    }, {
        upperKey: '"',
        defaultKey: 'ไ'
    }, {
        upperKey: 'ฎ',
        defaultKey: 'ำ'
    }, {
        upperKey: 'ฑ',
        defaultKey: 'พ'
    }, {
        upperKey: 'ธ',
        defaultKey: 'ะ'
    }, {
        upperKey: 'ํ',
        defaultKey: 'ั'
    }, {
        upperKey: '๊',
        defaultKey: 'ี'
    }, {
        upperKey: 'ณ',
        defaultKey: 'ร'
    }, {
        upperKey: 'ฯ',
        defaultKey: 'น'
    }, {
        upperKey: 'ญ',
        defaultKey: 'ย'
    }, {
        upperKey: 'ฐ',
        defaultKey: 'บ'
    }, {
        upperKey: ',',
        defaultKey: 'ล'
    }, {
        upperKey: 'ฅ',
        defaultKey: 'ฃ'
    }, {}, // Caps lock
    {
        upperKey: 'ฤ',
        defaultKey: 'ฟ'
    }, {
        upperKey: 'ฆ',
        defaultKey: 'ห'
    }, {
        upperKey: 'ฏ',
        defaultKey: 'ก'
    }, {
        upperKey: 'โ',
        defaultKey: 'ด'
    }, {
        upperKey: 'ฌ',
        defaultKey: 'เ'
    }, {
        upperKey: '็',
        defaultKey: '้'
    }, {
        upperKey: '๋',
        defaultKey: '่'
    }, {
        upperKey: 'ษ',
        defaultKey: 'า'
    }, {
        upperKey: 'ศ',
        defaultKey: 'ส'
    }, {
        upperKey: 'ซ',
        defaultKey: 'ว'
    }, {
        upperKey: '.',
        defaultKey: 'ง'
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: '(',
        defaultKey: 'ผ'
    }, {
        upperKey: ')',
        defaultKey: 'ป'
    }, {
        upperKey: 'ฉ',
        defaultKey: 'แ'
    }, {
        upperKey: 'ฮ',
        defaultKey: 'อ'
    }, {
        upperKey: '.',
        defaultKey: 'ิ'
    }, {
        upperKey: '์',
        defaultKey: 'ื'
    }, {
        upperKey: '?',
        defaultKey: 'ท'
    }, {
        upperKey: 'ฒ',
        defaultKey: 'ม'
    }, {
        upperKey: 'ฬ',
        defaultKey: 'ใ'
    }, {
        upperKey: 'ฦ',
        defaultKey: 'ฝ'
    }, {}, // Right shift
    {} // Space
];

/**
 * Ukrainian[uk_ua] keyboard characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.uk_ua = [{
        upperKey: '₴',
        defaultKey: '`'
    }, {
        upperKey: '!',
        defaultKey: '1'
    }, {
        upperKey: '"',
        defaultKey: '2'
    }, {
        upperKey: '№',
        defaultKey: '3'
    }, {
        upperKey: ';',
        defaultKey: '4'
    }, {
        upperKey: '%',
        defaultKey: '5'
    }, {
        upperKey: ':',
        defaultKey: '6'
    }, {
        upperKey: '?',
        defaultKey: '7'
    }, {
        upperKey: '*',
        defaultKey: '8'
    }, {
        upperKey: '(',
        defaultKey: '9'
    }, {
        upperKey: ')',
        defaultKey: '0'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'Й',
        defaultKey: 'й'
    }, {
        upperKey: 'Ц',
        defaultKey: 'ц'
    }, {
        upperKey: 'У',
        defaultKey: 'у'
    }, {
        upperKey: 'К',
        defaultKey: 'к'
    }, {
        upperKey: 'Е',
        defaultKey: 'е'
    }, {
        upperKey: 'Н',
        defaultKey: 'н'
    }, {
        upperKey: 'Г',
        defaultKey: 'г'
    }, {
        upperKey: 'Ш',
        defaultKey: 'ш'
    }, {
        upperKey: 'Щ',
        defaultKey: 'щ'
    }, {
        upperKey: 'З',
        defaultKey: 'з'
    }, {
        upperKey: 'Х',
        defaultKey: 'х'
    }, {
        upperKey: 'Ї',
        defaultKey: 'ї'
    }, {
        upperKey: 'Ґ',
        defaultKey: 'ґ'
    }, {}, // Caps Lock
    {
        upperKey: 'Ф',
        defaultKey: 'ф'
    }, {
        upperKey: 'І',
        defaultKey: 'і'
    }, {
        upperKey: 'В',
        defaultKey: 'в'
    }, {
        upperKey: 'А',
        defaultKey: 'а'
    }, {
        upperKey: 'П',
        defaultKey: 'п'
    }, {
        upperKey: 'Р',
        defaultKey: 'р'
    }, {
        upperKey: 'О',
        defaultKey: 'о'
    }, {
        upperKey: 'Л',
        defaultKey: 'л'
    }, {
        upperKey: 'Д',
        defaultKey: 'д'
    }, {
        upperKey: 'Ж',
        defaultKey: 'ж'
    }, {
        upperKey: 'Є',
        defaultKey: 'є'
    }, {}, // Return
    {}, // Left Shift
    {
        upperKey: 'Я',
        defaultKey: 'я'
    }, {
        upperKey: 'Ч',
        defaultKey: 'ч'
    }, {
        upperKey: 'С',
        defaultKey: 'с'
    }, {
        upperKey: 'М',
        defaultKey: 'м'
    }, {
        upperKey: 'И',
        defaultKey: 'и'
    }, {
        upperKey: 'Т',
        defaultKey: 'т'
    }, {
        upperKey: 'Ь',
        defaultKey: 'ь'
    }, {
        upperKey: 'Б',
        defaultKey: 'б'
    }, {
        upperKey: 'Ю',
        defaultKey: 'ю'
    }, {
        upperKey: ',',
        defaultKey: '.'
    }, {}, // Right Shift
    {} // Space
];

/**
 * Urdu[ur_pk] language characters.
 * */
var virtualKeyboard = virtualKeyboard || {
    layouts: {}
};

virtualKeyboard.layouts.ur_pk = [{
        upperKey: 'ً',
        defaultKey: '`'
    }, {
        upperKey: '1',
        defaultKey: '۱'
    }, {
        upperKey: '2',
        defaultKey: '۲'
    }, {
        upperKey: '3',
        defaultKey: '۳'
    }, {
        upperKey: '4',
        defaultKey: '۴'
    }, {
        upperKey: '5',
        defaultKey: '۵'
    }, {
        upperKey: '6',
        defaultKey: '۶'
    }, {
        upperKey: '7',
        defaultKey: '۷'
    }, {
        upperKey: '8',
        defaultKey: '۸'
    }, {
        upperKey: '9',
        defaultKey: '۹'
    }, {
        upperKey: '0',
        defaultKey: '۰'
    }, {
        upperKey: '_',
        defaultKey: '-'
    }, {
        upperKey: '+',
        defaultKey: '='
    }, {}, // Delete
    {}, // Tab
    {
        upperKey: 'ْ',
        defaultKey: 'ق'
    }, {
        upperKey: 'ّ',
        defaultKey: 'و'
    }, {
        upperKey: 'ٰ',
        defaultKey: 'ع'
    }, {
        upperKey: 'ڑ',
        defaultKey: 'ر'
    }, {
        upperKey: 'ٹ',
        defaultKey: 'ت'
    }, {
        upperKey: 'َ',
        defaultKey: 'ے'
    }, {
        upperKey: 'ئ',
        defaultKey: 'ء'
    }, {
        upperKey: 'ِ',
        defaultKey: 'ی'
    }, {
        upperKey: 'ۃ',
        defaultKey: 'ہ'
    }, {
        upperKey: 'ُ',
        defaultKey: 'پ'
    }, {
        upperKey: '{',
        defaultKey: '['
    }, {
        upperKey: '}',
        defaultKey: ']'
    }, {
        upperKey: '|',
        defaultKey: '\\'
    }, {}, // Caps lock
    {
        upperKey: 'آ',
        defaultKey: 'ا'
    }, {
        upperKey: 'ص',
        defaultKey: 'س'
    }, {
        upperKey: 'ڈ',
        defaultKey: 'د'
    }, {
        upperKey: '',
        defaultKey: 'ف'
    }, {
        upperKey: 'غ',
        defaultKey: 'گ'
    }, {
        upperKey: 'ھ',
        defaultKey: 'ح'
    }, {
        upperKey: 'ض',
        defaultKey: 'ج'
    }, {
        upperKey: 'خ',
        defaultKey: 'ک'
    }, {
        upperKey: '',
        defaultKey: 'ل'
    }, {
        upperKey: ':',
        defaultKey: '؛'
    }, {
        upperKey: '"',
        defaultKey: '\''
    }, {}, // Return
    {}, // Left shift
    {
        upperKey: 'ذ',
        defaultKey: 'ز'
    }, {
        upperKey: 'ژ',
        defaultKey: 'ش'
    }, {
        upperKey: 'ث',
        defaultKey: 'چ'
    }, {
        upperKey: 'ظ',
        defaultKey: 'ط'
    }, {
        upperKey: '',
        defaultKey: 'ب'
    }, {
        upperKey: 'ں',
        defaultKey: 'ن'
    }, {
        upperKey: '٘',
        defaultKey: 'م'
    }, {
        upperKey: '',
        defaultKey: '،'
    }, {
        upperKey: ',',
        defaultKey: '۔'
    }, {
        upperKey: '؟',
        defaultKey: '/'
    }, {}, // Right shift
    {} // Space
];