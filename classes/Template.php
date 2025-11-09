<?php

namespace WP_Iching;

class Template
{
    public static function render(): string
    {
        $permalink = get_the_permalink();

        $ask_button = '<div class="iching-button-wrapper"><button id="iching-button" class="iching-buttons">Отримати передбачення</button></div>';
        $again_button = '<div class="iching-button-wrapper"><a href="' . $permalink . '" id="iching-again-button" class="iching-buttons">Запитати І Цзін ще раз</a></div>';
        $divinationWrapper = '<div id="iching-divination" class="iching-divination"></div>';

        $output = sprintf(
            '<div class="iching">%s %s %s</div>',
            $ask_button,
            $divinationWrapper,
            $again_button
        );

        return $output;
    }
}
