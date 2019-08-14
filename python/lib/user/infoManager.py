from user.user import User

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
        Build the native database from shotgun by crossing login on the shotgun to the User class
        :return:
        """

        return {}

    @staticmethod
    def construct_from_tremplin():
        """
        Add tremplin to global table of skiutc users_2020
        :return:
        """

        return {}


