$(document).ready(function () {
    var table = $('#enquiry_list').dataTable({
        "ajax": ENQUIRY_LIST_URI,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.7/i18n/Japanese.json"
        },
        "order": [
            [0, 'desc']
        ],
        "columnDefs": [{
                "targets": 4,
                "data": null,
                "render": function (data, type, full, meta) {
                    var content = data[4].substr(0, 50);
                    var link = ENQUIRY_SHOW_URI.replace(/{id}/, data[0]);
                    return type === 'display' ? '<a href="' + link + '">' + content + "</a>" : data;
                }
            }]
    });

    var tt = new $.fn.dataTable.TableTools(table, {
        "aButtons": [
            "print"
        ],
        "sSwfPath": "https://cdn.datatables.net/tabletools/2.2.4/swf/copy_csv_xls_pdf.swf"
    });
    $(tt.fnContainer()).insertBefore('#enquiry_list');

});