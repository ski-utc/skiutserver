from user.user import User
from db import dbskiut_con
from globals import send_skiutc_mail
import pymysql
from datetime import datetime, timedelta

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
    def __init__(self, login=""):
        self.login = login
        self.shotgun = False
        if len(login) < 9:
            self._add_to_shotgun()

    @staticmethod
    def _organize_table():
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
            shotgun_list = cur.fetchall()
            for shotgun in shotgun_list:
                user = User.build_user_from_login(shotgun["login"])
                if not user.is_valid() or not user.is_cotisant():
                    sql = "DELETE FROM `shotgun-etu_2020` WHERE login=%s"
                    try:
                        cur.execute(sql,shotgun["login"])
                    except pymysql.err.InternalError as error:
                        code, message = error.args
                        print(">>>>>>>>>>>>>", code, message)
                    except pymysql.err.IntegrityError as error:
                        code, message = error.args
                        print(">>>>>>>>>>>>>", code, message)
                    cur.commit()
            cur.close()

    def _add_to_shotgun(self):
        """
        Function to add row in shotgun used for the shotgun
        Need to check after for cotisant and valid !
        :return: True if added, false else
        """
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "INSERT INTO `shotgun-etu_2020` (login) VALUES (%s)"
            try:
                cur.execute(sql, self.login)
                con.commit()
            except pymysql.InternalError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
                self.shotgun = False
            except pymysql.err.IntegrityError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
                self.shotgun = False
            finally:
                if cur.lastrowid:
                    self.shotgun = True
                else:
                    self.shotgun = False
                cur.close()

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

    @staticmethod
    def check_time():
        valid = False
        validity_date = datetime(2019, 10, 15, 0, 0, 0, 0)
        if datetime.now() - validity_date > timedelta(0):
            valid = True
        return valid

    @staticmethod
    def send_result_mail():
        """
        Send mail to every user in shotgun table
        - shotgun réussi
        - shotgun F
        :return:
        """
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            sql = "SELECT * FROM `shotgun-etu_2020`"
            try:
                cur.execute(sql)
            except pymysql.err.InternalError as error:
                code, message = error.args
                print(">>>>>>>>>>>>>", code, message)
            except pymysql.err.IntegrityError as error:
                code, message = error.args
                print(">>>>>>>>>>>>>", code, message)
            finally:
                cur.close()
        all_shotgun = cur.fetchall()
        for user, place in enumerate(all_shotgun):
            login = user["login"]
            mail = login + "@etu.utc.fr"
            #check si l'utilisateur a reussi le shotgun
            msg = ""
            if place < 301:
                msg = """Bonsoir,<br /><br />
                    Voici votre résultat à Shotgun Ski\'UTC 2020 pour le semestre de A19 : A, MENTION <br />
                    Vous aurez l\'honneur d\'être parmi nous lors de ce voyage de folie !<br />
                    Vous allez recevoir sous peu un autre mail avec plus d\'informations.<br /><br />

                    Ski\'UTC 2020 qui vous aime <3<br /><br />
                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.
                    """
            else:
                msg = """Bonsoir,<br /><br />
                      Voici votre résultat à Shotgun Ski\'UTC 2020 pour le semestre de A19 : FX, INSUFFISANT <br />
                      Ne vous inquiétez pas.. C\'est pas si difficile de se lever à 1h pour un shotgun physique !<br />
                      Vous allez recevoir bientôt un autre mail avec plus d\'informations.<br /><br />
                      Ski\'UTC 2020 qui vous aime quand même <3<br /><br />
                       ---------------<br />
                       Ceci est un mail automatique, Merci de ne pas y répondre.
                    """

            send_skiutc_mail(mail, "SKI'UTC 20 - Votre resultat", msg)
