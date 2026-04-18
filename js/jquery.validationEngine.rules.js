(function ($) {
    $.fn.validationEngineLanguage = function () {};
    $.validationEngineLanguage = {
        newLang: function () {
            $.validationEngineLanguage.allRules = {
                "required": {
                    "regex": "none",
                    "alertText": "* Questo campo è obbligatorio.",
                    "alertTextCheckboxMultiple": "* Treff eine Entscheidung",
                    "alertTextCheckboxe": "* Non hai confermato la presa visione dei CGC."
                },
                "length": {
                    "regex": "none",
                    "alertText": "* Sono ammessi da ",
                    "alertText2": " a ",
                    "alertText3": " caratteri."
                },
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Checks allowed Exceeded"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Bitte wähle ",
                    "alertText2": " Optionen"
                },
                "confirm": {
                    "regex": "none",
                    "alertText": "* Questi campi non corrispondono."
                },
                "telephone": {
                    "regex": "/^[0-9\-\(\)\ ]+$/",
                    "alertText": "* Unzulässige Telefonnummer"
                },
                "email": {
                    "regex": "/^[a-zA-Z0-9_\\.\\-]+\\@([a-zA-Z0-9\\-]+\\.)+[a-zA-Z0-9]{2,4}$/",
                    "alertText": "* L'indirizzo E-mail sembrerebbe essere errato!"
                },
                "date": {
                    "regex": "/^[0-9]{4}\-\[0-9]{1,2}\-\[0-9]{1,2}$/",
                    "alertText": "* Invalid date, must be in YYYY-MM-DD format"
                },
                "onlyNumber": {
                    "regex": "/^[0-9\ ]+$/",
                    "alertText": "* Bitte nur Nummern"
                },
                "noSpecialCharacters": {
                    "regex": "/^[0-9a-zA-Z]*$/",
                    "alertText": "* Non sono ammessi caratteri speciali"
                },
                "onlyValidPasswordCharacters": {
                    "regex": "/^[a-zA-Z0-9 @!#$%&(){}*+,\-./:;<>=?[\\]\^_|~]*$/",
                    "alertText": "* Non sono ammessi caratteri speciali"
                },
                "ajaxUser": {
                    "file": "../validateUser.php",
                    "alertTextOk": "* Dieser Benutzername ist verfügbar",
                    "alertTextLoad": "* Bitte warten es wird geladen.",
                    "alertText": "* Dieser Benutzername ist nicht mehr verfügbar"
                },
                "ajaxName": {
                    "file": "../validateUser.php",
                    "alertText": "* Dieser Benutzername ist nicht mehr verfügbar",
                    "alertTextOk": "* Dieser Benutzername ist verfügbar",
                    "alertTextLoad": "* Bitte warten es wird geladen."
                },
                "onlyLetter": {
                    "regex": "/^[a-zA-Z\ \']+$/",
                    "alertText": "* Nur Zeichen verwenden."
                }
            }
        }
    }
})(jQuery);
$(document).ready(function () {
    $.validationEngineLanguage.newLang()
});