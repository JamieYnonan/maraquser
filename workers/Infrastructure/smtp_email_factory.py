import smtplib
import json
import os.path

from smtplib import SMTP


class SMTPEmailFactory:

    @staticmethod
    def build() -> smtplib.SMTP:
        """

        :rtype: smtplib.SMTP
        """

        with open(os.path.dirname(__file__) + '/../config.json', 'r') as f:
            config = json.load(f)

        server: SMTP = smtplib.SMTP(config['SMTP']['HOST'], config['SMTP']['PORT'])
        server.starttls()
        server.login(config['SMTP']['USER'], config['SMTP']['PASSWORD'])

        return server
