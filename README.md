<p align="center"><img width="150" src="https://raw.githubusercontent.com/Block2Paz/Flurga/main/public/img/favicon.ico"></p>
<h1 align="center">Flurga</h1>
<p align="center">Flurga is a web interface for Frigate NVR build with "Bootstrap Italia" theme<br><br><img src=https://img.shields.io/github/issues/Block2Paz/Flurga>  <img src=https://img.shields.io/github/license/Block2Paz/Flurga> <img src=https://img.shields.io/github/stars/Block2Paz/Flurga></p>

## Features
- **Login system**
- **Multiple Frigate instances support**
- **View and download custom length recording**
- **Delete all events at once**
- **Multi language (en, es, fr, it)**
- **View and delete events**
- **View recordings**

## Installation with Docker
#### Docker compose
```yaml
version: '3.3'
services:
    flurga:
        container_name: Flurga
        restart: unless-stopped
        ports:
            - '8080:8080'
        volumes:
            - '~/flurga/config.yml:/flurga/config.yml'
        image: 'bthuderous/flurga:latest'
```
#### Docker run
```
docker run -d --name Flurga --restart unless-stopped -p 8080:8080 -v '~/flurga/config.yml:/flurga/config.yml' bthuderous/flurga:latest
```
<a href="https://hub.docker.com/r/bthuderous/flurga">Docker Image (bthuderous/flurga)</a>

## Config file
```yaml
flurga:
  username: admin
  password: default123
  timezone: Europe/Rome
  lang: en # Supported: en, es, fr, it
  
frigate:
  0: # Progressive number
    ip: 192.168.144.16:5000 # Frigate IP
    cameras:
      - CAM1
      - CAM2

  1:
    ip: 192.168.144.20:5000
    cameras:
      - CAM3
      - CAM4
```

## Problems / Questions
As with any beta, there may be some bugs and frequent updates, but we encourage you to report any issues!<br><br>
<b>Email:</b> flurga@vcardone.it - <b>Discord:</b> Block2Paz#4884

## Screenshot
<p align="center"><img src="images/login.png"></p>
<p align="center"><img src="images/home.png"></p>
<p align="center"><img src="images/events.png"></p>
<p align="center"><img src="images/recordings.png"></p>
