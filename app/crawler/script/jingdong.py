# -*- coding: utf-8 -*-
import requests
from bs4 import BeautifulSoup
import re
import sys
import json


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
        datas.append(data)
    return datas


if __name__ == '__main__':
    reload(sys)
    sys.setdefaultencoding('utf-8')
    datas = requestJD()
    string = json.dumps(datas)
    print(string)