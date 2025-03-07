jQuery(document).ready(function($) {
    const container = $('#deepseek-chat-container');
    const toggleBtn = $('#deepseek-chat-container .toggle-chat');
    const messagesArea = $('.chat-messages');
    const textarea = $('textarea');
    const sendBtn = $('.send-btn');

    // 切换聊天窗口状态
    container.on('click', '.toggle-chat', function(e) {
        e.stopPropagation();
        const wasMinimized = container.hasClass('minimized');
        container.toggleClass('minimized');
        
        // 控制消息区域显示
        messagesArea.toggle(!wasMinimized);
        
        // 展开时自动聚焦输入框
        if (wasMinimized) {
            textarea.focus();
            messagesArea.scrollTop(messagesArea[0].scrollHeight);
        }
    });

    // 发送消息处理
    function sendMessage() {
        const message = textarea.val().trim();
        if (!message) return;

        // 禁用按钮防止重复提交
        sendBtn.prop('disabled', true);
        
        // 添加用户消息
        messagesArea.append(`
            <div class="user-msg">
                <div class="bubble">${message}</div>
            </div>
        `);
        
        // 清空输入框
        textarea.val('');
        messagesArea.scrollTop(messagesArea[0].scrollHeight);

        // 显示加载状态
        messagesArea.append('<div class="loading">思考中...</div>');

        // AJAX请求
        $.ajax({
            url: deepseekData.ajaxurl,
            type: 'POST',
            data: {
                action: 'deepseek_chat',
                message: message,
                nonce: deepseekData.nonce
            },
            success: function(response) {
                $('.loading').remove();
                messagesArea.append(`
                    <div class="bot-msg">
                        <div class="bubble">${response.data}</div>
                    </div>
                `);
                messagesArea.scrollTop(messagesArea[0].scrollHeight);
            },
            error: function(xhr) {
                $('.loading').remove();
                messagesArea.append(`
                    <div class="error-msg">
                        <div class="bubble">请求失败，请稍后重试</div>
                    </div>
                `);
            },
            complete: function() {
                sendBtn.prop('disabled', false);
            }
        });
    }

    // 绑定发送事件
    sendBtn.on('click', sendMessage);
    textarea.on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});
