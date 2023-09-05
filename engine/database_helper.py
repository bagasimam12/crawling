import mysql.connector
from pathlib import Path
from env_helper import load_env_api
import random

load_env_api()


def connection_obj(nama_db="test-lumen"):
    connection = mysql.connector.connect(
        database=nama_db,
        user="root",
        password="",
        host="localhost",
        port="3306",
    )
    return connection


def insert_single_data(query, data, nama_db="test-lumen"):
    try:
        connection = mysql.connector.connect(
            database=nama_db,
            user="root",
            password="",
            host="localhost",
            port="3306",
        )

        cursor = connection.cursor()
        args = ",".join(
            cursor.mogrify("(%s,%s,%s,%s,%s)", i).decode("utf-8") for i in data
        )
        cursor.execute(query + "" + args)
        connection.commit()
        count = cursor.rowcount
        print(count, "Data berhasil disimpan")

        if connection:
            cursor.close()
            connection.close()
            print("MySql connection is closed")
    except (Exception, mysql.connector.Error) as error:
        print("Data gagal disimpan", error)


def insert_bulk_data(query, array_data, nama_db="test-lumen"):
    try:
        connection = mysql.connector.connect(
            database=nama_db,
            user="root",
            password="",
            host="localhost",
            port="3306",
        )

        cursor = connection.cursor()

        for data_list in array_data:
            for data_tuple in data_list:
                judul, isi_berita, dibuat_pada = data_tuple
                values = (judul, isi_berita, dibuat_pada)
                cursor.execute(query, values)

        connection.commit()
        count = cursor.rowcount
        print(count, "Data berhasil disimpan")

        if connection:
            cursor.close()
            connection.close()
            print("MySql connection is closed")
    except (Exception, mysql.connector.Error) as error:
        print("Data gagal disimpan", error)


def execute_query(query, nama_db="test-lumen"):
    try:
        connection = mysql.connector.connect(
            database=nama_db,
            user="root",
            password="",
            host="localhost",
            port="3306",
        )

        cursor = connection.cursor()
        cursor.execute(query)

        connection.commit()
        count = cursor.rowcount
        print(count, "Data berhasil disimpan")

        if connection:
            cursor.close()
            connection.close()
            print("MySql connection is closed")
    except (Exception, mysql.connector.Error) as error:
        print("Data gagal disimpan", error)


def generate_uuid():
    hex_digits = "0123456789abcdef"
    uuid = ""
    for i in range(32):
        uuid += hex_digits[random.randint(0, 15)]
        if i in [7, 11, 15, 19]:
            uuid += "-"
    return uuid
