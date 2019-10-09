from bottle import request, response
from bottle import post, get, route
from connexion.connexion import ConnexionHandler, authenticate
import json

@get('/addPayedLogin')
@authenticate
def add_pay_login(user=None):
    return {}