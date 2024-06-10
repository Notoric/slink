<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortlink;

class ShortlinkController extends Controller
{
    public function create(Request $request) {
        if (auth()->user() == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'url' => 'required|url'
            ]);
    
            //check if url returns 200 at its final redirect

            $shortlink = new Shortlink();
            $shortlink->create($request->url, auth()->id());
            return redirect("/l/{$shortlink->shortid}");
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function goto(Request $request, $id) {
        try {
            $shortlink = (new Shortlink())->get($id);
            // check if the link is expired or if it has reached the max clicks
            // log the interaction
            return redirect($shortlink->destination);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
