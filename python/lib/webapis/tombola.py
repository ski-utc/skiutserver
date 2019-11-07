from bottle import request, response
from bottle import post, route, patch, get, delete
from config.urls import _SKIUTC_SERVICE

from connexion.connexion import authenticate
from tombola.tombola import Tombola
from tombola.tombola_lots import TombolaLots

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

@post('/tombola_batch')
def add_batch():
    '''route to create a batch'''
    try:
        data = json.loads(request.body.read())
        name = data.get('name', None)
        qte = data.get('qte', None)
        response =  TombolaLots().add_batch(name, qte)

        return response
    except Exception as e:
        return e

@get('/tombola_batch')
def get_batches():
    '''route to get all batches'''
    try:
        response =  TombolaLots().get_batches()

        return response
    except Exception as e:
        return e

@delete('/tombola_batch')
def delete_batch():
    '''route to delete a batch and return the new list with new orders'''
    try:
        data = json.loads(request.body.read())
        id = data.get('id')

        if id:
            response = TombolaLots().delete_batch(id)
            return response
        return json.dumps('error: No id')
    except Exception as e:
        return e

@patch('/tombola_batch')
def update_batch():
    '''route to update a batch and return the new list'''
    try:
        data = json.loads(request.body.read())
        id = data.get('id')
        name = data.get('name')
        indice = data.get('indice')

        if id and name:
            response = TombolaLots().update_batch(id, name)
            return response

        if id and indice:
            response = TombolaLots().change_indice(id, indice)
            return response

        return json.dumps('Bad request')

    except Exception as e:
        return e

@get('/play_tombola')
@authenticate
def play_tombola(user = None):
    try:
        return TombolaLots().get_win(user)
    except Exception as e:
        return e
