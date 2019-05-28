FROM python:3.7

MAINTAINER jamiea31@gmail.com

WORKDIR /workers

RUN apt-get update
RUN apt-get install iputils-ping -y

ARG USERNAME_LOCAL
ARG UID_LOCAL
ARG GID_LOCAL
RUN groupadd -g $GID_LOCAL $USERNAME_LOCAL
RUN useradd -u $UID_LOCAL -g $GID_LOCAL $USERNAME_LOCAL

ENV TZ=America/Lima
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

