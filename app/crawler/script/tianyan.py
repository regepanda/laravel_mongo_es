# -*- coding: utf-8 -*-
import requests
from bs4 import BeautifulSoup
import re
import sys
import json


# 爬去天眼查网站关键字搜索出来的列表页数据
def requestEyes():
    headers = {
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding': 'gzip, deflate, br',
        'Accept-Language': 'zh-CN,zh;q=0.9,en;q=0.8',
        'Cache-Control': 'max-age=0',
        'Connection': 'keep-alive',
        'Host': 'www.tianyancha.com',
        'Upgrade-Insecure-Requests': '1',
        'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36'
    }
    cookies = {
        'TYCID': '980ce5f0575c11e89d0063d282d3a9ab',
        'undefined': '980ce5f0575c11e89d0063d282d3a9ab',
        'ssuid': '4001289176',
        'aliyungf_tc': 'AQAAAHvyQTEzpwAADJmMtoPCD/+9jK5G',
        'csrfToken': 'Luu14ap9jSU4Lvua_woi-Qe9',
        'Hm_lvt_e92c8d65d92d534b0fc290df538b4758': '1546498197',
        '_ga': 'GA1.2.497910060.1533200307',
        '_gid': 'GA1.2.1629279000.1546498198',
        'bannerFlag': 'true',
        'token': '1417d7907d6d447b8e678b7f871e340d',
        '_utm': '91a65d41a62b466585411ddd1e99ff2c',
        'tyc-user-info': '%257B%2522claimEditPoint%2522%253A%25220%2522%252C%2522myQuestionCount%2522%253A%25220%2522%252C%2522explainPoint%2522%253A%25220%2522%252C%2522nickname%2522%253A%2522%25E8%258E%25AB%25E5%25B0%258F%25E8%25B4%259D%2522%252C%2522integrity%2522%253A%25220%2525%2522%252C%2522state%2522%253A%25220%2522%252C%2522announcementPoint%2522%253A%25220%2522%252C%2522vipManager%2522%253A%25220%2522%252C%2522discussCommendCount%2522%253A%25220%2522%252C%2522monitorUnreadCount%2522%253A%2522123%2522%252C%2522onum%2522%253A%25220%2522%252C%2522claimPoint%2522%253A%25220%2522%252C%2522token%2522%253A%2522eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxODA4NjgzNzg1OCIsImlhdCI6MTU0NjQ5ODk2NCwiZXhwIjoxNTYyMDUwOTY0fQ.Qt57Z68RNlSq8Tgfy_2NAeowSXL7pNvcYGIuTRiSOAqCkTQfATbXQjJbXXy_2abkQcYVRTGDP9eo2YMci1kmiA%2522%252C%2522redPoint%2522%253A%25220%2522%252C%2522pleaseAnswerCount%2522%253A%25220%2522%252C%2522bizCardUnread%2522%253A%25220%2522%252C%2522vnum%2522%253A%25220%2522%252C%2522mobile%2522%253A%252218086837858%2522%257D',
        'auth_token': 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxODA4NjgzNzg1OCIsImlhdCI6MTU0NjQ5ODk2NCwiZXhwIjoxNTYyMDUwOTY0fQ.Qt57Z68RNlSq8Tgfy_2NAeowSXL7pNvcYGIuTRiSOAqCkTQfATbXQjJbXXy_2abkQcYVRTGDP9eo2YMci1kmiA',
        'Hm_lpvt_e92c8d65d92d534b0fc290df538b4758': '1546498968'
    }

    startUrl = sys.argv[1]
    # startUrl = 'https://www.tianyancha.com/search?key=%E5%8C%97%E4%BA%AC%E6%88%BF%E5%9C%B0%E4%BA%A7';
    result = requests.get(startUrl, headers=headers, cookies=cookies)  # 在请求中设定头，cookie
    houseHtml = result.text
    soup = BeautifulSoup(houseHtml, "html.parser")

    datas = []
    for house in soup.findAll("div", class_="search-result-single"):
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

        contact = house.find_all("div", class_="col")
        if len(contact):
            script = contact[0].find(name="script")
            if script is None:
                tmp = contact[0].findAll(name="span")
                data['phone'] = tmp[1].string if len(tmp) >= 2 else None
            else:
                data['phone'] = script.string
        else:
            data['phone'] = None

        if len(contact) >= 2:
            script = contact[1].find(name="script")
            if script is None:
                tmp = contact[1].findAll(name="span")
                data['email'] = tmp[1].string if len(tmp) >= 2 else None
            else:
                data['email'] = script.string
        else:
            data['email'] = None
        datas.append(data)
    return datas


if __name__ == '__main__':
    # reload(sys)
    # sys.setdefaultencoding('utf-8')
    datas = requestEyes()
    string = json.dumps(datas)
    print(string)



