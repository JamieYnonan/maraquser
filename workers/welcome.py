#!/usr/bin/env python
import json
from email import message

from Infrastructure.pika_factory import PikaFactory
from Infrastructure.smtp_email_factory import SMTPEmailFactory

connection = PikaFactory.build()
channel = connection.channel()
channel.queue_declare(queue='email_user_welcome', durable=True)
channel.basic_qos(prefetch_count=1)


def callback(ch, method, properties, body):
    user = json.loads(body)
    __send_mail(user['name'], user['lastName'], user['email'])

    ch.basic_ack(delivery_tag=method.delivery_tag)


def __send_mail(name: str, last_name: str, email: str):
    with open('config.json', 'r') as f:
        config = json.load(f)

    body = 'Welcome: %s %s' % (name, last_name)

    msg = message.Message()
    msg.add_header('from', config['WELCOME_EMAIL']['FROM'])
    msg.add_header('to', email)
    msg.add_header('subject', config['WELCOME_EMAIL']['SUBJECT'])
    msg.set_payload(body)

    smtp = SMTPEmailFactory.build()
    smtp.send_message(msg, from_addr=config['WELCOME_EMAIL']['FROM'], to_addrs=[email])


channel.basic_consume(queue='email_user_welcome', on_message_callback=callback)
channel.start_consuming()
