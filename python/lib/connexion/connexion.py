from db import dbskiut_con
from bottle import response, request
from config.urls import _SKIUTC_SERVICE, _CAS_URL
import secrets
import datetime
from functools import wraps
import json
import inspect
from user.user import User

def authenticate(f):
    """
    :param f: every api rest functions
    :return: function if user is auth
    """
    @wraps(f)
    def wrapper(*args, **kwargs):
        auth = request.headers.get('Authorization')
        if auth is None:
            response.status = 401
            response.message = '401 You are not logged in !'
            return json.dumps({"error": "NOT LOGGED NO TOKEN"})

        user_auth_inst = ConnexionHandler.is_authenticated(token=auth)
        if user_auth_inst is None:
            response.status = 401
            response.message = '401 You are not logged in !'
            return json.dumps({"error": "NOT LOGGED"})
        if "user" in inspect.getfullargspec(f).args:
            user = User.build_user_from_login(user_auth_inst["login"])
            return f(user=user, *args, **kwargs)
        else:
            return f(*args, **kwargs)
    return wrapper

class ConnexionHandler:
    """
    Handler des connexions intra skiutc pour une utilisateur
    """
    def __init__(self, login, token):
        if token is None:
            self.login = login
            self.update = False
        else:
            self.passed_token = token
            self.update = True

    @staticmethod
    def build_handler(login=None,token=None):
        """
        :param login: login de l'utilisateur
        :param token: token si l'utilisateur possede un localstorage ( deja connecte )
        :return: ConnexionHandler object
        """
        return ConnexionHandler(login, token)

    @staticmethod
    def is_authenticated(login=None,token=None):
        """
        :return: User instance if authenticate, None else
        """
        handler = ConnexionHandler.build_handler(login,token)
        return handler.update_connexion()

    def generate_token(self):
        return secrets.token_hex(20)

    def handle_connexion(self):
        """
        Handler global de l'authentication
        :return: login if autheticated, None else
        """
        if self.login is None:
            return self.update_connexion()
        else:
            return self.create_connexion()

    def create_connexion(self):
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "SELECT * FROM auth WHERE login=%s"
            cur.execute(sql, self.login)
            user_in_db = cur.fetchone()
            if user_in_db is None:
                token = self.generate_token()
                date = datetime.datetime.now() + datetime.timedelta(minutes=15)
                validity = date.strftime('%Y-%m-%d %H:%M:%S')
                sql_tuples = (self.login, token, validity)
                try:
                    sql = "INSERT INTO auth VALUES (%s,%s,%s)"
                    cur.execute(sql, sql_tuples)
                    con.commit()
                except MySQLdb.IntegrityError:
                    logging.warn("failed to create connexion")
                    return None
                finally:
                    cur.close()
                return {
                    "login": sql_tuples[0],
                    "token": sql_tuples[1],
                    "validity": sql_tuples[2]
                }
            else:
                date = datetime.datetime.now() + datetime.timedelta(minutes=15)
                validity = date.strftime('%Y-%m-%d %H:%M:%S')
                token = self.generate_token()
                try:
                    sql = "UPDATE auth SET token=%s, validity=%s WHERE login=%s"
                    sql_tuples=(token, validity, self.login)
                    cur.execute(sql, sql_tuples)
                    con.commit()
                except MySQLdb.IntegrityError:
                    logging.warn("failed to create connexion")
                    return None
                finally:
                    cur.close()
                return {
                    "login": sql_tuples[2],
                    "token": sql_tuples[0],
                    "validity": sql_tuples[1]
                }

    def update_connexion(self):
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "SELECT * FROM auth WHERE token=%s"
            cur.execute(sql, self.passed_token)
            user_in_db = cur.fetchone()
            if user_in_db is None:
                return None
            else:
                curr_validity = user_in_db["validity"]
                date = datetime.datetime.now() + datetime.timedelta(minutes=15)
                if curr_validity < datetime.datetime.now():
                    return None
                elif curr_validity < date:
                    new_validity = curr_validity + datetime.timedelta(minutes=5)
                else:
                    new_validity = curr_validity
                validity = new_validity.strftime('%Y-%m-%d %H:%M:%S')
                try:
                    sql = "UPDATE auth SET validity=%s WHERE token=%s"
                    sql_tuples = (validity, self.passed_token)
                    cur.execute(sql, sql_tuples)
                    con.commit()
                except MySQLdb.IntegrityError:
                    logging.warn("failed to update connexion")
                    return False
                finally:
                    cur.close()
                return {
                    "login": user_in_db["login"],
                    "token": sql_tuples[1],
                    "validity": sql_tuples[0]
                }
    @staticmethod
    def disconnect_user(token=None):
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            date = datetime.datetime.now() - datetime.timedelta(minutes=1)
            validity = date.strftime('%Y-%m-%d %H:%M:%S')
            try:
                sql = "UPDATE auth SET validity=%s WHERE token=%s"
                sql_tuples = (validity, token)
                cur.execute(sql, sql_tuples)
                con.commit()
            except MySQLdb.IntegrityError:
                logging.warn("failed to update connexion")
                return False
            finally:
                cur.close()
            return True
