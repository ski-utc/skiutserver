from user.user import User
from shotgun.shotgun import Shotgun

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
