import 'datatables.net';
import 'eonasdan-bootstrap-datetimepicker';

const tableResultConvert = $('.container-result-convert .table').DataTable({
    "paging": false,
    "searching": false,
    "columnDefs": [
        {
        "targets": [0, 1, 2, 3, 4],
        "searchable": false,
        "orderable": false,
        "visible": true,
        }
    ],
});

$("#dateFrom").datetimepicker({
    format: 'YYYY-MM-DD 00:00:00',
    showClear: true,
});

$("#dateTo").datetimepicker({
    format: 'YYYY-MM-DD 23:59:00',
    showClear: true,
});

$(window).ready(function () {
    $(".container-result-convert table thead tr th:nth-child(1)").removeClass('sorting_asc');

    const $sortClass = $sort == 'desc' ? 'sorting_desc' : 'sorting_asc';
    $(".container-result-convert table thead tr th:nth-child(4)").addClass($sortClass);
});

$(document).on('click', '#sortDate', (e) => {
    const $target = $(e.target);
    window.location.href = $target.data('sort');
});

$(document).on('click', '.modal-dialog .close span, .container-result-convert .modal', (e) => {
    const $target = $(e.target);
    document.getElementById("audio-" + $target.data('pause')).pause();
});

$(document).on('click', '.container-result-convert .glyphicon-floppy-remove', (e) => {
    const $target = $(e.target);
    if (confirm('Are you sure want to delete?'))
        document.getElementById("deleteForm" + $target.data('delete')).submit();
    return false;
});

$(document).on('click', '.container-result-convert .glyphicon-download-alt', (e) => {
    const $targetData = $(e.target).data();
    const $filename = $targetData.filename.split('.')[0] + '.txt';

    const $element = document.createElement('a');
    $element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent($targetData.content));
    $element.setAttribute('download', $filename);

    $element.style.display = 'none';
    document.body.appendChild($element);

    $element.click();
    document.body.removeChild($element);
});
