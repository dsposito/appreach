<?php

namespace App\Http\Controllers;

use App\Services\Twitter;

/**
 * Handles search API endpoints.
 */
class Search extends Controller
{
    /**
     * Handles the main search endpoint.
     *
     * @return string
     */
    public function index()
    {
        $contacts = Twitter::findContacts(array(
            'q' => $this->request->input('q'),
        ));

        return response()->json($contacts);
    }
}
