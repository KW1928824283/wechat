#!/usr/bin/env python
# coding=utf-8

import requests
from bs4 import BeautifulSoup
import re
import lxml
import json
import sys

def postHtmlinfo(url,header,data):
    try:
        r = requests.post(url,headers=header,data=data,timeout =30,allow_redirects=False)
        r.raise_for_status()
        r.encoding = r.apparent_encoding
        str=r.cookies["iPlanetDirectoryPro"]
        lo=r.headers['Location']
        return lo,str
    except:
        print("")

def getscore(url,header):
    try:
        r=requests.get(url,headers=header,timeout=30)
        r.raise_for_status()
        r.encoding=r.apparent_encoding
        return r.text
    except:
        print("成绩查询失败")

def parsescore(url,header):
    list=[]
    html=getscore(url,header)
    html=html.replace('\n','')
    html=html.replace('\t','')
    soup=BeautifulSoup(html,'html.parser')
    tbody=soup.tbody
    thead=soup.thead
    th=thead.find_all('th')
    i=0
    for zuizhong in th:
        i=i+1
        if(zuizhong.string=="最终"):
            break
    j=0
    for mingcheng in th:
        j=j+1
        if(mingcheng.string=="课程名称"):
            break  
    k=0
    for kaoshi in th:
        k=k+1
        if(kaoshi.string=="期末成绩"):
            break
    l=0
    for xuefen in th:
        l=l+1
        if(xuefen.string=="学分"):
            break
    m=0
    for pingshi in th:
        m=m+1
        if(pingshi.string=="平时成绩"):
            break
    n=0
    for jidian in th:
        n=n+1
        if(jidian.string=="绩点"):
            break

    tr=tbody.find_all('tr')
    for tr1 in tr:
        td=tr1.find_all('td')
        list.append({'class':td[j-1].string,
                     'fen':td[l-1].string,
                     'qimo':td[k-1].string,
                     'pingshi':td[m-1].string,
                     'zuizhong':td[i-1].string,
                     'jidian':td[n-1].string
                    })
    jsonStr=json.dumps(list)
    return jsonStr

def getcookies(url,header):
    try:
        r = requests.get(url,headers=header,timeout=30,allow_redirects=False)
        r.raise_for_status()
        r.encoding = r.apparent_encoding
        cookie=r.headers['Set-Cookie']
        cookie=cookie.replace('; Path=/eams/; HttpOnly','')
        loc=r.headers['Location']
        return loc,cookie
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
        cookie=cookie.replace('; Path=/','')
        lt=getLT(r.text)
        return lt,cookie
    except:
        print("")

def main():
    url = "http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fbkjw.chd.edu.cn%2Feams%2Findex.action"
    username = sys.argv[1]
    password = sys.argv[2]
    headers1 = {
               'Host': 'ids.chd.edu.cn',
               'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
               'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
               'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
               'Accept-Encoding': 'gzip, deflate',
               'Connection': 'keep-alive',
               'Upgrade-Insecure-Requests': '1',
    }
    result = getHTMLCookie(url,headers1)#第一个cookie
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
    header={
            'Host': 'ids.chd.edu.cn',
            'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
            'Accept-Encoding': 'gzip, deflate',
            'Referer': 'http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fbkjw.chd.edu.cn%2Feams%2Findex.action',
            'Content-Type': 'application/x-www-form-urlencoded',
            'Content-Length': '177',
            'Cookie':result[1],
            'Connection': 'keep-alive',
            'Upgrade-Insecure-Requests': '1'
    }
    post = postHtmlinfo(url,header,data)#post[0]第二个Cookie
   # print(post[1])
   # print(post[0])
    header2 = {
           'Host': 'bkjw.chd.edu.cn',
           'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
           'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
           'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
           'Accept-Encoding': 'gzip, deflate',
           'Referer': 'http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fbkjw.chd.edu.cn%2Feams%2Findex.action',
           'Cookie':'iPlanetDirectoryPro='+post[1],
           'Connection': 'keep-alive',
           'Upgrade-Insecure-Requests': '1'
    }
    ina = getcookies(post[0],header2)#ina[0]第三个Cookie
   # print(ina[0])
    strco=ina[1]+'; '+'iPlanetDirectoryPro='+post[1]
  #  print(strco)
    header3 = {
           'Host': 'bkjw.chd.edu.cn',
           'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
           'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
           'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
           'Accept-Encoding': 'gzip, deflate',
           'Cookie':strco, 
           'Connection': 'keep-alive',
           'Referer': ina[0],
           'Upgrade-Insecure-Requests': '1',
           'Cache-Control': 'max-age=0'
    }
  #  print(ina[0],header3,)
  #  urlhome='http://bkjw.chd.edu.cn/eams/home.action'
  #  urlscore='http://bkjw.chd.edu.cn/eams/teach/grade/course/person.action'
    urlchengji='http://bkjw.chd.edu.cn/eams/teach/grade/course/person!search.action?semesterId=76&projectType=&_=1512890020326'
   # index=getindex(urlscore,header3)
    index = parsescore(urlchengji,header3)
    print(index)
   # print(index[0])
main()
