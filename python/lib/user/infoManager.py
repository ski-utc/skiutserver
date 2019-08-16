from user.user import User
from shotgun.shotgun import Shotgun
from db import dbskiut_con
import pymysql

class infoManager(User):
    """
    Manager and Handler of informations for a user that has shotgunned
    This class handle information building, changind and so on
    """
    def __init__(self):
        print("built")

    @staticmethod
    def construct_from_shotgun():
        """
        Build the native database from shotgun by crossing login on the shotgun to the User class and inserting into db
        :return:
        """
        list = Shotgun.get_shotgun()
        sql = "INSERT INTO `users_2020`  (firstName, lastName, isAdult, etumail, login) VALUES (%s, %s, %s, %s, %s)"
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            for user in list:
                info_user = User.build_user_from_login(user["login"])
                info_user_tuples = (info_user.get_surname(), info_user.get_name(), info_user.is_adult(), info_user.get_email(), user["login"])
                try:
                    cur.execute(sql, info_user_tuples)
                except pymysql.err.InternalError as error:
                    code, message = error.args
                    print (">>>>>>>>>>>>>", code, message)
                except pymysql.err.IntegrityError as error:
                    code, message = error.args
                    print (">>>>>>>>>>>>>", code, message)
            con.commit()
        return {}

    @staticmethod
    def construct_from_tremplin():
        """
        Add tremplin to global table of skiutc users_2020
        :return:
        """

        return {}

    def change_info(self, **kwargs):
        """
        Changes the information of the user already existing in the db
        will be called with list of inforrmation to update (in kwargs)
        :return:
        """

        return {}
