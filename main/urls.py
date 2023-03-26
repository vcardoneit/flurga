from django.urls import path
from . import views

urlpatterns = [
    path('', views.index, name="index"),

    path('login', views.login, name="login"),
    path('logout', views.logout, name="logout"),
    path('changepw', views.changepw, name="changepw"),

    path('events', views.events, name="events"),

    path('recordings', views.recordings, name="recordings"),

    path('dashboard', views.dashboard, name='dashboard'),
    path('dashboard/edt/<int:id>', views.edtRec, name='edtRec'),
    path('dashboard/del/<int:id>', views.delRec, name='delRec'),
    path('dashboard/addRec', views.addRec, name='addRec'),
]
