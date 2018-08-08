# -*- coding: utf-8 -*-
import requests
from bs4 import BeautifulSoup
import re
import sys
import json
import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options


# 爬取京东的商品数据
def requestJD():
    headers = {
        'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'accept-encoding': 'gzip, deflate, br',
        'accept-language': 'zh-CN,zh;q=0.9,en;q=0.8',
        'cache-control': 'max-age=0',
        'upgrade-insecure-requests': '1',
        'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36'
    }
    cookies = {
        'qrsc': '3',
        '__jdu': '867341140',
        'TrackID': '1Uxm9nHkb_89E4CkFaF6AwNrtHXfjPyooTTYl7q-hkfZGmqMYpCYsG4YjbJNHj5YtFlfNe2vvG-6GLzcNMSQMQ0okejzn1melznoPFIjQFkVCKVFXsUNbc57zqUjwfgrQ',
        'pinId': '0hcFXXy5wMgR1TD4F6VI1rV9-x-f3wj7',
        'shshshfpa': '289e9e99-444b-3b5f-3785-1b3878a5eef5-1530973729',
        'shshshfpb': '05613e49c754942fc2348ff7408a049459bb5914f7747fdf05af30acd4',
        'xtest': '7872.cf6b6759',
        'ipLoc-djd': '1-72-2799-0',
        '__jdc': '122270672',
        '__jdv': '122270672|baidu|-|organic|not set|1533174855830',
        'PCSYCityID': '1930',
        'shshshfp': '83c2fe8b2170ca8eec8280b59c3ee7f5',
        '3AB9D23F7A4B3C9B': 'RLMC3AABRKWAT3P4RJJLMHMOJQRKRJBW2Y2TKYYPENEZ3WPEPS77HZ2BKOF6SQUZSHBE2NSVIOJ37JWCFIKGPH67E4',
        'user-key': 'fb667d3d-b83f-4dfb-903b-9664d71a6b6b',
        'cn': '0',
        '__jda': '122270672.867341140.1494042135.1533442694.1533526527.15',
        'rkv': 'V0500',
        'mt_xid': 'V2_52007VwoVVF9dUloaSCldAWIDEVVaCE5SHk4dQAAyAUBODQ9QUwNLHV4MYwAXAlwPVV0vShhcDHsCG05cX0NaH0IbWg5iAyJQbVhiWhxJEVUBYAoTYl1dVF0%3D',
        'shshshsID': '891593eea8946058948f397751cae55d_3_1533526662281',
        '__jdb': '122270672.4.867341140|15.153352652'
    }

    startUrl = sys.argv[1]
    # startUrl = 'https://search.jd.com/Search?keyword=%E5%BA%8A%E4%B8%8A%E5%9B%9B%E4%BB%B6%E5%A5%97&enc=utf-8&suggest=3.def.0.V06&wq=chuang&pvid=9c96e50aa4ca4ea59d0dea75a424b2fd'
    request = requests.get(startUrl, headers=headers, cookies=cookies)
    request.encoding = 'utf-8'
    html = request.text
    soup = BeautifulSoup(html, "html.parser")

    datas = []
    for product in soup.findAll("div", class_="gl-i-wrap"):
        data = {}

        img = product.find("div", class_="p-img").find(name="img").get("source-data-lazy-img")
        img = img if str(img).find("https") != -1 else "https:" + img

        price = product.find("div", class_="p-price").find("i").string

        nameText = product.find("div", class_="p-name p-name-type-2")
        detailUrl = str(nameText.find(name="a").get("href"))
        detailUrl = detailUrl if detailUrl.find("https") != -1 else "https:"+detailUrl
        name = re.compile(r'<[^>]+>', re.S).sub('', str(nameText.find(name="em")))

        commit = product.find("div", class_="p-commit").find(name="a").string

        shopText = product.find("span", class_="J_im_icon")
        shop = shopUrl = None
        if shopText is not None:
            shop = shopText.find(name="a").string
            shopUrl = shopText.find(name="a").get("href")
            shopUrl = "https:"+shopUrl

        data['img'] = img
        data['price'] = price
        data['name'] = name
        data['detail_url'] = detailUrl
        data['commit'] = commit
        data['shop'] = shop
        data['shop_url'] = shopUrl

        # 爬去详情页
        data['shop_detail'] = getDetail(detailUrl)
        datas.append(data)
    return datas


