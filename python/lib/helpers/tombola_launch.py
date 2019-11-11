from db import dbskiut_con
import pymysql
import sys
from user.user import User
from tombola.tombola_lots import TombolaLots

def main():
    tot = []
    con = dbskiut_con()
    con.begin()
    with con:
        try:
            cur = con.cursor()
            sql = "SELECT DISTINCT `login_user` from `tombola_2020`;"
            cur.execute(sql)
            con.commit()
            response = cur.fetchall()
        except Exception as e:
            if con:
                con.rollback()
            print(e)
            return e
        finally:
            cur.close()

    for u in response:
        user = User.build_user_from_login(u["login_user"])
        user_lot = TombolaLots().get_win(user)
        tot.append(user_lot)
        print(user_lot)

    return tot


if __name__ == '__main__':
    main()