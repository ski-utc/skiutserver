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

@post('/login_v2')
def login_v2():
    try:
        data = json.loads(request.body.read())
        if data is None or not data.get('service') or not data.get('ticket'):
            raise ValueError

        username = User.validate_auth_ticket(data['service'], data["ticket"])

        if username is None:
            raise ValueError

        user = User.build_user_from_login(username)

        user_auth = ConnexionHandler.build_handler(login=username)

        user_auth_inst = user_auth.handle_connexion()

        info_user = user.get_user_info()

        if info_user is None and user_auth_inst["token"]:
            user_return = {
                "login": user_auth_inst['login'],
                "token": user_auth_inst["token"]
            }
            return user_return
        else:
            info_user["token"] = user_auth_inst["token"]
            return info_user

    except ValueError:
        response.status = 400
        return json.dumps({"error": "Wrong authentication"})

    except Exception as e:
        response.status = 400
        return  json.dumps({"error": e})

@get('/logout')
@authenticate
def logout():
    auth = request.headers.get('Authorization')
    res = ConnexionHandler.disconnect_user(token=auth)
    return {
        "disconnected": res
    }
