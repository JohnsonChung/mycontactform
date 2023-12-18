$(document).ready(function () {
    var table = $('#user_list').dataTable({
        "ajax": USER_LIST_URI,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.7/i18n/Japanese.json"
        },
        "order": [
            [0, 'asc']
        ],
        "columnDefs": [{
                "targets": 4,
                "data": null,
                "render": function (data, type, full, meta) {
                    var id = data[0], name = data[1], isAdmin = data[3];
                    var template_actions = _.template($("#template-actions").html());
                    return type === 'display' ? template_actions({id: id, name: name, isAdmin: isAdmin}) : data;
                }
            }]
    });

    var tt = new $.fn.dataTable.TableTools(table, {
        "aButtons": [
        ],
        "sSwfPath": "https://cdn.datatables.net/tabletools/2.2.4/swf/copy_csv_xls_pdf.swf"
    });
    $(tt.fnContainer()).insertBefore('#user_list');

    window['deleteUser'] = function (id, name) {
        if (!confirm('このユーザー (' + name + ') を削除しますか？'))
            return false;
        $("form[data-id=" + id + "]").submit();
    }
});