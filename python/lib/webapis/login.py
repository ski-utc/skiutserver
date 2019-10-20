from bottle import request, response
from bottle import post, get, route
from user.user import User
from connexion.connexion import ConnexionHandler, authenticate

import json

@get('/test')
@authenticate
def test(user=None):
    '''first route'''
    return json.dumps({"skiutc":"skiutc", "user": user.get_login()})

@post('/loginTremplin')
def login_tremplindb():
    """
    request tremplin table
    :return:
    """
    try:
        try:
            data = json.loads(request.body.read())
        except:
            raise ValueError
        if data is None or not data.get("mail_tremplin") or not data.get("password"):
            raise ValueError

        user = User.build_user_from_login(data["mail_tremplin"])
        user_log = User.login(data["mail_tremplin"], data["password"], "trmpln")

        if not user_log.get("Validated"):
            raise KeyError

    except ValueError:
        response.status = 400
        response.status = '400 Bad Request'
        return json.dumps({"error": "Bad Request"})
    except KeyError:
        response.status = 400
        response.status = '400 Wrong password/login'
        return json.dumps({"error": "Invalid Password or Login"})

    response.status = 200
    response.headers['Content-Type'] = 'application/json'

    user_auth = ConnexionHandler.build_handler(login=user_log.get("login"))
    user_auth_inst = user_auth.handle_connexion()

    info_user = user.get_user_info()

    if info_user is None and user_auth_inst["token"]:
        user_return = {
            "login": user_log.get("login"),
            "token": user_auth_inst["token"]
        }
        return user_return
    else:
        info_user["token"] = user_auth_inst["token"]
        return info_user


@post('/login')
def login_cas():
    """
    end point login cas
    :return:
    """
    try:
        try:
            data = json.loads(request.body.read())
        except:
            raise ValueError
        if data is None or not data.get("username") or not data.get("password"):
            raise ValueError

        user = User.build_user_from_login(data["username"])
        user_log = User.login(data["username"], data["password"])

        if not user_log.get("ticket"):
            raise KeyError

    except ValueError:
        response.status = 400
        response.status = '400 Bad Request'
        return json.dumps({"error": "Bad Request"})
    except KeyError:
        response.status = 400
        response.status = '400 Wrong password/login'
        return json.dumps({"error": "Invalid Password or Login"})

    response.status = 200
    response.headers['Content-Type'] = 'application/json'

    user_auth = ConnexionHandler.build_handler(login=user_log.get("login"))
    user_auth_inst = user_auth.handle_connexion()

    info_user = user.get_user_info()

    if info_user is None and user_auth_inst["token"]:
        user_return = {
            "login": user_log.get("login"),
            "token": user_auth_inst["token"]
        }
        return user_return
    else:
        info_user["token"] = user_auth_inst["token"]
        return info_user

@get('/logout')
@authenticate
def logout():
    auth = request.headers.get('Authorization')
    res = ConnexionHandler.disconnect_user(token=auth)
    return {
        "disconnected": res
    }
