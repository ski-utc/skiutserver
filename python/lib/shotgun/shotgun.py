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
    def __init__(self,login=""):
        self.login = login
        self.shotgun = False
        self._add_to_shotgun()

    def _add_to_shotgun(self):
        """
        Function to add row in shotgun used for the shotgun
        we check for cotisation and login trusted
        login cannot be added twice
        :return: True if added, false else
        """
        user = User.build_user_from_login(self.login)
        if user.is_valid() and user.is_cotisant():
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
                    self.shotgun = False
                except pymysql.err.IntegrityError as error:
                    code, message = error.args
                    print (">>>>>>>>>>>>>", code, message)
                    self.shotgun =  False
                finally:
                    cur.close()
                self.shotgun =  True
        else:
            self.shotgun =  False

    def shotgun_valid(self):
        return self.shotgun

    @staticmethod
    def get_shotgun():
        """
        :return: Array of object returning shotgun
        """
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "SELECT * FROM `shotgun-etu_2020` WHERE idshotgun < 301"
            try:
                cur.execute(sql)
            except pymysql.err.InternalError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
            except pymysql.err.IntegrityError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
            finally:
                cur.close()
            shotgun = cur.fetchall()
            #array of all shotgun in object
            return shotgun
