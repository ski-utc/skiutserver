from bottle import request, response
from bottle import post, get, route
from user.user import User

import json

@get('/test')
def test():
    '''first route'''

    return json.dumps({"skiutc":"skiutc"})


@route('/login', method=['OPTIONS', 'POST'])
def loginCas():
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

        user = User.login(data["username"], data["password"])

        if not user.get("ticket"):
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

    return user


@get('/infoUser')
def infoUser():

    return None