<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * User profile and resource specs
 *
 * @see Pi\Application\Installer\Resource\User
 * @author Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 */
return array(
    // Fields
    'field'     => array(

        // Profile fields

        // Profile: Full name
        'fullname'  => array(
            'name'      => 'fullname',
            'title'     => __('Full name'),
        ),

        // Profile: Language
        'language'  => array(
            'name'  => 'language',
            'title' => __('Language'),
            'edit'  => 'locale',
        ),

        // Profile: Country
        'country'  => array(
            'name'  => 'country',
            'title' => __('Country'),
            'edit'  => 'Custom\User\Form\Element\Location',
        ),

        // Profile: Province
        'province'  => array(
            'name'  => 'province',
            'title' => __('Province'),

            'edit'  => 'hidden',
        ),

        // Profile: City
        'city'  => array(
            'name'  => 'city',
            'title' => __('City'),

            'edit'  => 'hidden',
        ),

        //Contact
        'telephone' => array(
            'name'  => 'telephone',
            'title' => __('Telephone'),
        ),

        'address' => array(
            'name'  => 'address',
            'title' => __('Address'),
            'edit'  => array(
                'class' => 'input-xxlarge',
                'attributes' => array(
                    'class' => 'input-xxlarge'
                )
            )
        ),

        'postcode' => array(
            'name'  => 'postcode',
            'title' => __('Postcode'),
        ),


        // Compound fields

        // Compound: Education experiences
        'education'  => array(
            'name'  => 'education',
            'title' => __('Education'),

            // Custom handler
            'handler'   => 'Custom\User\Compound\Education',

            // Fields
            'field' => array(
                'school'    => array(
                    'title' => __('School name'),
                ),
                'department'    => array(
                    'title' => __('Department'),
                ),
                'major'    => array(
                    'title' => __('Major'),
                ),
                'degree'    => array(
                    'title' => __('Degree'),
                ),
                'start'    => array(
                    'title' => __('Start time'),
                ),
                'end'    => array(
                    'title' => __('End time'),
                ),
                'description'   => array(
                    'title' => __('Description'),
                ),
            ),
        ),

        // Compound: Profession experiences
        'work'      => array(
            'name'  => 'work',
            'title' => __('Work'),

            // Custom handler
            'handler'   => 'Custom\User\Compound\Work',

            // Fields
            'field' => array(
                'company'    => array(
                    'title' => __('Company name'),
                ),
                'department'    => array(
                    'title' => __('Department'),
                ),
                'industry'    => array(
                    'title' => __('Industry'),
                    'edit'  => 'Custom\User\Form\Element\Industry',
                ),
                'sector'    => array(
                    'title' => __('Sector'),
                    'edit'  => 'hidden',
                ),
                'position'    => array(
                    'title' => __('Job Position'),
                    'edit'  => array(
                        'element'    => 'select',
                        'attributes' => array(
                            'options' => array(
                                ''                  => __('Select'),
                                __('R&D')           => __('R&D'),
                                __('Management')    => __('Management'),
                                __('Measurement')   => __('Measurement'),
                                __('QA')            => __('QA'),
                                __('Market')        => __('Market'),
                                __('Student')       => __('Student'),
                            ),
                        ),
                    ),
                ),
                'title'    => array(
                    'title' => __('Job title'),
                ),
                'description'   => array(
                    'title' => __('Description'),
                    'edit'  => array(
                        'element' => 'textarea',
                        'attributes' => array(
                            'rows'    => 5,
                            'class'   => 'input-block-level',
                        ),
                    ),
                ),
                'start'    => array(
                    'title' => __('Start time'),
                    'edit'  => 'Custom\User\Form\Element\Time'
                ),
                'end'    => array(
                    'title' => __('End time'),
                    'edit'  => 'hidden'
                ),
            ),
        ),

        // Compound: Profession interests
        'interest'      => array(
            'name'  => 'interest',
            'title' => __('Interests'),

            // Custom handler
            'handler'   => 'Custom\User\Compound\Interest',

            // Fields
            'field' => array(
                'interest' => array(
                    'title' => __('Interest'),
                ),
            ),
        ),

        // Compound: Subscriptions
        'subscription'      => array(
            'name'  => 'subscription',
            'title' => __('Subscriptions'),

            // Custom handler
            'handler'   => 'Custom\User\Compound\Subscription',

            // Fields
            'field' => array(
                'item' => array(
                    'title' => __('Item'),
                ),
            ),
        ),
    ),

    // Timeline logs from modules
    'timeline'  => array(
    ),

    // Activity logs
    'activity'  => array(
    ),

    // Quicklinks
    'quicklink' => array(
    ),

);
