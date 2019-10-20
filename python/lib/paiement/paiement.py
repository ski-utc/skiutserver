import requests
from db import dbskiut_con
from config.urls import _CAS_URL, _CALLBACK_URL
import json
from paiement.constants import CONSTANTS as cte

from weezevent.weezevent_api import WeezeventAPI
from requests.utils import quote

class Paiement():
    """
    Payment methods
    """

    def pay_pack(self, user, service = None):
        """
        Do the tansaction on weezevent side
        in order to validate the buy
        """

        api = WeezeventAPI()
        user_infos = user.get_user_info()

        if user_infos['tra_status'] == 'V':
            return (-1)

        transaction_tickets = [
            [cte['pack_skiutc'], 1],
        ]

        if user_infos['food'] is not 0: transaction_tickets.append([cte['food'], 1])
        if user_infos['assurance_annulation'] == 1: transaction_tickets.append([cte['assurance_annulation'], 1])
        if user_infos['pack'] is not 4:
            #get the pack value then the item value to find the good one
            transaction_tickets.append([cte[str(user_infos['pack'])][str(user_infos['items'])], 1])
        if user_infos['goodies'] == 1:
            transaction_tickets.append([cte['goodies'], 1])


        service_url = f"{_CALLBACK_URL}/validatePaiement?login={user_infos['login']}&service={quote(service)}"
        response = api.create_transaction(transaction_tickets, user.get_email(), service_url)

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                sql = "UPDATE `users_2020` SET `tra_id`=%s WHERE `login`=%s"
                cur.execute(sql, (response['tra_id'], user_infos['login'] ))
                con.commit()
            except Exception as e:
                raise ValueError
            finally:
                cur.close()

        return response

    def update_transaction_status(self, login):
        con = dbskiut_con()
        response = None
        con.begin()
        try:
            cur = con.cursor()
            sql = "SELECT `tra_id` FROM `users_2020` WHERE `login`=%s"
            cur.execute(sql, login)
            con.commit()
            tran = cur.fetchone()
        except Exception as e:
            print(e)
            raise ValueError
        finally:
            cur.close()

        if  tran.get('tra_id'):
            api = WeezeventAPI()
            response = api.get_transaction_info(tran['tra_id'])

            if response['status'] != 'W':
                con = dbskiut_con()
                con.begin()
                try:
                    cur = con.cursor()
                    sql = "UPDATE `users_2020` SET `tra_status`=%s WHERE `login`=%s and `tra_id`=%s"
                    cur.execute(sql, (response['status'], login, tran['tra_id']))
                    con.commit()
                except Exception as e:
                    raise ValueError
                finally:
                    cur.close()
