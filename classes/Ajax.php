<?php

namespace WP_Iching;

use Iching\Core\FortuneTeller\FortuneTeller;

class Ajax
{
    private array $hexagrames = [];

    public function __construct()
    {
    }

    public function setAjaxAction(): void
    {
        add_action('wp_ajax_nopriv_get_divination', [$this, 'iching_ajax_get_divination']);
        add_action('wp_ajax_get_divination', [$this, 'iching_ajax_get_divination']);
    }

    /**
     * @throws \Exception
     */
    public function iching_ajax_get_divination(): void
    {
        check_ajax_referer('title_example', 'nonce');

        $this->hexagrames = (new FortuneTeller())->index();

        $primHex = $this->hexagrames['primaryHexagram'];
        $primData = !empty($this->hexagrames['primaryHexagramData']) ? $this->hexagrames['primaryHexagramData'] : [];
        $primName = !empty($primData['name']) ? $primData['name'] : '';
        $primDesc = !empty($primData['description']) ? $primData['description'] : '';
        $primKeys = !empty($primData['keywords']) ? $primData['keywords'] : '';
        $primMeaning = !empty($primData['interpretation']) ? $primData['interpretation'] : '';
        $primWorlds = !empty($primData['worlds']) ? $primData['worlds'] : '';
        $primPotential = !empty($primData['potential']) ? $primData['potential'] : '';

        $secondHex = $this->hexagrames['secondaryHexagram'];
        $secondData = $this->hexagrames['secondaryHexagramData'];
        $secondName = $secondData['name'];
        $secondDesc = $secondData['description'];
        $secondKeys = $secondData['keywords'];
        $secondMeaning = $secondData['interpretation'];
        $secondWorlds = $secondData['worlds'];
        $secondPotential = $secondData['potential'];


        $primHtml = '<h3>Ваше передбачення</h3>';
        $primHtml .= '<h4>Початкова гексаграма: ' . $primHex . ' ' . $primName . '</h4>';

        if(empty($primData)) {
            $primHtml .= '<p>На жаль, зараз немає тлумачення для цієї гексаграми ' . $primHex . '</p>';
        } else {
            $primHtml = $this->getHexHtml(
                $primDesc,
                $primHtml,
                $primKeys,
                $primMeaning,
                $primWorlds,
                $primPotential
            );
        }

        $secondHtml = '<h4>Фонова (вторинна) гексаграма: Іцзнь не бачить її для вас</h4>';

        if($secondData) {
            $secondHtml = '<h4>Фонова (вторинна) гексаграма: ' . $secondHex . ' ' . $secondName . '</h4>';
        }

        if(empty($secondData)) {
            $secondHtml .= '<p>На жаль, зараз немає тлумачення для цієї гексаграми ' . $secondHex . '</p>';
        } else {
            $secondHtml = $this->getHexHtml(
                $secondDesc,
                $secondHtml,
                $secondKeys,
                $secondMeaning,
                $secondWorlds,
                $secondPotential
            );
        }

        //wp_send_json($primHtml . $secondHtml);
        echo json_encode('<div>' . $primHtml . $secondHtml . '</div>');
        die();
    }

    /**
     * @param string $desc
     * @param string $html
     * @param string $keys
     * @param string $meaning
     * @param string $worlds
     * @param string $potential
     *
     * @return string
     */
    private function getHexHtml(
        string $desc,
        string $html,
        string $keys,
        string $meaning,
        string $worlds,
        string $potential
    ): string {
        $html .= '<p>Опис гексаграми: ' . $desc . '</p>';
        $html .= '<p>Ключі: ' . $keys . '</p>';
        $html .= '<p>Передбачення: ' . $meaning . '</p>';
        $html .= '<p>Енергія світів: ' . $worlds . '</p>';
        $html .= '<p>Потенція: ' . $potential . '</p>';

        return $html;
    }
}
