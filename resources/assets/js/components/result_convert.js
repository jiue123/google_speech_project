import 'datatables.net';
import 'eonasdan-bootstrap-datetimepicker';

const tableResultConvert = $('.container-result-convert .table').DataTable({
  "paging": false,
  "searching": false,
  "columnDefs": [
    {
      "targets": [0, 1, 2, 4],
      "searchable": false,
      "orderable": false,
      "visible": true,
    }
  ],
  "order": [
    [3, 'desc']
  ],
});

$("#dateFrom").datetimepicker({
  format: 'YYYY-MM-DD 00:00:00'
});

$("#dateTo").datetimepicker({
  format: 'YYYY-MM-DD 23:59:00'
});
