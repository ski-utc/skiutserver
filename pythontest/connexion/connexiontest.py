import unittest
from connexion.connexion import ConnexionHandler, dbskiut_con
from unittest.mock import Mock, MagicMock
from unittest.mock import patch, call

class ConnexionHandlerTest(unittest.TestCase):
    def setUpLogin(self):
        self.login = "logintest"
        return ConnexionHandler.build_handler(self.login)

    def setUpToken(self):
        self.token = "tokentest"
        self.connexionhandlertoken = ConnexionHandler.build_handler(self.token)

    @patch('dbskiut_con')
    def createNewConnexion(self):
        mock_cursor = MagicMock()
        test_data = [{'login': 'logintest', 'token': 'tokentest', 'validity': '2019-08-14 15:05:22'}]
        mock_cursor.fetchone.return_value = test_data
        mock_pymysql.return_value.__enter__.return_value = mock_cursor

        mock_pymysql.return_value.cursor.return_value.__enter__.return_value = mock_cursor

        CoHandler = setUpLogin()
        res = CoHandler.create_connexion()

        print(res)
        self.assertEqual(test_data, res)


if __name__ == '__main__':
    unittest.main()