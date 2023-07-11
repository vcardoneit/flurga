<p align="center"><img width="150" src="https://raw.githubusercontent.com/Block2Paz/Flurga/main/static/img/favicon.ico"></p>
<h1 align="center">Flurga</h1>
<p align="center">Flurga is a web interface for Frigate NVR build with "Bootstrap Italia" theme<br><br><img src=https://img.shields.io/github/issues/Block2Paz/Flurga>  <img src=https://img.shields.io/github/license/Block2Paz/Flurga> <img src=https://img.shields.io/github/stars/Block2Paz/Flurga></p>

## Features
- **Login system**
- **Simple dashboard**
- **Multiple Frigate instances support**
- **View and download custom length recording**
- **Delete all events at once**
- **Multi language (de, en, es, fr, it)**
- **View and delete events**
- **View recordings**

## Installation with Docker
#### Docker compose
```yaml
version: '3.3'

volumes:
  flurga:

services:
  flurga:
    container_name: Flurga
    restart: unless-stopped
    volumes:
      - flurga:/home/Flurga
    ports:
      - '1923:1923'
    image: 'ghcr.io/vcardoneit/flurga'
```

#### Environment Variables
| Environment Variable  | Purpose | Default |
| ------------- | ------------- | ------------- |
| `TIME_ZONE`  | Your local timezone in <a href="https://timezonedb.com/time-zones">TZ Name</a> format  | `Europe/Rome`  |
| `SECRET_KEY`  | Django secret key  | `Random string`  |
| `DEBUG`  | Enable or disable debug (True/False)  | `False`  |
| `DJANGO_SUPERUSER_USERNAME`  | Username for login  | `admin`  |
| `DJANGO_SUPERUSER_PASSWORD`  | Password for login  | `admin`  |

## Config
At the first login you will be redirected to the dashboard, where you can set the IP of Frigate, and the name of cameras, separated by comma.

Also in the dashboard, you can logout from the session, change the password, change language, and edit/add/remove instances of Frigate (See screenshots below)

## Problems / Questions
There may be some bugs and frequent updates, but we encourage you to report any issues!<br><br>
<b>Email:</b> flurga@vcardone.it - <b>Discord:</b> thuderous

## Screenshot
<p align="center"><img src="https://i.ibb.co/B67Mrn7/Home.png"></p>
<p align="center"><img src="https://i.ibb.co/R3MYDHz/Dashboard.png"></p>
<p align="center"><img src="https://i.ibb.co/qgTF0tF/Dashboard-Conf.png"></p>
<p align="center"><img src="https://i.ibb.co/2sMF0hc/Other.png"></p>
