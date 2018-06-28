import 'bootstrap-fileinput';

const uploadUrl = typeof audioUploadUrl !== 'undefined' ? audioUploadUrl : '';

$("#audio-input").fileinput({
  theme: "fa",
  uploadUrl: uploadUrl,
  hideThumbnailContent: true,
  allowedFileExtensions: [
      'wav',
      'flac'
  ],
  showAjaxErrorDetails: false,
  ajaxSettings: {
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  },
  uploadExtraData : function () {
    var obj = {};
    obj['language'] = $('#selectLanguage :selected').val();
    return obj;
  },
}).on('filepreupload', function(event, data, previewId, index) {
    if (!data.extra.language) {
        return {
            error: 'Please select the language format of audio!',
        };
    }
}).on('fileuploaded', function(event, data, previewId, index) {
    $('#selectLanguage').prop('selectedIndex', 0);
});