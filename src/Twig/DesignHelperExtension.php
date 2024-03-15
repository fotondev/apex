<?php

namespace App\Twig;

use Twig\TwigFilter;

class DesignHelperExtension extends \Twig\Extension\AbstractExtension
{
    public function getFilters()
    {
        return [

            new TwigFilter('undo_btn', [$this, 'undoBtn']),
            new TwigFilter('min_label', [$this, 'minLabel']),

        ];
    }


    public function undoBtn($id)
    {
        return '<div id="undo_'.$id.'" class="btn-undo">отменить</div>';
    }

    public function minLabel($value)
    {
        return '<span class="text-nowrap">'.$value.' (min)</span>';

    }

}