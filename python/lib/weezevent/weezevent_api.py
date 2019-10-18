"""
Method to do a request to weezevent API
"""
import requests
import json

from requests.exceptions import HTTPError

from config.urls import _WEEZ_URL, _WEEZ_KEY, _WEEZ_FUN_ID


class WeezeventAPI():
    """
    API calls for weezevent
    """
    def __init__(self):
        self.session = self._connect()

    # Basic request for weezevent api
    def _request(self, method, service, route, data):
        method = method.lower()
        service = service.upper()

        headers = {
            'Content-type': 'application/json'
        }

        request = None
        if method == 'post':
            request = requests.post

        if method == 'get':
            request = requests.get
        try:
            response = request(f"{_WEEZ_URL}/{service}/{route}?system_id=payutc", headers=headers,  data=data)
        except Exception as e:
            raise e

        return response

    #Connect the app with its key
    def _connect(self):
        try:
            response = self._request('post', 'WEBSALE', 'loginApp', json.dumps({ 'key': _WEEZ_KEY }))

            response.raise_for_status()

        except HTTPError as http_err:
            print(f'HTTP error occurred: {http_err}')  # Python 3.6
        except Exception as err:
            print(f'Other error occurred: {err}')  # Python 3.6
        else:
            print('connected')
            return response.json()


    #Create a Weez Transaction with items passed in props
    def create_transaction(self, items, email, return_url):
        params = {
            'app_key': _WEEZ_KEY,
            'items': json.dumps(items),
            'mail': email,
            'fun_id': _WEEZ_FUN_ID,
            'return_url': return_url,
        }
                
        response = self._request('post', 'WEBSALE', 'createTransaction', json.dumps(params))
        print(response.json())
        return response.json()

    #Get transaction information
    def get_transaction_info(self, transaction):
        params = {
            'app_key': _WEEZ_KEY,
            'tra_id': transaction,
            'fun_id': _WEEZ_FUN_ID,
        }

        response = self._request('post', 'WEBSALE', 'getTransactionInfo', json.dumps(params))

        return response.json()
