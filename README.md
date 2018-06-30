Google Speech API
==============

This project use Google Cloud Speech-to-Text enables developers to ``convert audio to text`` by applying powerful 
``neural network models`` in an easy to use API.

# Before Installation

We need to install:

* [Git](https://git-scm.com/downloads)
* [Node](https://nodejs.org/en/download/)
* [Docker(CE)](https://www.docker.com/community-edition#/download)

# Installation

Create a folder ``speech-api`` inside this folder clone these below two projects.

```sh
git clone git@github.com:jiue123/laradock_customized.git
git clone git@github.com:jiue123/google_speech_project.git
```

Append the below content to the end of host file for assigning domain to local ip

(For windows 10 the file is located in C:\Windows\System32\drivers\etc\hosts)

(For Mac the file is located in /etc/hosts)
> ``127.0.0.1 audio.local``

## Step 1: Config ``laradock``

Go to the folder ``laradock_customized``.

Switch branch to `feature/audio_convert`.

Copy ``.env-example`` to ``.env`` file.

Inside folder ``nginx/sites/``, copy ``laravel.conf.example`` file to ``audio.conf`` file.

Open ``audio.conf`` file and edit these below lines, then save.

```sh
server_name audio.local;
root /var/www/google_speech_project/public;
```

Inside folder ``laradock_customized`` run command:

> ``docker-compose up -d nginx mysql php-worker phpmyadmin``

Use command ``docker ps -a`` to check if container ``mysql`` and ``nginx`` is running or not. If running it's ok.

## Step 2: Config google-speech project

Go to the folder ``google_speech_project``.

Switch branch to `develop`.

Copy ``.env-example`` to ``.env`` file.

Edit these below lines of ``.env`` file.

```
APP_URL=http://audio.local

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=root
DB_PASSWORD=root
```

### #Step 2.1
Go to the folder ``laradock_customized``.

Run command:
> ``docker-compose exec workspace bash``

Inside container ``workspace`` run command:
> ``cd /var/www/google_speech_project``
>
> ``composer install``
>
> ``php artisan key:generate``
>
> ``php artisan migrate``
>
> ``php artisan db:seed``
>
> ``exit``

### #Step 2.2

Go to the folder ``google_speech_project``.

Run command:
> ``npm install``
>
> ``npm run dev``

## Step 3 Enable google services and create service account key

### #Step 3.1 Enable services needed

[Cloud Speech API](https://console.cloud.google.com/apis/library/speech.googleapis.com?id=7ae3f475-64e7-4123-880a-a889f34fa714&project=speech-api-196408&folder&organizationId)

[Google Cloud Storage](https://console.cloud.google.com/apis/library/storage-component.googleapis.com?id=466e130e-03f7-4da9-965c-10f7e2cf0bd1&project=speech-api-196408&folder&organizationId)

After `enable` Google Cloud Storage, go to ``https://console.cloud.google.com/storage`` and create a bucket
> Example bucket name is ``bucket0891``

### #Step 3.2 Create service account key.

Follow step by step in below link for create service account key.
[Setting up authentication](https://cloud.google.com/speech-to-text/docs/reference/libraries#client-libraries-install-php)

After have ``json`` file, create folder ``.credentials`` inside folder ``google_speech_project`` and put the json file on this folder.

### #Step 3.3 Config .env file for provide authentication credentials to your application

Open ``.env`` file inside folder ``google_speech_project``

Find rows as below:
```sh
#Google Credentials
GOOGLE_PROJECT_ID=
GOOGLE_APPLICATION_CREDENTIALS=
```

Type google project ID
> Example: ``GOOGLE_PROJECT_ID=speech-api-111111``

Type path to ``json`` file inside the folder ``.credentials``
> Example: ``GOOGLE_APPLICATION_CREDENTIALS=".credentials/Speech_API-12ab34cd56.json"``

### #Step 3.4 Fill information ``bucket`` of google storage service

Find rows as below:
```sh
#Google Storage
USE_GOOGLE_STORAGE=
STORAGE_BUCKET_NAME=
STORAGE_BUCKET_URL=
```

Set value for ``USE_GOOGLE_STORAGE`` by ``true``.
> ``USE_GOOGLE_STORAGE=true``

Type ``bucket`` name.
> Example: ``STORAGE_BUCKET_NAME=bucket0891``

Set value for ``STORAGE_BUCKET_URL``
> ``STORAGE_BUCKET_URL=http://storage.googleapis.com/``

# Test
Open browser and type ``http://audio.local`` to see the result.

user: ``admin@gmail.com``

pass: ``secret``

# Note
 - This project have storage service ``s3(AWS)`` and ``Blob(Azure)``. You can config for use the services in file ``.env`` 
 inside folder ``google_speech_project``
 ```sh
    FILESYSTEM_DRIVER=
    
    #Microsoft Azure
    AZURE_BLOB_STORAGE_ENDPOINT=
    AZURE_BLOB_STORAGE_CONTAINER=
    AZURE_BLOB_SERVICE_URL=
    
    #AWS Amazon
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=
    AWS_VERSION=
    AWS_BUCKET=
    AWS_BUCKET_URL=
 ```
> \#With s3 service
>
> ``FILESYSTEM_DRIVER=s3``
>
> \#With Blob service
>
> ``FILESYSTEM_DRIVER=azure``

If use ``s3(AWS)`` or ``Blob(Azure)`` must set ``USE_GOOGLE_STORAGE`` by ``false`` or blank.

 - But i recommend using the ``google storage`` service because with longer audio more than 1 minutes you must use 
 ``Google Cloud Storage objects`` as input.
 - In this project just support convert speech to text with audio file with format ``.wav(8000 Hz)`` and ``.flac(16000 Hz)``

You can find out more formats for audio file in link  [AudioEncoding](https://cloud.google.com/speech-to-text/docs/reference/rest/v1/RecognitionConfig#AudioEncoding)

Add the format you want into ``google_speech_project/config/google.php``.
```sh
google_speech_options_convert' => [
    'wav' => [
        'encoding' => 'MULAW',
        'sampleRateHertz' => 8000,
    ],
    'flac' =>[
        'encoding' => 'FLAC',
        'sampleRateHertz' => 16000,
    ],
]
```
