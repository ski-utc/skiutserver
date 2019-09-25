from bottle import post, route, get
from connexion.connexion import authenticate
from shotgun.shotgun import Shotgun

@get('/shotgunme')
@authenticate
def test(user=None):
    new_shotgun = Shotgun(user.get_login())
    if new_shotgun.shotgun_valid():
        return {
            'status': 'SUCCESS'
        }
    else:
        return {
            'status': 'FAILED'
        }