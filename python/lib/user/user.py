import requests
from db import dbskiut_con
from config.urls import _SKIUTC_SERVICE, _CAS_URL, _GINGER_URL, _GINGER_KEY
from requests.exceptions import HTTPError
import xmltodict

class User():
    """
    User methods
    With the User object, you can retrieve any utc user with a login or an email
    """

    def __init__(self, login=None, email=None):
        self.email = email
        self.login = login
        self.user_info = ""
        self.name = ""
        self.surname = ""
        self.cotisant = ""
        self.adult = None
        self.valid = True
        if login is not None:
            self.get_user_info_ginger()


    def is_valid(self):
        """
        :return: True if user build is etu
        """
        return self.valid

    def get_name(self):
        return self.name

    def get_surname(self):
        return self.surname

    def get_login(self):
        return self.login

    def get_email(self):
        if self.email is None:
            return {'error': 'No email was build'}
        return self.email

    def is_adult(self):
        return self.adult

    def is_cotisant(self):
        """
        :return: True if user is cotisant
        """
        return self.cotisant

    def is_admin(self):
        con = dbskiut_con()
        with con:
            cur = con.cursor()
            sql = "SELECT * from auth WHERE login=%s"
            cur.execute(sql, self.login)
            user_info = cur.fetchone()
            if user_info is None:
                return False
        if user_info["admin"] == 1:
            return True
        else:
            return False

    def get_user_info(self):
        """
        :return: user info that have shotgun skiutc
        """
        con = dbskiut_con()
        with con:
            cur = con.cursor()
            sql = "SELECT * from users_2020 WHERE login=%s"
            cur.execute(sql, self.login)
            user_info = cur.fetchone()
            if user_info is None:
                return None
            self.user_info = user_info

        return self.user_info

    def get_user_info_ginger(self):
        """
        :return: object with info of CAS users
        """
        headersginger = {
            'Content-Type': 'application/json',
            'User-Agent': 'python'
        }
        paramsginger = {
            'key': _GINGER_KEY
        }
        try:
            res = requests.get(_GINGER_URL+self.login, headers=headersginger, params=paramsginger)
            res.raise_for_status()
        except HTTPError as http_err:
            print(f'HTTP error occurred: {http_err}')
        except Exception as err:
            print(f'Other error occurred: {err}')
        user = res.json()

        if user.get("error"):
            self.valid = False
            return None

        self.email = user["mail"]
        self.name = user["nom"]
        self.surname = user["prenom"]
        self.cotisant = user["is_cotisant"]
        self.adult = user["is_adulte"]

        return user


    @staticmethod
    def build_user_from_login(username):
        """
        Create a new User object
        :param username: login of user
        :return: User object
        """
        return User(login=username)

    @staticmethod
    def build_user_from_email(email):
        """
        Create a new User object
        :param email: email of user
        :return: User object
        """
        return User(email=email)

    @staticmethod
    def login(username=None, password=None):
        """
        :param username: login
        :param password:
        :return: user information or login if not in skiutcs db
        """
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

    @staticmethod
    def validate_auth_ticket(service, ticket):
        """
        validation of the ticket from the cas in order to authenticate the user
        """

        headerscas = {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'text/plain',
            'User-Agent': 'python'
        }

        paramscas = {
            'service': service,
            'ticket': ticket
        }

        response = requests.get("https://cas.utc.fr/cas/serviceValidate", headers=headerscas, params=paramscas)
        response = xmltodict.parse(response.text)

        try:
            username = response['cas:serviceResponse']['cas:authenticationSuccess']['cas:user']
            return username

        except Exception as e:
            return None
