import requests
from db import dbskiut_con
from config.urls import _SKIUTC_SERVICE, _CAS_URL


class Tombola():
    """
    Tombola methods
    """
    def __init__(self, user):
        self.user = user

    def insert_transaction(self, transaction):
        """
        Add the transaction to the database
        with user_id and weez_trans number
        """

        con = dbskiut_con
        login = user.get_login()

        with con:
            cur = con.cursor()
            sql = "INSERT INTO `tombola_2020` (`user`, `weez_trans`) VALUES (%s, %s)"
            cur.execute(sql, login, transaction)

    def validate_transaction(self, ticket, ticket5, ticket10):
        """
        Do the transaction on weezevent side
        in order to validate the buy
        """
    def get_transactions(self):
        """
        Return all transactions done by the user
        and what ticket he bought
        """

    
