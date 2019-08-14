from user.user import User
from db import dbskiut_con

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
    ALTER TABLE `users` DROP `id`;
    ALTER TABLE `users` AUTO_INCREMENT = 1;
    ALTER TABLE `users` ADD `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    """
    def __init__(self):
        self.shotgun = None

    @staticmethod
    def add_to_shotgun(login=None):
        con = dbskiut_con()
        return {}

