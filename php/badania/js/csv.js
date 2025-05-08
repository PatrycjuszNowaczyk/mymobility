jQuery(document).ready( function ($) {

    function convertArrayOfObjectsToCSV(args) {
        var result, ctr, keys, columnDelimiter, lineDelimiter, data;

        data = args.data || null;
        if (data == null || !data.length) {
            return null;
        }

        columnDelimiter = args.columnDelimiter || ';';
        lineDelimiter = args.lineDelimiter || '\n';

        keys = Object.keys(data[0]);

        result = '';
        // result += keys.join(columnDelimiter);
        // result += lineDelimiter;

        data.forEach(function(item) {
            ctr = 0;
            keys.forEach(function(key) {
                if (ctr > 0) result += columnDelimiter;

                result += '"' + item[key] + '"';
                ctr++;
            });
            result += lineDelimiter;
        });

        return result;
    }

    function downloadCSV(args) {
        var data, filename, link;
        var csv = convertArrayOfObjectsToCSV({
            data: args.array
        });
        if (csv == null) return;

        filename = args.filename || 'export.csv';

        if (!csv.match(/^data:text\/csv/i)) {
            csv = 'data:text/csv;charset=utf-8,' + csv;
        }
        data = encodeURI(csv);

        link = document.createElement('a');
        link.setAttribute('href', data);
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    $(document).on('click', '#pobierz-badania', function(e) {
        e.preventDefault();
        const button = $(this);
        // const id = button.data('id');
        const name = 'wyniki_' + $(this).data('current-time');

        $.ajax({ 
            'url': ajaxurl,
            'type': 'POST',
            'data': {
                'action': 'generuj_csv',
                // 'id' : id,
            },
            beforeSend: function() { 
                button.closest('.content-wyniki').addClass('loading');
            },
            success: function ( data ) {
                button.closest('.content-wyniki').removeClass('loading'); 
                // console.log(data);
                let array = JSON.parse(data);
                downloadCSV({array: array, filename: name});
            }
        });
        
    });



} );
