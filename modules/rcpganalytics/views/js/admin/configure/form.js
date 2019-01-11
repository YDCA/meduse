/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awared by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 *  @author    ReactionCode <info@reactioncode.com>
 *  @copyright 2015-2018 Garamo Online L.T.D
 *  @license   Commercial license
 */

(function(){
    'use strict';

    // Initialize all user events when DOM ready
    document.addEventListener('DOMContentLoaded', initConfigForm, false);

    function initConfigForm() {

        var hidingTimeNode;
        var eventValueNodes;

        hidingTimeNode = document.querySelector('#RC_PGANALYTICS_SSSR');
        hidingTimeNode.addEventListener('input', validateSiteSpeedSampleRate);

        eventValueNodes = document.querySelectorAll('.js-event-value');

        eventValueNodes.forEach(function(eventValueNode) {
           eventValueNode.addEventListener('input', validateEventValue);
        });
    }

    function validateSiteSpeedSampleRate(event) {
        var min = 1;
        var max = 100;
        var isNumber = /^\d+$/;
        var inputValue = event.target.value;

        if (isNumber.test(inputValue)) {
            if (Number(inputValue) > max) {
                event.target.value = max;
            } else if (Number(inputValue) < min) {
                event.target.value = min;
            }
        } else {
            event.target.value = min;
        }
    }

    function validateEventValue(event) {
        var min = 0;
        var isNumber = /^\d+$/;

        var inputValue = event.target.value;

        if (isNumber.test(inputValue)) {
            if (Number(inputValue) < min) {
                event.target.value = min;
            }
        } else {
            event.target.value = min;
        }
    }
})();