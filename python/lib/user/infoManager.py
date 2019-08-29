from user.user import User
from shotgun.shotgun import Shotgun
from db import dbskiut_con
from globals import file_to_json, ItemsSwitcher, PackSwitcher
import pymysql


class infoManager(User):
    """
    Manager and Handler of informations for a user that has shotgunned
    This class handle information building, changind and so on
    1: build a user with infoManager
    2: change info
    """
    def __init__(self, login=None):
        super().__init__(login=login)
        self._recap_user = None
        self._price = 0
        self._key_infos = ["address", "zipcode", "tel", "city", "size", "weight", "shoesize", "transport", "transport-back",
                     "food", "pack", "equipment", "items", "assurance_annulation", "assurance_rapa"]

    def update_price(self, p):
        self._price += p

    @staticmethod
    def construct_from_shotgun():
        """
        Build the native database from shotgun by crossing login on the shotgun to the User class and inserting into db
        :return:
        """
        liste = Shotgun.get_shotgun()
        sql = "INSERT INTO `users_2020`  (firstName, lastName, isAdult, etumail, login) VALUES (%s, %s, %s, %s, %s)"
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            for user in liste:
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

    @staticmethod
    def construct_from_login(login=None):
        """
        Add tremplin to global table of skiutc users_2020
        :return:
        """

        return infoManager(login)

    def change_info(self, **kwargs):
        """
        Changes the information of the user already existing in the db !
        will be called with list of inforrmation to update (in kwargs)
        attr to change are in the dict kwargs
            we already have :
        -firstName, lastName, isAdult, email
            changeable :
        -- perso --
        - adress
        - zipcode
        - city
        - tel

        -- ski --
        - size
        - weight
        - shoesize
        - equipment (ski, rien, snow)
        - pack ( gold, etc)
        - items (chaussure ski, chaussure snow, ski seul, snow seul,; chaussure seules)

        -- transport --
        - transport (paris, compi, sans)
        - transport-back ville depart (0,1)

        -- divers --
        - food (avec porc, sans porc, sans)
        - assurance annul (0,1)
        - assurance rapa (0,1)
        :return:
        """
        info = []
        for key in self._key_infos:
            info.append(kwargs[key])

        info.append(self.get_login())
        info_tuple = tuple(info)
        sql = "UPDATE `users_2020` " \
              "SET `address`=%s,`zipcode`=%s,`tel`=%s,`city`=%s,`size`=%s,`weight`=%s,`shoesize`=%s," \
              "`transport`=%s,`transport-back`=%s,`food`=%s,`pack`=%s,`equipment`=%s,`items`=%s," \
              "`assurance_annulation`=%s, `assurance_rapa`=%s " \
              "WHERE login=%s"

        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            try:
                cur.execute(sql, info_tuple)

            except pymysql.err.InternalError as error:
                code, message = error.args
                print(">>>>>>>>>>>>>", code, message)
                return False
            except pymysql.err.IntegrityError as error:
                code, message = error.args
                print(">>>>>>>>>>>>>", code, message)
                return False
            con.commit()
        return True

    def get_total_price(self):
        """
        :return: int total price of user with his options
        """
        sql = "SELECT * FROM `users_2020`  WHERE `login`=%s"
        con = dbskiut_con()
        con.begin()
        with con:
            cur = con.cursor()
            try:
                cur.execute(sql, self.get_login())
                self._recap_user = cur.fetchone()
            except pymysql.err.InternalError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)
            except pymysql.err.IntegrityError as error:
                code, message = error.args
                print (">>>>>>>>>>>>>", code, message)

        list_prices = file_to_json('meta/prices.json')
        user_info = self.get_user_info()
        if user_info.get('type') == 0:
            self.update_price(list_prices["base_pack_etu"])
        if user_info.get('food') == 1:
            self.update_price(list_prices["food_pack"])
        if user_info.get('assurance_annulation') == 1:
            self.update_price(list_prices["assurance"])
        """
        Packs neige now
        """
        pack_switcher = PackSwitcher()
        items_switcher = ItemsSwitcher()
        pack = pack_switcher.numbers_to_packname(user_info.get('pack'))
        items = items_switcher.numbers_to_itemsname(user_info.get('items'))
        self.update_price(list_prices["packs"][pack][items])

        return self._price

    def get_price_with_recap(self):
        price = self.get_total_price()
        user_info = self.get_user_info()

        return {"price": price, "recap": user_info}
