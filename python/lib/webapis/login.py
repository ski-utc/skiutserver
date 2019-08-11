from bottle import request, response
from bottle import post, get, route
from user.user import User

import json

@get('/test')
def test():
    '''first route'''

    return json.dumps({"skiutc":"skiutc"})


@route('/login', method=['OPTIONS', 'POST'])
def login_cas():
    """
    end point login cas
    :return:
    """
    if request.method == 'OPTIONS':
        return {}
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

    info_user = user.get_user_info()

    if info_user is None:
        user_return = {
            "login": user_log.get("login")
        }
        return user_return
    else:
        return info_user