<?php


return [
    'props' => [
        /**
         * Optional columns definition for `table` layout
         * to show selected information in the table.
         */
        'columns' => function (?array $columns = null) {
            return $columns;
        },
    ],
    'computed' => [
        'columns' => function () {
            $columns = $this->columns ?? [
                'text' => [
                    'label' => 'Title',
                    'value' => $this->text
                ],
                'info' => [
                    'label' => 'Info',
                    'value' => $this->info
                ]
            ];

            return $columns;
        },
        'defaultColumns' => function () {
            return [
                'title' => [
                    'label' => 'Title',
                    'value' => '{{ model.title }}',
                    'width' => '1/2',
                ]
            ];
        },
    ]
];
