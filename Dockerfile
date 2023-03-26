FROM python:alpine3.17

ENV FlurgaPath = /home/Flurga

RUN mkdir -p $FlurgaPath

COPY . $FlurgaPath

WORKDIR $FlurgaPath

ENV PYTHONDONTWRITEBYTECODE 1

ENV PYTHONUNBUFFERED 1

RUN pip install -r requirements.txt

RUN python -c "from django.core.management.utils import get_random_secret_key; print(get_random_secret_key())" > /home/skey.txt

ENV TIME_ZONE Europe/Rome

RUN python manage.py migrate

ENV DJANGO_SUPERUSER_USERNAME admin
ENV DJANGO_SUPERUSER_PASSWORD admin
ENV DJANGO_SUPERUSER_EMAIL admin@admin.com

RUN python manage.py createsuperuser --noinput

EXPOSE 1923/tcp

CMD python manage.py runserver --insecure 0.0.0.0:1923