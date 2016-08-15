<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cart Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for cart specific words that
    | we need to display to the user. You are free to modify these
    | language lines according to your application's requirements.
    |
    */

    'buttons' => [
        'empty' => 'Winkelwagen legen',
        'checkout' => 'Afrekenen',
        'continue' => 'Verder winkelen',
        'remove' => 'Verwijderen',
        'add' => 'Toevoegen aan winkelwagen',
    ],

    'general' => [
        'cart' => 'Winkelwagen',
        'your_cart' => 'Uw Winkelwagen',
        'item' => 'Product',
        'quantity' => 'Aantal',
        'price' => 'Prijs',
        'image' => 'Afbeelding',
    ],

    'messages' => [
        'cart' => [
            'empty' => 'Uw winkelwagen is geleegd!',
        ],
        'item' => [
            'exists' => 'Product is ligt al in uw winkelwagen!',
            'added' => 'Product is toegevoegd aan uw winkelwagen!',
            'removed' => 'Product is verwijderd uit uw winkelwagen!',
        ],
        'quantity' => [
            'updated' => 'Aantal is succesvol aangepast!',
            'between' => 'Aantal moet tussen de :min en :max zijn.',
        ],
    ],

    'wishlist' => [
        'item' => 'Product',
        'quantity' => 'Aantal',
        'price' => 'Prijs',
        'image' => 'Afbeelding',

        'buttons' => [
            'add' => 'Toevoegen aan verlanglijst',
            'transfer' => 'Naar verlanglijst',
        ],

        'messages' => [
            'wishlist' => [
                'empty' => 'Uw verlanglijst is geleegd!',
            ],
            'item' => [
                'exists' => 'Product is staat al op uw verlanglijst!',
                'added' => 'Product is toegevoegd aan uw verlanglijst!',
                'removed' => 'Product is verwijderd uit uw verlanglijst!',
                'transferred' => 'Product is verplaatst naar uw verlanglijst!',
            ],
            'quantity' => [
                'updated' => 'Aantal is succesvol aangepast!',
                'between' => 'Aantal moet tussen de :min en :max zijn.',
            ],
        ],
    ],

    'is_empty' => 'U heeft geen producten in uw winkelwagen',
    'tax' => 'BTW',
    'total' => 'In totaal'
];
