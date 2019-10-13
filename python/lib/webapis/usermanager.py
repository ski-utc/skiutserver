from bottle import request, response
from bottle import post, get, route
from user.infoManager import infoManager
from connexion.connexion import ConnexionHandler, authenticate
import json

@post('/changeInfo')
@authenticate
def change_info(user=None):
    try:
        try:
            data = json.loads(request.body.read())
        except:
            raise ValueError
        if data is None:
            raise ValueError
        user_manager = infoManager.construct_from_login(user.get_login())

    except ValueError:
        response.status = 400
        response.status = '400 Bad Request'
        return json.dumps({"error": "Bad Request"})

    response.status = 200
    response.headers['Content-Type'] = 'application/json'
    change_status = user_manager.change_info(**data)

    return {"success": change_status}

@get('/getRecap')
@authenticate
def get_price_and_recap(user=None):
    try:
        user_manager = infoManager.construct_from_login(user.get_login())
    except ValueError:
        response.status = 400
        response.status = '400 Bad Request'
        return json.dumps({"error": "Bad Request"})

    return user_manager.get_price_with_recap()
