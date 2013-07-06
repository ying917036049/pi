<?php
/**
 * Asset helper
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) Pi Engine http://www.xoopsengine.org
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 * @package         Pi\View
 * @subpackage      Helper
 */

namespace Pi\View\Helper;

use Pi;
use Zend\View\Helper\AbstractHelper;

/**
 * Helper for building asset URI
 * @see Pi\Application\Service\Asset
 *
 * Usage inside a phtml template:
 * <code>
 *  $cssUri = $this->asset('theme/default', 'css/style.css');
 *  $jsUri = $this->asset('module/demo', 'js/demo.js');
 * </code>
 */
class Asset extends AbstractHelper
{
    /**
     * Get URI of an asset
     *
     * @param   string  $component
     * @param   string  $file
     * @param   bool    $versioning Flag to append version
     * @return  string
     */
    public function __invoke($component, $file, $versioning = true)
    {
        return Pi::service('asset')->getAssetUrl($component, $file, $versioning);
    }
}
