<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Tailwind CSS Classes for Markdown Tables
    |--------------------------------------------------------------------------
    |
    | This file defines the Tailwind CSS classes applied to various parts of
    | HTML tables generated from Markdown by the custom renderers.
    | Modifying these classes will change the appearance of the tables.
    |
    */

    'wrapper' => 'relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-white/5 dark:bg-gray-800 text-gray-700 dark:text-gray-400 not-prose',

    'table' => 'w-full text-sm text-left rtl:text-right ',

    'thead' => 'text-xs uppercase dark:bg-black/10 ',

    // Classes for <tr> elements within <tbody>
    'tbody_row' => 'border-b border-gray-200 dark:border-gray-700',

    // Classes for <th> elements within <thead>
    'th_head' => 'px-6 py-4 font-medium',

    // Classes for the first <th> element (scope="row") within a <tbody> row
    'th_body_row_header' => 'px-6 py-4 font-medium  whitespace-nowrap ',

    // Classes for regular <td> elements within <tbody>
    'td_cell' => 'px-6 py-4',

];
