import requests
from db import dbskiut_con
from config.urls import _SKIUTC_SERVICE, _CAS_URL


class Tombola():
    """
    Tombola methods
    """
    def __init__(self):
        self.ticket = None

    def add_transaction(self):
        con = dbskiut_con
        with con:
            cur = con.cursor()
            sql = "INSERT INTO `tombola_2019` (`login`, `trans`, `pack10`, `pack1`, `date`) VALUES (%s, %s, %d, %d)"
            cur.execute(sql)



