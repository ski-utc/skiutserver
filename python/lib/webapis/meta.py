from bottle import request, response
from bottle import post, get, route
from user.user import User
from connexion.connexion import ConnexionHandler
from shotgun.shotgun import Shotgun

@get("/meta")
def get_meta():
    """
    Returns all static data when laoading the app
    We don't use authenticate here because wwe don't need to be connected
    Therefore, we are checking if user is connected to get user info if there are
    :return: json meta for static data on app
    """

    meta = {
        "user": {}
    }

    auth = request.headers.get('Authorization')
    #user_auth_inst = ConnexionHandler.is_authenticated(token=auth)
    user_auth_inst = 2
    response.status = 200
    response.headers['Content-Type'] = 'application/json'

    Shotgun.add_to_shotgun("hpaignea")

    if user_auth_inst is None:
        return meta
    else:
        meta["user"]["login"] = "hpaignea" #user_auth_inst["login"]
        meta["user"]["auth"] = True
        return meta