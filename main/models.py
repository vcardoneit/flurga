from django.db import models


class config(models.Model):
    frigateIP = models.CharField(max_length=255)
    cams = models.CharField(max_length=255)
