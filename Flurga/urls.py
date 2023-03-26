from django.conf.urls.i18n import i18n_patterns
from django.urls import path, include

urlpatterns = i18n_patterns(
    path('', include('main.urls')),
)
