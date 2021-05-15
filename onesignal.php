<?php
/*
   Plugin Name: OneSignal Registration
   description: >-
   Version: 1.2
   Author: Md Adil
   Author URI: https://md-adil.github.io
   License: GPL2
*/

add_action('user_register', function ($user_id) {
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return;
    }
    $option = get_option('OneSignalWPSetting');
    if (!$option) {
        return;
    }
    $appId = $option['app_id'];
    $hash = hash_hmac('sha256', $user->user_email, $appId);
    $data = [
        'app_id' => $appId,
        'identifier' => $user->user_email,
        'device_type' => 11,
        'email_auth_hash' => $hash,
        'tags' => ['user' => $user_id],
    ];
    wp_remote_post('https://onesignal.com/api/v1/players', [
        'headers' => [
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode($data),
    ]);
},  10, 1);