def getDetail(detailUrl):
    headers = {
        'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'accept-encoding': 'gzip, deflate, br',
        'accept-language': 'zh-CN,zh;q=0.9,en;q=0.8',
        'cache-control': 'max-age=0',
        'if-modified-since': 'Wed, 08 Aug 2018 01:51:00 GMT',
        'upgrade-insecure-requests': '1',
        'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36'
    }
    cookies = {
        '__jdu': '867341140',
        'TrackID': '1Uxm9nHkb_89E4CkFaF6AwNrtHXfjPyooTTYl7q-hkfZGmqMYpCYsG4YjbJNHj5YtFlfNe2vvG-6GLzcNMSQMQ0okejzn1melznoPFIjQFkVCKVFXsUNbc57zqUjwfgrQ',
        'pinId': '0hcFXXy5wMgR1TD4F6VI1rV9-x-f3wj7',
        'shshshfpa': '289e9e99-444b-3b5f-3785-1b3878a5eef5-1530973729',
        'shshshfpb': '05613e49c754942fc2348ff7408a049459bb5914f7747fdf05af30acd4',
        '__jdv': '122270672|baidu|-|organic|not set|1533174855830',
        'PCSYCityID': '1930',
        'user-key': 'fb667d3d-b83f-4dfb-903b-9664d71a6b6b',
        'cn': '0',
        'shshshfp': '83c2fe8b2170ca8eec8280b59c3ee7f5',
        '3AB9D23F7A4B3C9B': 'RLMC3AABRKWAT3P4RJJLMHMOJQRKRJBW2Y2TKYYPENEZ3WPEPS77HZ2BKOF6SQUZSHBE2NSVIOJ37JWCFIKGPH67E4',
        '__jdc': '122270672',
        'mt_xid': 'V2_52007VwoVVF9dUloaSClYAmUBFVQOCk5eGUoaQABjARJODVpQDQMeGQgDbgEXV1gKU1IvShhcDHsCG05cUUNbF0IaVQ5nACJQbVhiWR9JG1QMZAcQYl1dVF0%3D',
        '__jda': '122270672.867341140.1494042135.1533652792.1533691694.19',
        'ipLoc-djd': '1-72-2799-0',
        'shshshsID': '2af2ee786d329833bbf6c217377ac195_3_1533693059646',
        '__jdb': '122270672.3.867341140|19.1533691694'
    }

    params = {'jd_pop': '563367bc-5722-4531-a33f-f1e783444c09', 'abt': 0}
    response = requests.get(detailUrl, headers=headers, cookies=cookies, params=params)
    html = response.content.decode('gbk')
    soup = BeautifulSoup(html, "html.parser")

    # 获取商品规格参数
    detailDatas = {}
    standards = soup.find_all("div", class_="item ")
    detailDatas['standard'] = []
    if standards is not None:
        for standard in standards:
            img = 'https'+standard.find(name="img").get("src") if standard.find(name="img") is not None else None
            name = standard.find("i").string if standard.find("i") is not None else None
            detailDatas['standard'].append({
                'image': img,
                'name': name
            })

    # 获取商品 split('.')
    detailDatas['shop_introduce'] = {}
    shopIntroduces = soup.find("ul", class_="parameter2 p-parameter-list")
    if shopIntroduces is not None:
        shopIntroduces = shopIntroduces.find_all("li")
        for shopIntroduce in shopIntroduces:
            if shopIntroduce.string is not None:
                item = shopIntroduce.string.split('：')
                detailDatas['shop_introduce'][item[0]] = item[1]

    # 获取评论
    sowingMaps = soup.find("div", class_="spec-list").find_all("li")
    detailDatas['sowing_map'] = []
    for sowingMap in sowingMaps:
        sowingImg = sowingMap.find("img").get("src")
        detailDatas['sowing_map'].append('https:'+sowingImg)

    return detailDatas


if __name__ == '__main__':
    reload(sys)
    sys.setdefaultencoding('utf-8')
    datas = requestJD()
    string = json.dumps(datas)
    print(string)