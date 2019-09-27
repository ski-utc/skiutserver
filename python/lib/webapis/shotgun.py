from bottle import post, route, get
from connexion.connexion import authenticate
from shotgun.shotgun import Shotgun

@post('/shotgunme')
@authenticate
def test(user=None):
    if Shotgun.check_time():
        data = json.loads(request.body.read())
        new_shotgun = Shotgun(data.get("login"))
        if new_shotgun.shotgun_valid():
            return {
                'status': 'SUCCESS'
            }
        else:
            return {
                'status': 'FAILED'
            }
    else:
        return {
                'status': 'FAILED'
            }