$(document).ready(function () {
    // 載入現有的過濾詞並顯示在 textarea 中
    function loadFilterWords() {
        $.ajax({
            url: '/get-filters', // 確保這個 URL 是指向你的後端路由的
            type: 'GET',
            success: function (data) {
                // 將獲得的過濾詞陣列轉換成換行分隔的字符串
                var wordsText = data.join("\n");
                $('#filter-words').val(wordsText);
            },
            error: function () {
                alert('無法加載過濾詞列表。請檢查後端服務。');
            }
        });
    }

    // 初始化時載入過濾詞
    loadFilterWords();

    // 處理過濾詞表單提交
    $('#edit-filter-form').on('submit', function (e) {
        e.preventDefault();
    
        // 从 textarea 中获取过滤词
        var words = $('#filter-words').val();
    
        // 发送更新过滤词的请求到后端
        $.ajax({
            url: '/edit-filter', // 确保这是正确的后端路由
            type: 'POST',
            data: { filter_words: words },
            success: function (response) {
                if (response.success) {
                    alert("过滤词已更新！");
                    loadFilterWords(); // 重新载入更新后的过滤词
                } else {
                    alert("更新失败：" + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert("更新失败：" + error);
            }
        });
    });
    
});
