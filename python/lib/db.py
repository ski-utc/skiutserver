#!/usr/bin/env python
# -*- coding: utf-8 -*-
from config.urls import _CAS_URL, _GINGER_URL, _SKIUTC_SERVICE, _DB_PASSWORD, _DB_HOST, _DB_USER, _DB_NAME
import pymysql


def dbskiut_con():
    con = pymysql.connect(host=_DB_HOST, db=_DB_NAME, user=_DB_USER, password=_DB_PASSWORD, charset='utf8mb4',
                          cursorclass=pymysql.cursors.DictCursor)
    return con

if __name__ == '__main__':  # pragma: no coverage
    _main(sys.argv)
