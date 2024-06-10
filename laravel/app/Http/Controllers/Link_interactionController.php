<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link_interaction;

class Link_interactionController extends Controller
{
    public static function getList(string $id) {
        $link_interaction = new Link_interaction();
        $link_interactions = $link_interaction->getRecords($id);
        return $link_interactions;
    }

    public static function access(Request $request) {
        $link_interaction = new Link_interaction();
        $link_interaction->create($request->id, $request->ip());
    }

    public static function getCountryArray(string $id) {
        $link_interaction = new Link_interaction();
        $country_array = $link_interaction->getCountryArray($id);
        return $country_array;
    }
}
