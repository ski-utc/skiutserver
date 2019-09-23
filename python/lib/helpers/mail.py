from globals import send_skiutc_mail
from db import dbskiut_con
import pymysql
import sys
from shotgun.shotgun import Shotgun
"""
Every usefull script to send any email
"""


def send_rappel_payment():
    """
    Envoie un email à toutes les personnes ayant réussi le shotgun pour les prévenir de l'ouverture de la billeterie + diverses infos
    """
    con = dbskiut_con()
    con.begin()
    with con:
        cur = con.cursor()
        sql = "SELECT * FROM `users_2020`"
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
    list_users = cur.fetchall()
    for user, place in enumerate(list_users):
        login = user["login"]
        mail = login + "@etu.utc.fr"
        msg = """
                    Coucou et bienvenue dans l’aventure Ski’UTC toi qui a reçu une belle mention A!  😉<br /><br />
                    La billeterie pour régler le séjour Ski\'UTC ouvre aujourd\'hui sur le site de Ski\'UTC: http://assos.utc.fr/skiutc/ <br />
                    Tu peux aller sur le site rentrer tes infos et choix pour les packs !<br /><br />
                    Tu as jusqu\'au XXXXXXXXXXXXXXXX pour payer, sinon ta place sera remise en jeu lors du shotgun physique !<br />
                    Le paiement se fera par Pay\'UTC sur le site après avoir donné tes infos, ou par chèque (mais privilégie Pay\'UTC stp <3 )<br />
                    Pour information il y aura des frais de 4€ pour le paiement en ligne. <br /><br />
                    Si tu veux payer par chèque, il devra être à l\'ordre de BDE UTC SKIUTC avec le montant calculé sur le site. Des perms seront tenues à la rentrée pour récupérer les chèques !<br /><br />
                    N\'oublie pas de préparer un chèque de caution, qui devra être de 100€. On te tiendra au courant lorsque tu devras nous le donner ! <br /><br />
                    La bise givrée 😘 ❄️<br /><br />
                    La team Ski’UTC <br /><br /><br />
                    PS : Le shotgun des chambres se déroulera plus tard, lorsque tout le monde aura payé !<br />
                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.
        """

        send_skiutc_mail(mail, "SKI'UTC 20 - CHOIX DES OPTIONS", msg)


def send_shotgun_physique():
    """
    Envoie un mail à toutes les personnes ayant fail le shotgun
    """
    con = dbskiut_con()
    con.begin()
    with con:
        cur = con.cursor()
        sql = "SELECT * FROM `shotgun-etu_2020` WHERE id > 300"
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
    list_users = cur.fetchall()
    for user, place in enumerate(list_users):
        login = user["login"]
        mail = login + "@etu.utc.fr"
        msg = """
                    Salut à toi qui a raté de peu le shotgun !<br /><br />
                    On te redonne une chance d’avoir une place pour ce super voyage, et finir le semestre avec tes potes sur les pistes de Val d'Allos !<br />
                    On remet en jeu des places pour les plus motivés. Pour ça il suffit de suivre les étapes suivantes :<br /><br />
                    -    A partir de XXXXX tu vas pouvoir retourner sur le site et cliquer sur le bouton Shotgun ou bien aller sur le lien suivant http://assos.utc.fr/skiutc/shotgun.html et entrer ton login. Tu seras alors mis dans la base de données des gens participant au shotgun physique.<br /><br />
                    -    A partir de XXXXX tu pourras accéder à la page de choix des options sur le site et remplir tes informations. Tu pourras alors avoir le prix exact que tu auras à payer.<br /><br />
                    -    Dernière étape : XXXXXXXXXXX ! Viens avec ton chèque (pour payer le voyage plus un chèque de caution de 100€). Les premiers inscrits auront leur place validée !<br /><br />
                    J’espère que tu réussiras cette quête !<br /><br />
                    La team Ski’UTC 
                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.
        """
        send_skiutc_mail(mail, "SKI'UTC 20 - SHOTGUN PHYSIQUE", msg)


def main():
    arg = sys.argv[0]
    if arg == "rappel":
        send_rappel_payment()
    if arg == "physique":
        send_shotgun_physique()
    if arg == "shotgun"
        Shotgun.send_result_mail()

if __name__ == '__main__':
    main()