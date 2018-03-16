import 'bootstrap-fileinput';

const uploadUrl = typeof audioUploadUrl !== 'undefined' ? audioUploadUrl : '';

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