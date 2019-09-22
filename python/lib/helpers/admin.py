from db import dbskiut_con
import secrets
import datetime

user_list = ["hpaignea","qrichard","lphilber","metzvale","lromanet","mandonpa","leroyjul","cflorimo","sellamva","cogentdo","crappecl","trenaudi","obriotos","lmaignan","amaingue","abrossar","phannart","tleporho"]

def set_admin():
    con = dbskiut_con()
    con.begin()
    with con:
        cur = con.cursor()
        for user in user_list:
            token = secrets.token_hex(20)
            date = datetime.datetime.now()
            validity = date.strftime('%Y-%m-%d %H:%M:%S')
            sql_tuples = (user, token, validity, 1)
            sql = "INSERT INTO auth VALUES (%s,%s,%s,%s)"
            cur.execute(sql, sql_tuples)
            con.commit()
            print(user + " : done")


if __name__ == '__main__':
    set_admin()