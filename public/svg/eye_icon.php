<?php
function eye_icon($class = '') {

echo '<svg fill="none" viewBox="0 0 24 24" class="'.$class.'">
        <path
            stroke-width="1.5"
            stroke="currentColor"
            d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z"></path>
        <circle
            stroke-width="1.5"
            stroke="currentColor"
            r="3"
            cy="12"
            cx="12"></circle>
    </svg>';

};