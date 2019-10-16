from bottle import request, response, redirect
from bottle import post, route, get
from config.urls import _SKIUTC_SERVICE

from connexion.connexion import authenticate
from paiement.paiement import Paiement

import json

from user.user import User
from webapis.meta import get_meta

@post('/paiement')
@authenticate
def paiement(user=None):
    """
    route to pay the pack
    """
    try:
        try:
            data = json.loads(request.body.read())
            service = data.get('service', None)

        except:
            raise ValueError

        api_response = Paiement().pay_pack(user, service)

        if api_response == -1:
            reponse.status = 200
            return json.dumps({ "message": "User has allready paid."})

        return api_response

    except ValueError:
        response.status = 400
        return json.dumps({ "error" : "Invalid datas."})

    except Exception as e:
        response.status = 500
        return json.dumps({ "error": e })

@post('/validatePaiement')
def paiement():
    '''route to validate the transaction'''
    try:
        query_string = request.query.decode()

        login = query_string.get('login', None)

        if login is None:
            raise ValueError

        try:
            Paiement().update_transaction_status(login)
        except Exception as e:
            raise ValueError

    except Exception as e:
        raise e
