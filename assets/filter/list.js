$(document).ready(function () {
    function loadFilterWords() {
        $.ajax({
            url: '/get-filters',
            // Test server https://www.j-quest.jp/contact3_testing/
            type: 'GET',
            success: function (data) {
                var wordsText = data.join("\n");
                $('#filter-words').val(wordsText);
            },
            error: function () {
                noty({
                    type: 'error',
                    text: '回線の原因でロード失敗しました。',
                    timeout: 3000  // Close the noty after 3 seconds
                });
            }
        });
    }

    loadFilterWords();

    $('#edit-filter-form').on('submit', function (e) {
        e.preventDefault();
        var words = $('#filter-words').val();
    
        $.ajax({
            url: '/edit-filter',
            type: 'POST',
            data: { filter_words: words },
            success: function (response) {
                if (response.success) {
                    noty({
                        type: 'success',
                        text: "フィルターは成功にアップデートしました！",
                        timeout: 3000
                    });
                    loadFilterWords();
                } else {
                    noty({
                        type: 'error',
                        text: "データーベースのアップデートが失敗しました：" + response.message,
                        timeout: 3000
                    });
                }
            },
            error: function (xhr, status, error) {
                noty({
                    type: 'error',
                    text: "回線の原因でアップデート失敗しました：" + error,
                    timeout: 3000
                });
            }
        });
    });
});
