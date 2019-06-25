import requests
from config.urls import _CAS_URL, _GINGER_URL, _SKIUTC_SERVICE

class User():
    """
    User methods
    """

    def __init__(self, login):
        self.login = login

    @staticmethod
    def login(username=None, password=None):
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
