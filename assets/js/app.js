/**
 * Main module for extra js features
 * @author oljn22
 */

"use strict";

import { default as clickAction } from "./click-action.js";

(function(){
    document.addEventListener("DOMContentLoaded", () => {
        if (document.getElementById("place_card_form")) {
            clickAction();
        }
    });
})();
