import requests
from db import dbskiut_con
from config.urls import _CAS_URL
import json

from weezevent.weezevent_api import WeezeventAPI

class Tombola():
    """
    Tombola methods
    """
    def buy_tombola(self, user, ticket1, ticket5, ticket10, service):
        """
        Do the transaction on weezevent side
        in order to validate the buy
        """
        api = WeezeventAPI()

        tickets = []

        if int(ticket1) > 0 : tickets.append(['15122', ticket1])
        if int(ticket5) > 0 : tickets.append(['15121', ticket5])
        if int(ticket10) > 0 : tickets.append(['15123', ticket10])

        response = api.create_transaction(tickets, user.get_email(), service)

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                sql = "INSERT INTO `tombola_2020` (`id_transaction`, `login_user`, `status`, `ticket1`, `ticket5`, `ticket10`) VALUES (%s, %s, %s, %s, %s, %s)"
                cur.execute(sql, (response['tra_id'], user.get_login(), 'W', int(ticket1), int(ticket5), int(ticket10)))
                con.commit()
            except Exception as e:
                raise e
            finally:
                cur.close()

        return response

    #Call the transaction from weezevent and update its status if it has changed
    def update_transaction_status(self, transaction):
        api = WeezeventAPI()

        response = api.get_transaction_info(transaction)

        if response['status'] != 'W':
            con = dbskiut_con()
            con.begin()
            try:
                cur = con.cursor()
                sql = "UPDATE `tombola_2020` SET `status`=%s WHERE `id_transaction`=%s"
                cur.execute(sql, (response['status'], transaction))
                con.commit()
            except Exception as e:
                raise e
            finally:
                cur.close()

    #Get user stats for tombola
    def get_user_stats(self, user):
        response = {}

        con = dbskiut_con()
        con.begin()
        try:
            cur = con.cursor()
            sql = "SELECT SUM(`ticket1`) AS `ticket1`, SUM(`ticket5`) AS `ticket5`, SUM(`ticket10`) AS `ticket10` FROM `tombola_2020` WHERE `login_user`=%s AND `status`=%s"
            cur.execute(sql, (user.get_login(), 'V'))
            con.commit()
            response = cur.fetchone()
            for k, v in response.items():
                if v is None:
                    response[k] = 0

            response = {
                'ticket1': int(response['ticket1']),
                'ticket5': int(response['ticket5']),
                'ticket10': int(response['ticket10']),
                'total_tickets': int(self.get_total_tickets())
            }
        except Exception as e:
            raise e
        finally:
            cur.close()
        return json.loads(json.dumps(response))

    def validate_tombola(self, user):
        con = dbskiut_con()
        response = None
        con.begin()
        try:
             cur = con.cursor()
             sql = "SELECT `id_transaction` FROM `tombola_2020` WHERE `login_user`=%s ORDER BY `id` DESC"
             cur.execute(sql, user.get_login())
             con.commit()
             transaction = cur.fetchone()
        except Exception as e:
            raise e
        finally:
            cur.close()

        if transaction.get('id_transaction'):
            #Update the transaction status in the bdd
            self.update_transaction_status(transaction['id_transaction'])

        return self.get_user_stats(user)

    # Update each transaction status which have not been handled
    def check_transaction_routine(self):
        con = dbskiut_con()
        con.begin()
        try:
            cur = con.cursor()
            sql = "SELECT `id_transaction` FROM `tombola_2020` WHERE `status`=%s"
            cur.execute(sql, 'W')
            con.commit()
            transactions = cur.fetchall()
        except Exception as e:
            raise e
        finally:
            cur.close()

        for transaction in transactions:
            if transaction.get('id_transaction'):
                self.update_transaction_status(transaction['id_transaction'])

    def get_poids(self, user):
        con = dbskiut_con()
        con.begin()
        try:
            sql = "SELECT SUM(`ticket1` + 5*`ticket5` + 10*`ticket10`) as value FROM tombola_2020 WHERE `login_user`=%s AND `status`=%s"
            cur.execute(sql, (user.get_login(), 'V'))
            con.commit()
            user_tickets = cur.fetchone()
        except Exception as e:
            raise e
        finally:
            cur.close()

        if user_tickets is None:
            user_tickets = 0

        return int(user_tickets['value'])/self.get_total_tickets()

    def get_total_tickets(self):
        con = dbskiut_con()
        con.begin()
        try:
            cur = con.cursor()
            sql = "SELECT SUM(`ticket1` + 5*`ticket5` + 10*`ticket10`) as value FROM `tombola_2020` WHERE `status`=%s"
            cur.execute(sql, 'V')
            con.commit()
            total_tickets = cur.fetchone()
        except Exception as e:
            raise e
        finally:
            cur.close()
        if total_tickets is None or total_tickets['value'] is None:
            total_tickets = { 'value': 0 }

        return int(total_tickets['value'])
