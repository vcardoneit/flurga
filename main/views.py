import requests
import datetime
from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib.auth import authenticate, login as dlogin
from django.contrib.auth import logout as dlogout
from django.contrib.auth.models import User
from django.utils.translation import gettext_lazy as _
from django.contrib.auth.hashers import check_password
from django.http import HttpResponse
from django.template import loader
from .models import config


def getCams():
    cfg = config.objects.values()
    if not cfg:
        return ("getCamsError")
    cameras = []

    try:
        for x in range(len(cfg)):
            frigateIP = cfg[x]['frigateIP']
            cameras.append({x: []})
            cam = config.objects.values('cams')
            cams = cam[x]['cams'].split(',')
            for y in cams:
                camUrl = "http://" + frigateIP + "/" + y + "/latest.jpg"
                r = requests.get(url=camUrl, timeout=2)
                if (r.status_code == 200):
                    cameras[x][x].append(y)

        return cameras
    except Exception:
        return ("getCamsError")


def login(request):
    if request.user.is_authenticated:
        return redirect("/" + request.LANGUAGE_CODE)
    else:
        if request.method == 'POST' and 'username' in request.POST and 'password' in request.POST:
            username = request.POST.get('username')
            password = request.POST.get('password')
            user = authenticate(request, username=username, password=password)

            if user is not None:
                dlogin(request, user)
                return redirect("/" + request.LANGUAGE_CODE)
            else:
                template = loader.get_template('main/login.html')
                context = {'error': _("Error, check your credentials!")}
                return HttpResponse(template.render(context, request))
        else:
            return render(request, "main/login.html")


@login_required
def index(request):
    if request.method == 'POST' and 'giorno' in request.POST and 'oraInizio' in request.POST and 'oraFine' in request.POST and 'camls[]' in request.POST:
        cfg = config.objects.values()
        data = request.POST.get('giorno')
        oraI = request.POST.get('oraInizio')
        oraF = request.POST.get('oraFine')
        camls = request.POST.getlist('camls[]')

        cams = getCams()
        if cams == "getCamsError":
            template = loader.get_template('main/dashboard.html')
            records = config.objects.all()
            context = {'error': _('Config error, please check the configuration below!'), 'records': records}
            return HttpResponse(template.render(context, request))

        dataInizio = data + " " + oraI
        dataFine = data + " " + oraF
        timestampI = datetime.datetime.strptime(dataInizio, '%Y-%m-%d %H:%M').timestamp()
        timestampF = datetime.datetime.strptime(dataFine, '%Y-%m-%d %H:%M').timestamp()

        videoLink, downLink, = ([] for i in range(2))

        check = True
        camsrch = []
        for fInstance in cams:
            for x in fInstance:
                for camera in fInstance[x]:
                    for cam in camls:
                        if cam in camera:
                            frigateIP = cfg[x]['frigateIP']
                            link = 'http://' + frigateIP + '/vod/' + camera + '/start/' + str(timestampI) + '/end/' + str(timestampF) + '/index.m3u8'
                            lCheck = requests.get(url=link)
                            if (lCheck.status_code == 200):
                                check = False
                                camsrch.append(camera)
                                downloadLink = 'http://' + frigateIP + '/api/' + camera + '/start/' + str(timestampI) + '/end/' + str(timestampF) + '/clip.mp4'
                                videoLink.append(link)
                                downLink.append(downloadLink)

        if check is False:
            template = loader.get_template('main/index.html')
            context = {
                'id': range(len(camsrch)),
                'cameras': cams,
                'videoLink': videoLink,
                'downLink': downLink,
            }
            return HttpResponse(template.render(context, request))
        else:
            return redirect("/" + request.LANGUAGE_CODE)
    else:
        cams = getCams()
        if cams == "getCamsError":
            template = loader.get_template('main/dashboard.html')
            records = config.objects.all()
            context = {'error': _('Config error, please check the configuration below!'), 'records': records}
            return HttpResponse(template.render(context, request))
        else:
            template = loader.get_template('main/index.html')
            context = {
                'cameras': cams,
            }
            return HttpResponse(template.render(context, request))


@login_required
def events(request):
    if getCams() == "getCamsError":
        template = loader.get_template('main/dashboard.html')
        records = config.objects.all()
        context = {'error': _('Config error, please check the configuration below!'), 'records': records}
        return HttpResponse(template.render(context, request))

    if request.method == 'POST' and 'del' in request.POST:
        link = request.POST.get('del')
        requests.delete(link)
        return redirect('events')
    elif request.method == 'POST' and 'dall' in request.POST:
        cfg = config.objects.values()

        for x in range(len(cfg)):
            frigateIP = cfg[x]['frigateIP']
            URL = "http://" + frigateIP + "/api/events?limit=-1"  # -1 infinite
            data = requests.get(url=URL).json()

            for y in data:
                delLink = 'http://' + frigateIP + '/api/events/' + y['id']
                requests.delete(delLink)

        return redirect('events')

    cfg = config.objects.values()
    title, snapshot, clip, delt, time = ([] for i in range(5))
    try:
        for x in range(len(cfg)):
            frigateIP = cfg[x]['frigateIP']
            URL = "http://" + frigateIP + "/api/events?limit=300"  # -1 infinite
            data = requests.get(url=URL, timeout=2).json()
            print(data)

            for x in data:
                title.append(x['camera'] + " (" + x['label'].capitalize() + " - " + f"{x['top_score']:.0%}" + ")")
                snapshot.append('http://' + frigateIP + '/api/events/' + x['id'] + '/snapshot.jpg')
                clip.append('http://' + frigateIP + '/api/events/' + x['id'] + '/clip.mp4')
                delt.append('http://' + frigateIP + '/api/events/' + x['id'])
                time.append(datetime.datetime.fromtimestamp(x['start_time']).strftime("%d/%m/%Y %H:%M:%S"))

        template = loader.get_template('main/events.html')
        context = {
            'id': range(len(title)),
            'title': title,
            'snapshot': snapshot,
            'clip': clip,
            'delt': delt,
            'time': time,
        }
        return HttpResponse(template.render(context, request))
    except Exception:
        template = loader.get_template('main/dashboard.html')
        records = config.objects.all()
        context = {'error': _('Config error, please check the configuration below!'), 'records': records}
        return HttpResponse(template.render(context, request))


