<p align="center"><img width="150" src="https://raw.githubusercontent.com/Block2Paz/Flurga/main/public/img/favicon.ico"></p>
<h1 align="center">Flurga</h1>
<p align="center">Flurga is a web interface for Frigate NVR build with "Bootstrap Italia" theme<br><br><img src=https://img.shields.io/github/issues/Block2Paz/Flurga>  <img src=https://img.shields.io/github/license/Block2Paz/Flurga> <img src=https://img.shields.io/github/stars/Block2Paz/Flurga></p>

## Features
- **Simple login system**
- **View and download custom length recording**
- **View and delete events**
- **Delete all events at once**
- **View all recordings**

## Installation with docker compose
<a href="https://hub.docker.com/r/bthuderous/flurga">Docker Image (bthuderous/flurga)</a>
```yaml
version: "3"

services:
  flurga:
    image: bthuderous/flurga:latest
    container_name: Flurga
    restart: unless-stopped
    ports:
      - 8080:8080
    volumes:
      - /home/user/flurga/config.yml:/flurga/config.yml
```

## Config file
```yaml
flurga:
  # Username and password for Flurga login
  username: admin
  password: default123
  timezone: Europe/Rome
  lang: en
  
frigate:
  # Frigate IP:PORT
  host: 192.168.144.16:5000

  # Cameras list
  cameras:
    - CAM1
    - CAM2
```

## Problems / Questions
As with any beta, there may be some bugs and frequent updates, but we encourage you to report any issues!<br><br>
<b>Email:</b> flurga@vcardone.it - <b>Discord:</b> Block2Paz#4884

## Screenshot
<p align="center"><img src="images/login.png"></p>
<p align="center"><img src="images/home.png"></p>
<p align="center"><img src="images/events.png"></p>
<p align="center"><img src="images/recordings.png"></p>
