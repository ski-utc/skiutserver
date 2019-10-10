from bottle import request, response
from bottle import post, get, route
from connexion.connexion import ConnexionHandler, authenticate
from user import infoManager
import json

@get('/addPayedLogin')
@authenticate
def add_pay_login(user=None):
    try:
        if user.is_admin():
            try:
                data = json.loads(request.body.read())
                if data is None:
                    raise ValueError
            finally:
                return infoManager.set_has_payed(data.get("login"))
        else:
            return json.dumps({"error": "you are not an skiutc admin"})
    except ValueError:
        response.status = 400
        response.status = '400 Bad Request'
        return json.dumps({"error": "Bad Request"})

@get('/getRecapUsers')
@authenticate
def get_recap_users(user=None):
    try:
        if user.is_admin():
            return infoManager.get_has_payed()
        else:
            return json.dumps({"error": "you are not an skiutc admin"})
    except ValueError:
        response.status = 400
        response.status = '400 Bad Request'
        return json.dumps({"error": "Bad Request"})
