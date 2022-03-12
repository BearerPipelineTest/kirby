<?php

return [
    'props' => [
        /**
         * Section layout.
         * Available layout methods: `cards`, `cardlets`, `list`, `table`.
         */
        'layout' => function (string $layout = 'list') {
            $layouts = ['cards', 'cardlets', 'list', 'table'];
            return in_array($layout, $layouts) ? $layout : 'list';
        }
    ]
];
