<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '\..\..\..\..\app\code\core\Mage\Core\functions.php';

$includePaths = array(
    '..\..\..\..\app',
    '..\..\..\..\app\code\core',
    '..\..\..\..\app\code\local',
    '..\..\..\..\lib',
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $includePaths));
spl_autoload_register('magentoAutoloadForUnitTests');

function magentoAutoloadForUnitTests($class)
{
    $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
        $fileName = $path . DIRECTORY_SEPARATOR . $file;
        if (file_exists($fileName)) {
            include $file;
            if (class_exists($class, false) || interface_exists($class, false)) {
                return true;
            }
        }
    }
    return false;
}
