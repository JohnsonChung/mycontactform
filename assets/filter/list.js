$(document).ready(function () {
    var table = $('#word_check_list').dataTable({
        "ajax": FILTER_WORDS_LIST_URI, // 更改為適合您的過濾字詞列表的URI
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.7/i18n/Japanese.json"
        },
        "order": [
            [0, 'asc']
        ],
        "columnDefs": [{
                "targets": 2,
                "data": null,
                "render": function (data, type, full, meta) {
                    var id = data[0], word = data[1];
                    var template_actions = _.template($("#template-actions").html());
                    return type === 'display' ? template_actions({id: id, word: word}) : data;
                }
            }]
    });

    // 其他必要的DataTable設定...
    // 处理新增过滤词
    $('#add-filter-form').on('submit', function (e) {
        e.preventDefault();
        var word = $('#new-filter-word').val();
        $.ajax({
            url: '您的添加过滤词后端处理URL',
            type: 'POST',
            data: { filter_word: word },
            success: function(response) {
                // 添加成功后的操作，例如刷新列表
                $('#word_check_list').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                // 错误处理
            }
        });
    });

    // 处理删除过滤词
    $('#word_check_list tbody').on('click', '.delete-word', function () {
        var id = $(this).data('id');
        if(confirm('确定删除这个过滤词吗?')) {
            $.ajax({
                url: '您的删除过滤词后端处理URL/' + id,
                type: 'POST',
                success: function(response) {
                    // 删除成功后的操作，例如刷新列表
                    $('#word_check_list').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    // 错误处理
                }
            });
        }
    });
});

