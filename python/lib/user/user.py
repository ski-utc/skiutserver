import requests
from config.urls import _CAS_URL, _GINGER_URL, _SKIUTC_SERVICE, _DB_PASSWORD, _DB_HOST, _DB_USER, _DB_NAME
import pymysql

class User():
    """
    User methods
    """

    def __init__(self, login):
        self.login = login
        self.user_info = None

    def get_login(self):
        return self.login

    def get_user_info(self):
        con = pymysql.connect(host=_DB_HOST, db=_DB_NAME, user=_DB_USER, password=_DB_PASSWORD, charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
        with con:
            cur = con.cursor()
            sql = "SELECT * from users_2019 WHERE login=%s"
            cur.execute(sql, self.login)
            user_info = cur.fetchone()
            if user_info is None:
                return None
            self.user_info = user_info

        return self.user_info

    @staticmethod
    def build_user_from_login(username):
        return User(username)

    @staticmethod
    def login(username=None, password=None):
        #@TODO //Check BDD si user is tremplin, then login tremplin

        #else connexion CAS
        headerscas = {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'text/plain',
            'User-Agent': 'python'
        }
        paramscas = {
            'service': _SKIUTC_SERVICE,
            'username': username,
            'password': password
        }

        response = requests.post(_CAS_URL, headers=headerscas, params=paramscas)

        tgt = response.text
        response = requests.post(_CAS_URL + tgt, headers=headerscas, params=paramscas)
        st = response.text

        data = {"ticket": st, "login": username}

        return data
