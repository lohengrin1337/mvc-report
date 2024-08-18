/**
 * Main module for extra js features for project entry point
 * @author oljn22
 */

"use strict";

import makeCardSlotsClickable from "./click-action.js";
import delayCpuCardPlacement from "./cpu-action.js";

(function(){
    document.addEventListener("DOMContentLoaded", () => {
        if (document.getElementById("place_card_form")) {
            makeCardSlotsClickable();
        }

        if (document.getElementById("cpu_play_form")) {
            delayCpuCardPlacement();
        }
    });
})();
