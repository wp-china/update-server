<?php
/**
 * Update-Server
 *
 * @copyright  Copyright (c) 2020 - WP-China-Yes
 * @license    http://www.gnu.org/licenses/gpl-3.0.html  GPLv3 License
 */
define('NEW_VERSION', '2.1.0');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.wordpress.org/plugins/update-check/1.1/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
$response_str = curl_exec($ch);
curl_close($ch);

$plugins = json_decode($_POST['plugins'], true)['plugins'];
foreach ($plugins as $plugin) {
    if ($plugin['Name'] === 'WP-China-Yes') {
        $old_version_list = explode('.', (string)$plugin['Version']);
        $now_version_list = explode('.', (string)NEW_VERSION);

        $len = max(count($old_version_list), count($now_version_list));

        for ($i = 0; $i < $len; $i++) {
            $old_version_list[$i] = intval(@$old_version_list[$i]);
            $now_version_list[$i] = intval(@$now_version_list[$i]);

            if ($old_version_list[$i] < $now_version_list[$i]) {
                $response_array = json_decode($response_str, true);
                $response_array['plugins']['wp-china-yes/index.php'] = [
                    'id' => 'w.org/plugins/wp-china-yes',
                    'slug' => 'wp-china-yes',
                    'plugin' => 'wp-china-yes/index.php',
                    'new_version' => NEW_VERSION,
                    'url' => 'https://wordpress.org/plugins/wp-china-yes/',
                    'package' => 'http://downloads.wordpress.org/plugin/wp-china-yes.' . NEW_VERSION . '.zip',
                    'icons' => [
                        '1x' => 'https://ps.w.org/wp-china-yes/assets/icon-128x128.jpg'
                    ],
                    'banners' => [],
                    'banners_rtl' => [],
                    'tested' => '5.4',
                    'requires_php' => '5.6',
                    'compatibility' => []
                ];

                echo json_encode($response_array, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
}

echo $response_str;