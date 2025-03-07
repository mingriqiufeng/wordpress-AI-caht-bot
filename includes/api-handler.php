<?php
add_action('wp_ajax_deepseek_chat', 'handle_deepseek_chat');
add_action('wp_ajax_nopriv_deepseek_chat', 'handle_deepseek_chat');

function handle_deepseek_chat() {
    check_ajax_referer('deepseek_chat_nonce', 'nonce');
    
    $api_key = get_option('deepseek_api_key');
    if (!$api_key) {
        wp_send_json_error('请先在后台配置API密钥', 401);
    }

    $message = sanitize_text_field($_POST['message']);
    if (empty($message)) {
        wp_send_json_error('消息内容不能为空', 400);
    }

    $response = wp_remote_post('https://api.deepseek.com/v1/chat/completions', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key
        ],
        'body' => json_encode([
            'model' => 'deepseek-chat',
            'messages' => [[
                'role' => 'user',
                'content' => $message
            ]],
            'temperature' => 0.7,
            'stream' => false
        ]),
        'timeout' => 30
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error('请求失败: ' . $response->get_error_message(), 500);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['choices'][0]['message']['content'])) {
        wp_send_json_success(wp_kses_post($body['choices'][0]['message']['content']));
    } else {
        wp_send_json_error('API响应格式错误: ' . print_r($body, true), 500);
    }
}
