<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt BSD 3-Clause License
 */

namespace Module\System\Form;

use Pi;
use Pi\Form\Form as BaseForm;

/**
 * Class for initializing form of find password
 *
 * @author Liu Chuang <liuchuang@eefocus.com>
 */
class FindPasswordForm extends BaseForm
{

    public function init()
    {
        $this->add(array(
            'name'       => 'email',
            'options'    => array(
                'label' => __('Email'),
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $captchaMode = Pi::user()->config('register_captcha');
        if($captchaElement = Pi::service('form')->getReCaptcha($captchaMode)){
            $this->add($captchaElement);
        }

        $this->add(array(
            'name' => 'security',
            'type' => 'csrf',
        ));

        $this->add(array(
            'name'  => 'submit',
            'type'  => 'submit',
            'attributes'    => array(
                'value' => __('Find password'),
            ),
        ));
    }
}