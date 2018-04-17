const speech = require('@google-cloud/speech');

var client = new speech.v1.SpeechClient({
    keyFilename: '.credentials/Speech_API-3b1d7a513a31.json',
    projectId: 'speech-api-196408',
});