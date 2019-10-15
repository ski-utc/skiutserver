import json
from config.urls import _SMTPS_MAIL, _MAIL_LOGIN, _MAIL_PASSWORD, _MAIL_SKIUTC
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import smtplib

def file_to_json(path):
    with open(path, 'r') as f:
        data = f.read()
    json_data = json.loads(data)
    return json_data

def send_skiutc_mail(target, subjet, body):
    server = smtplib.SMTP(_SMTPS_MAIL, 587)
    server.ehlo()
    server.starttls()
    server.ehlo()
    server.login(_MAIL_LOGIN, _MAIL_PASSWORD)
    fromaddr = _MAIL_SKIUTC
    toaddr = target
    msg = MIMEMultipart()
    msg['From'] = "SkiUTC <"+fromaddr+">"
    msg['To'] = toaddr
    msg['Subject'] = subjet
    msg.attach(MIMEText(body, 'html'))
    server.sendmail(fromaddr, toaddr, msg.as_string())

class PackSwitcher(object):
    def numbers_to_packname(self, argument):
        """Dispatch method"""
        method_name = 'pack_' + str(argument)
        method = getattr(self, method_name, lambda: "Invalid number")
        return method()

    def pack_2(self):
        return "mythic"

    def pack_1(self):
        return "heroique"

    def pack_0(self):
        return "normal"


class ItemsSwitcher(object):
    def numbers_to_itemsname(self, argument):
        """Dispatch method"""
        method_name = 'items_' + str(argument)
        method = getattr(self, method_name, lambda: "Invalid number")
        return method()

    def items_0(self):
        return "ski_shoes"

    def items_1(self):
        return "snow_shoes"

    def items_4(self):
        return "ski"

    def items_5(self):
        return "snow"

    def items_2(self):
        return "ski_seul"

    def items_3(self):
        return "snow_seul"