from bottle import run, get, hook, response, route
from webapis import login, meta, tombola, usermanager, shotgun, usermanager

@hook('after_request')
#@bottle.route('/:#.*#', method='OPTIONS')  # Also tried old syntax.
def enableCORSGenericRoute():
    """
    You need to add some headers to each request.
    Don't use the wildcard '*' for Access-Control-Allow-Origin in production.
    """
    response.headers['Access-Control-Allow-Origin'] = '*'
    response.headers['Access-Control-Allow-Methods'] = 'PUT, GET, POST, DELETE, PATCH, OPTIONS'
    response.headers['Access-Control-Allow-Headers'] = 'Origin, Accept, Content-Type, X-Requested-With, X-CSRF-Token, Authorization'

@route('/', method = 'OPTIONS')
@route('/<path:path>', method = 'OPTIONS')
def options_handler(path = None):
    return


if __name__ == '__main__':
    run(host='0.0.0.0', port=8000, debug=False, reloader=True)
