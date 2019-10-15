import requests
from db import dbskiut_con
from config.urls import _CAS_URL
import json
from paiement.constants import CONSTANTS as cte

from weezevent.weezevent_api import WeezeventAPI

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

        response = api.create_transaction(transaction_tickets, user.get_email(), service)

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                sql = "UPDATE `users_2020` SET `tra_id`=%s WHERE `login`=%s"
                cur.execute(sql, (response['tra_id'], user_infos['login'] ))
                con.commit()
            except Exception as e:
                raise e
            finally:
                cur.close()

        return response

    def update_transaction_status(self, user, tra_id):
        api = WeezeventAPI()
        user_infos = user.get_user_info()

        response = api.get_transaction_info(tra_id)

        if response['status'] != 'W':
            con = dbskiut_con()
            con.begin()
            try:
                cur = con.begin()
                sql = "UPDATE `users_2020` SET `tra_status`=%s WHERE `login`=%s and tra_id=%s"
                cur.execute(sql, (response['status'], user_infos['login'], tra_id))
                con.commit()
            except Exception as e:
                raise e
            finally:
                cur.close()

#     CREATE TABLE `users_2020` (
#   `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
#   `food` int(11) NOT NULL DEFAULT '0',
#   `equipment` int(11) NOT NULL DEFAULT '0',
#   `pack` int(11) NOT NULL DEFAULT '0',
#   `items` int(11) NOT NULL DEFAULT '2',
#   `tra_id` int(11) DEFAULT NULL,
#   `tra_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
#   `assurance_annulation` tinyint(1) NOT NULL DEFAULT '0',
#   `goodies` tinyint(1) NOT NULL DEFAULT '0' ,
#   PRIMARY KEY (`id_user`)
# ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
