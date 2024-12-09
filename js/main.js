var datatablesOptions = {
    "serverSide": true,
    "ajax": {
        "url": ajax.url,
        "type": "POST" // or "GET" depending on your server-side implementation
    },
    "processing": true,
    "columns": [
        null,
        null,
        null,
        null,
        /* EDIT */ {
            mRender: function (data, type, row) {
                return '<a class="table-edit" data-id="' + row[0] + '">EDIT</a>';
            }
        },
        /* DELETE */ {
            mRender: function (data, type, row) {
                return '<a class="table-delete" data-id="' + row[0] + '">DELETE</a>';
            }
        }
    ]
};

$(document).ready(function () {
    $('#usertable').DataTable(datatablesOptions);
});
