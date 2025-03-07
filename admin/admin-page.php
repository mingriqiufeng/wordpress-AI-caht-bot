<?php
add_action('admin_menu', function() {
    add_options_page(
        'DeepSeek 设置',
        'DeepSeek Chat',
        'manage_options',
        'deepseek-settings',
        'deepseek_settings_page'
    );
});

add_action('admin_init', function() {
    register_setting('deepseek_options', 'deepseek_api_key');
    
    add_settings_section(
        'deepseek_main',
        'API 配置',
        null,
        'deepseek-settings'
    );

    add_settings_field(
        'deepseek_api_key',
        'API Key',
        function() {
            $value = get_option('deepseek_api_key');
            echo '<input type="password" name="deepseek_api_key" value="' . esc_attr($value) . '" style="width:300px;">';
        },
        'deepseek-settings',
        'deepseek_main'
    );
});

function deepseek_settings_page() {
    ?>
    <div class="wrap">
        <h1>DeepSeek 聊天机器人设置</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('deepseek_options');
            do_settings_sections('deepseek-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
