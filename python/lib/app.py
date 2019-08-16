import threading

from helpers.routine import Routine

from bottle import run, get, hook, response, route
from webapis import login, meta, tombola
from tombola.tombola import Tombola

@hook('after_request')
#@bottle.route('/:#.*#', method='OPTIONS')  # Also tried old syntax.
def enableCORSGenericRoute():
    """
    You need to add some headers to each request.
    Don't use the wildcard '*' for Access-Control-Allow-Origin in production.
    """
    response.headers['Access-Control-Allow-Origin'] = '*'
    response.headers['Access-Control-Allow-Methods'] = 'PUT, GET, POST, DELETE, OPTIONS'
    response.headers['Access-Control-Allow-Headers'] = 'Origin, Accept, Content-Type, X-Requested-With, X-CSRF-Token, Authorization'

@route('/', method = 'OPTIONS')
@route('/<path:path>', method = 'OPTIONS')
def options_handler(path = None):
    return

# Routine every 30 minutes
tombola_routine = Routine(60*30, Tombola().check_transaction_routine)

if __name__ == '__main__':
    threading.Thread(target=run, kwargs=dict(host='localhost', port=8000, debug=True, reloader=True)).start()

    # Routine to update none-handled tombola transactions
    tombola_routine.start()
