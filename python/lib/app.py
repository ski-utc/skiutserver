from bottle import run, get
from webapis import login


@get('/')
def home():
    '''first route'''
    return '<h1>Hello to SKIUTC Server</h1>'

if __name__ == '__main__':
    run(host='localhost', port=8000, debug=True, reloader=True)
