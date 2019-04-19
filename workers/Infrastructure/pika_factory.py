import pika
import json
import os.path


class PikaFactory:

    @staticmethod
    def build() -> pika.BlockingConnection:
        """

        :rtype: pika.BlockingConnection
        """

        with open(os.path.dirname(__file__) + '/../config.json', 'r') as f:
            config = json.load(f)

        credentials = pika.PlainCredentials(config['RABBIT']['USER'], config['RABBIT']['PASSWORD'])
        return pika.BlockingConnection(
            pika.ConnectionParameters(config['RABBIT']['HOST'],
                                      config['RABBIT']['PORT'],
                                      config['RABBIT']['VHOST'],
                                      credentials))
