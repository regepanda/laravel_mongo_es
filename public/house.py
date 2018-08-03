# -*- coding: utf-8 -*-
import requests
from bs4 import BeautifulSoup
import re
# import MySQLdb
import sys
import json

reload(sys)
sys.setdefaultencoding('utf-8')

# 爬取京东的商品数据
def requestJD():
    user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5)'
    headers = {'User-Agent': user_agent}
    session = requests.session()
    request = session.get("https://search.jd.com/Search?keyword=%E7%8B%97%E7%AA%9D&enc=utf-8&wq=gou%27wo&pvid=771c8d0a4b614543840a572d0e923f2a", headers=headers)
    html = request.text

    soup = BeautifulSoup(html, "html.parser")
    datas = []
    for product in soup.findAll("div", class_="gl-i-wrap"):
        data = {}

        img = re.findall('source-data-lazy-img="(.*)" width', str(product.find("img", class_="err-product")))
        price = re.findall('<i>(.*)</i>', str(product.select(".p-price > strong > i")))
        name = re.compile(r'<[^>]+>', re.S).sub('', str(product.find("div", class_="p-name p-name-type-2").select("em")))
        comment = product.select(".p-commit > strong > a")[0].string
        shopName = product.select(".p-shop > span > a")
        shopName = shopName[0].string if len(shopName) else None

        data['img'] = img[0]
        data['price'] = price[0]
        data['name'] = name
        data['comment'] = comment
        data['shop_name'] = shopName
        datas.append(data)

    # conn = MySQLdb.connect()
    # for value in datas:
    #     print(value)

# 爬去天眼查网站关键字搜索出来的列表页数据
headers = {
            'Accept':'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding':'gzip, deflate, br',
            'Accept-Language':'zh-CN,zh;q=0.9,en;q=0.8',
            'Cache-Control':'max-age=0',
            'Connection':'keep-alive',
            'Host':'www.tianyancha.com',
            'Upgrade-Insecure-Requests':'1',
            'User-Agent':'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36'
         }
cookies = {
            'TYCID':'980ce5f0575c11e89d0063d282d3a9ab',
            'undefined':'980ce5f0575c11e89d0063d282d3a9ab',
            'ssuid':'4001289176',
            'aliyungf_tc':'AQAAAHvyQTEzpwAADJmMtoPCD/+9jK5G',
            'csrfToken':'eChII5aRZl0jAMOCBtK9UcK7',
            'Hm_lvt_e92c8d65d92d534b0fc290df538b4758':'1533191152,1533200307',
            '_ga':'GA1.2.497910060.1533200307',
            '_gid':'GA1.2.1106128874.1533200307',
            'bannerFlag':'true',
            'token':'2fd759c21a8c4cec8a95834238138802',
            '_utm':'52693bbe4d0240d488804dcef6f43a29',
            'tyc-user-info':'%257B%2522token%2522%253A%2522eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxODA4NjgzNzg1OCIsImlhdCI6MTUzMzI1OTQ5NSwiZXhwIjoxNTQ4ODExNDk1fQ.DczUY-thwcXuWpVmJDoMoFQx4O7IZkf8WNfxKvjYo7YhgmEwWAErJkfI55OTK9OVf00mdVdmXghkx1ekDAXXPw%2522%252C%2522integrity%2522%253A%25220%2525%2522%252C%2522state%2522%253A%25220%2522%252C%2522redPoint%2522%253A%25220%2522%252C%2522vipManager%2522%253A%25220%2522%252C%2522vnum%2522%253A%25220%2522%252C%2522onum%2522%253A%25220%2522%252C%2522mobile%2522%253A%252218086837858%2522%257D',
            'auth_token':'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxODA4NjgzNzg1OCIsImlhdCI6MTUzMzI1OTQ5NSwiZXhwIjoxNTQ4ODExNDk1fQ.DczUY-thwcXuWpVmJDoMoFQx4O7IZkf8WNfxKvjYo7YhgmEwWAErJkfI55OTK9OVf00mdVdmXghkx1ekDAXXPw',
            'Hm_lpvt_e92c8d65d92d534b0fc290df538b4758':'1533259497'
         }

startUrl = sys.argv[1]
result = requests.get(startUrl, headers=headers, cookies=cookies)  # 在请求中设定头，cookie
houseHtml = result.text
soup = BeautifulSoup(houseHtml, "html.parser")

datas = []
for house in soup.findAll("div", class_="search-result-single "):
    data = {}
    companyName = house.find("a", class_="name ")
    companyName = re.compile(r'<[^>]+>', re.S).sub('', str(companyName))
    data['company_name'] = companyName
    # companyName = re.findall('tyc-event-click="">(.*)</a>', str(house.select(".header > a")))
    # companyName = re.compile(r'<[^>]+>', re.S).sub('', str(companyName))
    # companyName = re.findall('\[\'(.*)\'\]', companyName)

    companyMes = house.findAll("div", class_="title text-ellipsis")
    companyPeople = re.compile(r'<[^>]+>', re.S).sub('', str(companyMes[0]))
    data['company_people'] = companyPeople
    companyPrice = re.compile(r'<[^>]+>', re.S).sub('', str(companyMes[1]))
    data['company_price'] = companyPrice
    companyTime = re.compile(r'<[^>]+>', re.S).sub('', str(companyMes[2]))
    data['company_time'] = companyTime

    contact = house.findAll("div", class_="col")
    if len(contact):
        data['phone'] = contact[0].select(".link-hover-click")[0].string
    if len(contact) >= 2:
        data['email'] = contact[1].select(".link-hover-click")[0].string
    datas.append(data)

string = json.dumps(datas)

# url = 'http://laravel_mongo_es.com/test'
# r = requests.post(url, json=datas)
print(string)




