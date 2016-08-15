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
        'empty' => 'Empty Cart',
        'checkout' => 'Proceed to checkout',
        'continue' => 'Continue Shopping',
        'remove' => 'Remove',
        'add' => 'Add to Cart',
    ],

    'general' => [
        'cart' => 'Cart',
        'your_cart' => 'Your Cart',
        'item' => 'Product',
        'quantity' => 'Quantity',
        'price' => 'Price',
        'image' => 'Image',
    ],

    'messages' => [
        'cart' => [
            'empty' => 'Your cart has been cleared!',
        ],
        'item' => [
            'exists' => 'Item is already in your cart!',
            'added' => 'Item was added to your cart!',
            'removed' => 'Item has been removed!',
        ],
        'quantity' => [
            'updated' => 'Quantity was updated successfully!',
            'between' => 'Quantity must be between :min and :max.',
        ],
    ],

    'wishlist' => [
        'item' => 'Product',
        'quantity' => 'Quantity',
        'price' => 'Price',
        'image' => 'Image',

        'buttons' => [
            'add' => 'Add to wishlist',
            'transfer' => 'To Wishlist',
        ],

        'messages' => [
            'wishlist' => [
                'empty' => 'Your Wishlist has been cleared!',
            ],
            'item' => [
                'exists' => 'Item is already in your Wishlist!',
                'added' => 'Item was added to your Wishlist!',
                'removed' => 'Item has been removed from your Wishlist!',
                'transferred' => 'Item has been moved to your Wishlist!',
            ],
            'quantity' => [
                'updated' => 'Quantity was updated successfully!',
                'between' => 'Quantity must be between :min and :max.',
            ],
        ],
    ],

    'is_empty' => 'You have no items in your shopping cart',
    'tax' => 'Tax',
    'total' => 'Your total'
];
