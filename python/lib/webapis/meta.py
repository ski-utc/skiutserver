from bottle import request, response
from bottle import post, get, route
from user.user import User
from connexion.connexion import ConnexionHandler
from shotgun.shotgun import Shotgun
from datetime import datetime
from globals import file_to_json

@get("/meta")
def get_meta():
    """
    Returns all static data when loading the app
    We don't use authenticate here because wwe don't need to be connected
    Therefore, we are checking if user is connected to get user info if there are
    :return: json meta for static data on app
    """

    meta = {
        "user": {},
        "shotgun_authorized": Shotgun.check_time(),
        "current_date": datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
        "prices": {}
    }
    auth = request.headers.get('Authorization')
    user_auth_inst = ConnexionHandler.is_authenticated(token=auth)

    response.status = 200
    response.headers['Content-Type'] = 'application/json'


    if user_auth_inst is None:
        return meta
    else:
        user = User.build_user_from_login(user_auth_inst["login"])
        info_user = user.get_user_info()
        if info_user.get("login"):
            meta["prices"] = file_to_json('meta/prices.json')
        meta["user"]["info"] = info_user
        meta["user"]["login"] = user_auth_inst["login"]
        meta["user"]["admin"] = bool(user_auth_inst["admin"])
        meta["user"]["auth"] = True
        return meta