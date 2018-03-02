import $ from 'jquery';
import 'bootstrap-fileinput';

const uploadUrl = audioUploadUrl;

$("#audio-input").fileinput({
  theme: "fa",
  uploadUrl: uploadUrl,
  hideThumbnailContent: true,
  allowedFileExtensions: ['wav'],
  showAjaxErrorDetails: false,
  ajaxSettings: {
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }
}).on('fileuploaded', function(event, data, previewId, index) {
  var form = data.form, files = data.files, extra = data.extra,
    response = data.response, reader = data.reader;
  console.log('File uploaded triggered');
});