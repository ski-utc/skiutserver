from bottle import request, response
from bottle import post, get, route
from user.user import User
from connexion.connexion import ConnexionHandler


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
    user_auth_inst = ConnexionHandler.is_authenticated(token=auth)

    response.status = 200
    response.headers['Content-Type'] = 'application/json'

    if user_auth_inst is None:
        return meta
    else:
        meta["user"]["login"] = user_auth_inst["login"]
        return meta