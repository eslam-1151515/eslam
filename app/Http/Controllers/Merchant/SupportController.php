<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\SupportContact;
use Inertia\Inertia;

class SupportController extends Controller
{
    public function index()
    {
        $contacts = SupportContact::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'type' => $c->type,
                    'title' => $c->title,
                    'phone_number' => $c->phone_number,
                    'whatsapp_message' => $c->whatsapp_message,
                    'action_url' => $c->action_url,
                ];
            });

        return Inertia::render('Merchant/Support/Index', [
            'contacts' => $contacts,
        ]);
    }
}
