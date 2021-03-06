<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt BSD 3-Clause License
 */

return array(
    'item'  => array(
        'social_sharing'    => array(
            'title'         => _t('Social sharing items'),
            'description'   => '',
            'edit'          => array(
                'type'      => 'multi_checkbox',
                'options'   => array(
                    'options'   => Pi::service('social_sharing')->getList(),
                ),
            ),
            'filter'        => 'array',
        ),
        'show_breadcrumbs' => array(
            'title'        => _a('Show breadcrumbs'),
            'description'  => '',
            'edit'         => 'checkbox',
            'filter'       => 'number_int',
            'value'        => 1
        ),
    ),
);