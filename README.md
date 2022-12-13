<p align="center"><img width="150" src="https://raw.githubusercontent.com/Block2Paz/Flurga/main/public/img/favicon.ico"></p>
<h1 align="center">Flurga</h1>
<p align="center">Flurga is a web interface for Frigate NVR build with "Bootstrap Italia" theme<br><br><img src=https://img.shields.io/github/issues/Block2Paz/Flurga>  <img src=https://img.shields.io/github/license/Block2Paz/Flurga> <img src=https://img.shields.io/github/stars/Block2Paz/Flurga></p>

## Installation with docker compose
```
version: "3"

services:
  flurga:
    image: bthuderous/flurga:latest
    container_name: flurga
    restart: unless-stopped
    ports:
      - 8080:8080 # Web server port
    volumes:
      - /home/user/flurga/app.ini:/flurga/app.ini
```

## Features
- Simple login system
- View and download custom length recording
- View and delete events
- View all recordings

## Config file
!! ATTENTION !! Edit app.ini file config with your Frigate IP and cameras
```
[config]
; Frigate ip with no http:// or https://
ip = "192.168.144.16:5000"

; Login password
password = "default123"

; Set your timezone
tz = "Europe/Rome"

; Camera list - format: cameras[] = "CAMERANAME"
cameras[] = "CAM1"
cameras[] = "CAM2"
```

## Problems / Questions
As with any beta, there may be some bugs and frequent updates, but we encourage you to report any issues!<br><br>
<b>Email:</b> flurga@vcardone.it - <b>Discord:</b> Block2Paz#4884

## Screenshot
<p align="center"><img src="https://vcardone.it/imgs/flurgalogin.png"></p>
<img src="https://vcardone.it/imgs/F4.png">
<a href="https://imgur.com/a/cF40RAp">Other images</a>
