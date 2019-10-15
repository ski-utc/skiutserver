from bottle import request, response
from bottle import post, route, patch, get

from connexion.connexion import authenticate
from paiement.paiement import Paiement

import json

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
