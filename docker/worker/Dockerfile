FROM python:3.7

MAINTAINER jamiea31@gmail.com

WORKDIR /workers

RUN apt-get update
RUN apt-get install iputils-ping -y

ARG TZ
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh

COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

