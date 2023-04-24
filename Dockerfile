FROM python:alpine3.17

RUN mkdir -p /home/Flurga

COPY . /home/Flurga

WORKDIR /home/Flurga

ENV PYTHONDONTWRITEBYTECODE 1

ENV PYTHONUNBUFFERED 1

RUN pip install -r requirements.txt

RUN python manage.py migrate

ENV DJANGO_SUPERUSER_USERNAME admin
ENV DJANGO_SUPERUSER_PASSWORD admin
ENV DJANGO_SUPERUSER_EMAIL admin@admin.com

RUN python manage.py createsuperuser --noinput

EXPOSE 1923/tcp

CMD python manage.py runserver --insecure 0.0.0.0:1923