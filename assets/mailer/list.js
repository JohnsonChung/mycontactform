$(document).ready(function () {
    var table = $('#mailer_list').dataTable({
        "ajax": MAILER_LIST_URI,
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
                    var id = data[0], mail = data[1];
                    var template_actions = _.template($("#template-actions").html());
                    return type === 'display' ? template_actions({id: id, mail: mail}) : data;
                }
            }]
    });

    var tt = new $.fn.dataTable.TableTools(table, {
        "aButtons": [
        ],
        "sSwfPath": "https://cdn.datatables.net/tabletools/2.2.4/swf/copy_csv_xls_pdf.swf"
    });
    $(tt.fnContainer()).insertBefore('#mailer_list');

    window['deleteMailer'] = function (id, mail) {
        if (!confirm('このメール (' + mail + ') を削除しますか？'))
            return false;
        $("form[data-id=" + id + "]").submit();
    }
});