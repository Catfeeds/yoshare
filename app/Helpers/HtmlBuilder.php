<?php

namespace App\Helpers;


class HtmlBuilder
{
    public static function menuEditor($menu)
    {
        return '<li class="dd-item dd3-item" data-id="' . $menu->id . '">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content"><i class="fa ' . $menu->icon . '"></i> ' . $menu->name . '</div>';
    }
}