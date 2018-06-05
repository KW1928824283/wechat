#!/usr/bin/env python
# coding=utf-8

import requests
from bs4 import BeautifulSoup
import re
import lxml
import sys
import json

def postHtmlinfo(url,header,data,cookie):
    try:
        r = requests.post(url,headers=header,data=data,cookies=cookie,timeout =30,allow_redirects=False)
        r.raise_for_status()
        r.encoding = r.apparent_encoding
        co2=r.cookies
        str=r.cookies["iPlanetDirectoryPro"]
        lo=r.headers['Location']
        return co2,lo,str
    except:
        print("")

def getindex(url,header,cookie1):
    try:
        r=requests.get(url,headers=header,cookies=cookie1,timeout=30)
        r.raise_for_status()
        r.encoding=r.apparent_encoding
        reheader=r.headers
        cookie=r.headers['Set-Cookie']
        cookie=cookie.split('/')
        cookie=cookie[0]
        cookie=cookie.replace('; Path=','')
        cookie=cookie.replace(',',';')
        return r.text ,cookie
    except:
        print("")

def parsepage(html):
    soup = BeautifulSoup(html, 'lxml')
    soup=soup.find(id='tableDiv')
    print(soup)

def getcard(header):
    list =[]
    url = "http://portal.chd.edu.cn/pnull.portal?rar=0.021862873665522864&.pmn=view&.ia=false&action=showItem&.pen=pe950&itemId=601&childId=621&page="+sys.argv[3]
    r=requests.get(url,headers=header,timeout=30)
    r.raise_for_status()
    r.encoding=r.apparent_encoding
    html=r.text
    html=html.replace(' ','')
    html=html.replace('\r\n','')
    html=html.replace('...','')
    html=html.replace('微机室','')
    soup = BeautifulSoup(html, 'html.parser')
    tbody=soup.tbody
    tr=tbody.find_all('tr')
    for tr1 in tr:
        td=tr1.find_all('td')
        list.append({
                    'xuhao':td[0].string,
                    'kahao':td[1].string,
                    'date':td[2].string,
                    'time':td[3].string,
                    'didian':td[4].string,
                    'money':td[5].string,
                    'yu_e':td[6].string
                    })
    jsonStr=json.dumps(list)
    return jsonStr


def getcookies(url,header,cookie):
    try:
        r = requests.get(url,headers=header,cookies=cookie,timeout=30,allow_redirects=False)
        r.raise_for_status()
        r.encoding = r.apparent_encoding
        co3=r.cookies
        cookie=r.headers['Set-Cookie']
        cookie=cookie.replace('; path=/; Httponly','')
        loc=r.headers['Location']
        return co3,loc,cookie
    except:
        print("")

def getLT(text):
    try:
        soup = BeautifulSoup(text, 'html.parser')
        list=[]
        a = soup.find_all('input')[4]
        b=a.attrs['value']
        list.append(b)
        c = soup.find_all('input')[6]
        d =c.attrs['value']
        list.append(d)
        return list
    except:
        print("")

def getHTMLCookie(url,headers1):
    try:
        r=requests.get(url,headers=headers1,timeout =30)
        r.raise_for_status()
        r.encoding = r.apparent_encoding
        cookie=r.headers['Set-Cookie']
        cookie=cookie.replace(',',';')
        cookie=cookie.replace('; Path=/',';')
        coo =r.cookies
        lt=getLT(r.text)
        return lt,cookie,coo
    except:
        print("")

def main():
    url = "http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fportal.chd.edu.cn%2F"
    urlindex="http://portal.chd.edu.cn/"
    username = sys.argv[1]
    password = sys.argv[2]
    headers1 = {'Host': 'ids.chd.edu.cn',
                'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
                'Accept-Encoding': 'gzip, deflate',
                'Connection': 'keep-alive',
                'Upgrade-Insecure-Requests': '1',
                'Cache-Control': 'max-age=0'
               }
    result = getHTMLCookie(url,headers1)#result[2] 第一个cookie
    header = {'Host': 'ids.chd.edu.cn',
             'User-Agent':'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0',
             'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
             'Accept-Language':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
             'Accept-Encoding': 'gzip, deflate',
             'Content-Type':'application/x-www-form-urlencoded',
             'Content-Length':'178',
             'Referer':'http://ids.chd.edu.cn/authserver/login?service=http://portal.chd.edu.cn/',
             'Connection':'keep-alive',
             'Upgrade-Insecure-Requests':'1'
           }
    data = {
           'username':username,
            'password':password,
            'btn':'',
            'dllt':'userNamePasswordLogin',
            'lt':result[0][0],
            'execution':result[0][1],
            '_eventId':'submit',
            'rmShown':'1'
           }
    post = postHtmlinfo(url,header,data,result[2])#post[0]第二个cookie
    header2 = {
           'Host': 'portal.chd.edu.cn',
           'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0',
           'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
           'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
           'Accept-Encoding': 'gzip, deflate',
           'Referer': 'http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fportal.chd.edu.cn%2F',
           'Cookie':'iPlanetDirectoryPro='+post[2],
           'Connection': 'keep-alive',
           'Upgrade-Insecure-Requests': '1'
              }
    ina = getcookies(post[1],header2,post[0])#ina[0]第三个cookie  
    strco='iPlanetDirectoryPro='+post[2]+'; '+ina[2]
    header3 = {
           'Host': 'portal.chd.edu.cn',
           'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0',
           'Accept': 'text/html, */*; q=0.01',
           'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
           'Accept-Encoding': 'gzip, deflate',
           'X-Requested-With': 'XMLHttpRequest',
           'Cookie':strco, 
           'Referer': 'http://portal.chd.edu.cn/',
           'Connection': 'keep-alive'
    }
    index = getindex(ina[1],header3,result[2])
   # parsepage(index[0])
    strco2=strco+'; '+index[1]
    urlcard = "http://portal.chd.edu.cn/pnull.portal?rar=0.021862873665522864&.pmn=view&.ia=false&action=showItem&.pen=pe950&itemId=601&childId=621&page=1"
    header4 = {
               'Host': 'portal.chd.edu.cn',
               'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0',
               'Accept': 'text/html, */*; q=0.01',
               'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
               'Accept-Encoding': 'gzip, deflate',
               'X-Requested-With': 'XMLHttpRequest',
               'Referer': 'http://portal.chd.edu.cn/index.portal?.pn=p56_p232',
               'Cookie':strco2,
               'Connection': 'keep-alive'
              }
    json=getcard(header4)
    print (json)
main()
