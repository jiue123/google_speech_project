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
});