@login_required
def changepw(request):
    if request.method == 'POST' and 'oldpassword' in request.POST and 'newpassword' in request.POST and 'confirmpw' in request.POST:
        currentpassword = request.user.password

        oldpassword = request.POST.get('oldpassword')
        newpassword = request.POST.get('newpassword')
        confirmpw = request.POST.get('confirmpw')

        if (check_password(oldpassword, currentpassword)):
            if (newpassword == confirmpw):
                u = User.objects.get(username__exact=request.user)
                u.set_password(newpassword)
                u.save()
                return redirect("login")
            else:
                template = loader.get_template('main/changepw.html')
                context = {'error': _('Error, check the passwords!')}
                return HttpResponse(template.render(context, request))
        else:
            template = loader.get_template('main/changepw.html')
            context = {'error': _('Error, check the passwords!')}
            return HttpResponse(template.render(context, request))
    else:
        return render(request, "main/changepw.html")


@login_required
def dashboard(request):
    records = config.objects.all()
    return render(request, 'main/dashboard.html', {'records': records})


@login_required
def edtRec(request, id):
    if request.method == 'POST':
        record = get_object_or_404(config, id=id)
        record.frigateIP = request.POST.get("frigateIP")
        record.cams = request.POST.get("cams")
        record.save()
        return redirect("dashboard")
    else:
        return redirect("dashboard")


@login_required
def delRec(request, id):
    if request.method == 'POST':
        record = get_object_or_404(config, id=id)
        record.delete()
        return redirect("dashboard")
    else:
        return redirect("dashboard")


@login_required
def addRec(request):
    if request.method == 'POST':
        record = config.objects.create()
        record.frigateIP = request.POST.get("frigateIP")
        record.cams = request.POST.get("cams")
        record.save()
        return redirect("dashboard")
    else:
        return redirect("dashboard")


@login_required
def logout(request):
    dlogout(request)
    return redirect("login")


@login_required
def recordings(request):
    cfg = config.objects.values()

    cams = getCams()
    if cams == "getCamsError":
        template = loader.get_template('main/dashboard.html')
        records = config.objects.all()
        context = {'error': _('Config error, please check the configuration below!'), 'records': records}
        return HttpResponse(template.render(context, request))

    recordings = []

    if request.method == 'GET' and 'ti' in request.GET and 'tf' in request.GET and 'cam' in request.GET and 'fi' in request.GET:
        ti = request.GET.get('ti')
        tf = request.GET.get('tf')
        cam = request.GET.get('cam')
        frigateIP = cfg[int(request.GET.get('fi'))]['frigateIP']

        sLink = 'http://' + frigateIP + '/vod/' + cam + '/start/' + ti + '/end/' + tf + '/index.m3u8'
        dLink = 'http://' + frigateIP + '/api/' + cam + '/start/' + ti + '/end/' + tf + '/clip.mp4'

    fInstance = 0
    camId = 0
    for x in range(len(cfg)):
        frigateIP = cfg[x]['frigateIP']
        for cam in cams[fInstance][fInstance]:
            url = "http://" + frigateIP + "/api/" + cam + "/recordings/summary"
            json = requests.get(url=url).json()
            recordings.append({cam: {"data": []}})
            hourId = 0
            for b in json:
                fData = datetime.datetime.strptime(b['day'], '%Y-%m-%d')
                print(camId)
                print(cam)
                recordings[camId][cam]['data'].append({fData: []})
                sHour = sorted(b['hours'], key=lambda x: x['hour'])
                for k in sHour:
                    start = k['hour'] + ":00"
                    if int(k['hour']) == 23:
                        end = "00:00"
                        endTimestamp = (datetime.datetime.strptime(b['day'] + " " + end, '%Y-%m-%d %H:%M') + datetime.timedelta(days=1)).timestamp()
                    else:
                        end = str(int(k['hour']) + 1) + ":00"
                        endTimestamp = datetime.datetime.strptime(b['day'] + " " + end, '%Y-%m-%d %H:%M').timestamp()
                    ora = start + " - " + end
                    startTimestamp = datetime.datetime.strptime(b['day'] + " " + start, '%Y-%m-%d %H:%M').timestamp()
                    link = '?ti=' + str(startTimestamp) + '&tf=' + str(endTimestamp) + '&cam=' + cam + '&fi=' + str(fInstance)
                    recordings[camId][cam]['data'][hourId][fData].append({ora: link})
                hourId += 1
            camId += 1
        fInstance += 1

    try:
        context = {'recordings': recordings, 'sLink': sLink, 'dLink': dLink}
    except Exception:
        context = {'recordings': recordings}
    return render(request, 'main/recordings.html', context)
