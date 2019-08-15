from user.user import User
from db import dbskiut_con
import pymysql

class Shotgun:
    """
    Shotgun handler class
    - Handle shotgun api calls
    - Build the shotgun
    - Check for users

    A shotgunner shotguns by its login

    we need to store in db :
    login
    timestamp

    reset auto increment :
    ALTER TABLE `shotgun-etu_2020` DROP `idshotgun`;
    ALTER TABLE `shotgun-etu_2020` AUTO_INCREMENT = 1;
    ALTER TABLE `shotgun-etu_2020` ADD `idshotgun` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    """
    def __init__(self):
        self.shotgun = None

    @staticmethod
    def add_to_shotgun(login=None):
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "INSERT INTO `shotgun-etu_2020` (login) VALUES (%s)"
            try:
                cur.execute(sql, login)
                con.commit()
            except pymysql.InternalError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
            finally:
                cur.close()

    @staticmethod
    def kill_doublons():
        """
        method to delete duplicate by keeping the first one
        WITH cte AS (
            SELECT
                idshotgun,
                login,
                timestamp,
                ROW_NUMBER() OVER (
                    PARTITION BY
                        login
                    ORDER BY
                        login
                ) row_num
             FROM
                ``shotgun-etu_2019`
        )
        DELETE FROM cte
        WHERE row_num > 1;
        :return:
        """
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "WITH t_deleted AS (DELETE FROM `shotgun-etu_2020` RETURNING *), t_inserted AS (SELECT idshotgun, login, timestamp, ROW_NUMBER() OVER ( PARTITION BY login ORDER BY login ) rnk FROM t_deleted) INSERT INTO T SELECT name FROM t_inserted WHERE rnk=1"
            try:
                cur.execute(sql)
                con.commit()
            except pymysql.InternalError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
            finally:
                cur.close()



