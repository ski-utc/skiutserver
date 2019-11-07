import requests
import json

from db import dbskiut_con
from tombola.tombola import Tombola
from random import randrange


class TombolaLots():
    """
    methods to manage win batches
    - add batch
    - get winners list
    - get_win
    - change order of batch
    - delete batch (if not won)
    - get total number of batches (with loosing batches)
    """

    def add_batch(self, name, qte = None):
        """
        add qte times the new batch
        (for example: 3 PS4)
        """

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                get_order_sql = "SELECT `indice` from `tombola_2020_lots` ORDER BY `indice` DESC;"
                cur.execute(get_order_sql)
                con.commit()
                response = cur.fetchone()

                indice = 0

                if response is not None and response['indice'] is not None:
                    indice = response['indice'] + 1

                insert_sql = "INSERT INTO `tombola_2020_lots` (`name`, `indice`) VALUES (%s, %s);"

                qte = 1 if qte is None else int(qte)
                for i in range(qte):
                    cur.execute(insert_sql, (name, indice + i))
                con.commit()

                get_all_batches = "SELECT * FROM `tombola_2020_lots` ORDER BY `indice` ASC;"
                cur.execute(get_all_batches)
                con.commit()
                response = cur.fetchall()

                return json.dumps(response)

            except Exception as e:
                if con:
                    con.rollback()
                raise e
            finally:
                cur.close()

    def delete_batch(self, id):
        """
        delete a batch with its id
        and return the updated list with updated orders
        """

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                get_order_sql = "SELECT `indice`, `winner` from `tombola_2020_lots` WHERE `id`= %s "
                cur.execute(get_order_sql, id)
                con.commit()
                response = cur.fetchone()

                if response['winner'] is not None:
                    raise AttributeError

                order = response.get('indice')
                sql = "DELETE FROM `tombola_2020_lots` WHERE `id`= %s "
                cur.execute(sql, id)
                con.commit()

                sql = "UPDATE `tombola_2020_lots` SET `indice`=`order`-1 WHERE `indice`>%s"
                cur.execute(sql, order)
                con.commit()

                get_all_batches = "SELECT * FROM `tombola_2020_lots` ORDER BY `indice` ASC;"
                cur.execute(get_all_batches)
                con.commit()
                response = cur.fetchall()

                return json.dumps(response)

            except AttributeError:
                return json.dumps({'error': 'batch allready won'})

            except Exception as e:
                if con:
                    con.rollback()
                error = json.dumps({'error': "an error occured"})
                return error
            finally:
                cur.close()

    def update_batch(self, id, name):
        """
        update a batch with its id
        and return the updated list with updated orders
        """

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                get_order_sql = "SELECT `indice`, `winner` from `tombola_2020_lots` WHERE `id`= %s "
                cur.execute(get_order_sql, id)
                con.commit()
                response = cur.fetchone()

                if response['winner'] is not None:
                    raise AttributeError

                sql = "UPDATE `tombola_2020_lots` SET `name`=%s WHERE `id`= %s "
                cur.execute(sql, (name, id))
                con.commit()

                get_all_batches = "SELECT * FROM `tombola_2020_lots` ORDER BY `indice` ASC;"
                cur.execute(get_all_batches)
                con.commit()
                response = cur.fetchall()

                return json.dumps(response)

            except AttributeError:
                return json.dumps({'error': 'batch allready won'})

            except Exception as e:
                if con:
                    con.rollback()
                error = json.dumps({'error': "an error occured"})
                return error
            finally:
                cur.close()
    def change_indice(self, id, indice):
        """
        change the indice of one batch and update all the others.
        """

        con = dbskiut_con()
        con.begin()
        with con:
            try:
                cur = con.cursor()
                get_order_sql = "SELECT `indice` from `tombola_2020_lots` WHERE `id`= %s "
                cur.execute(get_order_sql, id)
                con.commit()
                response = cur.fetchone()

                actual_indice = int(response['indice'])
                indice = int(indice)
                if indice > actual_indice:
                    print('on entre bien la ')
                    others_sql = "UPDATE `tombola_2020_lots` SET `indice`= `indice` -1 WHERE `indice`> %s and `indice`<= %s;"
                    cur.execute(others_sql, (actual_indice, indice))
                    print('premiere ok')
                    indice_sql = "UPDATE `tombola_2020_lots` SET `indice`=%s WHERE `id`=%s";
                    cur.execute(indice_sql, (indice, id))
                    print('deuxieme ok')
                    con.commit()


                if indice < actual_indice:
                    others_sql = "UPDATE `tombola_2020_lots` SET `indice`= `indice` + 1 WHERE `indice`< %s and `indice`>= %s;"
                    cur.execute(others_sql, (actual_indice, indice))

                    indice_sql = "UPDATE `tombola_2020_lots` SET `indice`=%s WHERE `id`=%s";
                    cur.execute(indice_sql, (indice, id))
                    con.commit()

                get_all_batches = "SELECT * FROM `tombola_2020_lots` ORDER BY `indice` ASC;"
                cur.execute(get_all_batches)
                con.commit()
                response = cur.fetchall()
                return json.dumps(response)

            except AttributeError:
                return json.dumps({'error': 'batch allready won'})

            except Exception as e:
                if con:
                    con.rollback()
                print(e)
                error = json.dumps({'error': "an error occured"})
                return error
            finally:
                cur.close()

    def get_win(self, user):
        """
        method to get the wining (or loosing) batch for a user
        if the user wins several times, we give him the best batch

        - get total number which have not been played (where has_played_tombola is not true in user) => total_tickets
        - get total of user tickets => user_tickets
        - get all available presents (where winner is NULL) => batches
        - run a random beetween 0 and total_tickets for user_tickets times
        - get the best solution (lower number)
        - check a batch with the indice of the number is available and set  "win" to the batch OR None
        - update the tombola_2020_lots table and set winner if a batch has is found
        - delete all tickets bought by the logged user
        - return the result
        """
        try:
            user_stats = Tombola().get_user_stats(user)
            user_stats = json.loads(user_stats)

            total_tickets = user_stats['total_tickets']
            user_tickets = user_stats['ticket1'] + 5*user_stats['ticket5'] + 10*user_stats['ticket10']

            if user_tickets == 0:
                raise AttributeError

            print(f'Cet utilisateur a: {user_tickets} tickets. Il y a en tout {total_tickets}')
            con = dbskiut_con()
            con.begin()
            with con:
                try:
                    cur = con.cursor()
                    # get all availables batches
                    sql = "SELECT * FROM `tombola_2020_lots` WHERE `winner` IS NULL ORDER BY `indice` ASC;"
                    cur.execute(sql)
                    con.commit()
                    batches = cur.fetchall()

                    # run random for user_tickets times
                    best = total_tickets
                    for ticket in range(user_tickets):
                        launch = randrange(total_tickets)
                        best = launch if launch < best else best

                    # get the win batch if won or None if not
                    try:
                        win = batches[best]
                    except:
                        win = None

                    # insert user login in the tombola_2020_lots
                    if win is not None:
                        win_id = win.get('id')
                        sql = "UPDATE `tombola_2020_lots` SET `winner`=%s WHERE `id`=%s;"
                        cur.execute(sql, (user.get_login(), win_id))
                        result = {'tombola_result': win}
                    # return 0 value for loosers
                    else:
                        result = {'tombola_result': 0}

                    # delete all user batches
                    sql = "DELETE FROM `tombola_2020` WHERE `login_user`=%s;"
                    cur.execute(sql, (user.get_login()))

                    # Here we commit for the UPDATE and the DELETE
                    # if an error occure all will be rollback to avoid problems
                    con.commit()

                    return json.dumps(result)

                except Exception as e:
                    if con:
                        con.rollback()
                    raise e
                finally:
                    cur.close()

        except AttributeError:
            return json.dumps({'error': 'User has no available tickets.'})

        except Exception as e:
            return e
