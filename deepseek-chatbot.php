<?php
/**
 * Plugin Name: DeepSeek Chatbot
 * Description: 集成DeepSeek V3大模型的智能聊天机器人
 * Version: 1.0.0
 * Author: Your Name
 */

defined('ABSPATH') || exit;

// 注册设置和API处理
require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/api-handler.php';

// 加载前端资源
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('deepseek-chat-style', plugins_url('assets/css/chatbot.css', __FILE__));
    wp_enqueue_script('deepseek-chat-script', plugins_url('assets/js/chatbot.js', __FILE__), ['jquery'], null, true);
    
    wp_localize_script('deepseek-chat-script', 'deepseekData', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('deepseek_chat_nonce')
    ]);
});

// 注入聊天窗口
add_action('wp_footer', function() {
    ?>
    <div id="deepseek-chat-container">
        <div class="chat-header">
            <h3><img src="<?php echo plugins_url('assets/logo.svg', __FILE__); ?>" alt="AI">智能助手</h3>
            <button class="toggle-chat">−</button>
        </div>
        <div class="chat-messages"></div>
        <div class="chat-input">
            <textarea placeholder="输入消息..."></textarea>
            <button class="send-btn">发送</button>
        </div>
    </div>
    <?php
});
