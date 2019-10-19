from bottle import request, response
from bottle import post, route, patch, get
from config.urls import _SKIUTC_SERVICE

from connexion.connexion import authenticate
from tombola.tombola import Tombola

import json


@post('/tombola')
@authenticate
def tombola(user=None):
    '''route to buy tombola ticket'''
    try:
        try:
            data = json.loads(request.body.read())
        except:
             raise ValueError

        ticket1 = data.get('ticket1', 0)
        ticket5 = data.get('ticket5', 0)
        ticket10 = data.get('ticket10', 0)
        service = data.get('service', _SKIUTC_SERVICE)

    except ValueError:
        response.status = 400
        return json.dumps({"error": "Invalid datas"})

    response.status = 200
    response.headers['Content-Type'] = 'application/json'


    api_response = Tombola().buy_tombola(user, ticket1, ticket5, ticket10, service)

    return api_response

@patch('/tombola')
@authenticate
def tombola(user=None):
    '''route to validate the transaction'''
    try:
        res = Tombola().validate_tombola(user)
        return res
    except Exception as e:
        return e

@get('/tombola')
@authenticate
def tombola(user=None):
    '''route to get user stats'''
    try:
        return Tombola().get_user_stats(user)
    except Exception as e:
        return e
