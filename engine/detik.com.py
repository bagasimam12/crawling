import sys
import validators
import requests
from datetime import datetime
from bs4 import BeautifulSoup
from concurrent.futures import ThreadPoolExecutor
from env_helper import load_env_api
import os
from database_helper import insert_bulk_data

load_env_api()

if len(sys.argv) < 2:
    print("Argumen tidak lengkap")
    exit()

search = sys.argv[1]
limit = os.getenv("LIMIT_ASYNC", 20)
limit = int(limit)

page = requests.get(
    "https://www.detik.com/search/searchall?query=" + search + "&siteid=2"
)
soup = BeautifulSoup(page.text, "html.parser")
paging = soup.find(class_="paging")
if paging == None:
    exit()
page = paging.find_all("a")
length = len(page) - 1
array_data = []


def crawling_data(halaman):
    print("run di page ", halaman)
    angka = str(halaman)
    page = requests.get(
        "https://www.detik.com/search/searchall?query="
        + search
        + "&siteid=2&sortby=time&page="
        + angka
    )
    soup = BeautifulSoup(page.text, "html.parser")
    list_berita = soup.find(class_="list-berita")
    if list_berita == None:
        return
    articles = list_berita.find_all("article")
    if articles == None:
        return

    for i, list in enumerate(articles):
        konten = "tidak ada konten"
        url = "tidak ada konten/url sudah rusak"
        judul = "tidak ada konten/url sudah rusak"
        if list.a != None:
            url = list.a["href"]
            judul = list.a.find(class_="box_text").h2.text

        if validators.url(url):
            cPage = requests.get(url)
            cSoup = BeautifulSoup(cPage.text, "html.parser")
            body = cSoup.find(class_="detail__body")
            if (body) != None:
                konten = str(body.get_text(strip=True))
        else:
            konten = "tidak ada konten"

        if konten == "tidak terdefinisi":
            continue

        array_data.append(
            [
                (
                    judul,
                    konten,
                    datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                )
            ]
        )

        print(array_data)
        exit()

    query = (
        "INSERT INTO hasil_scrapper"
        + " (judul,isi_berita,dibuat_pada) VALUES (%s, %s, %s)"
    )
    insert_bulk_data(query, array_data)


number_of_thread = 6
number_of_thread = int(number_of_thread)

pool = ThreadPoolExecutor(max_workers=number_of_thread)
for x in range(1, (int(length) + 1)):
    if x > limit:
        break
    pool.submit(crawling_data, str(x))
pool.shutdown(wait=True)
