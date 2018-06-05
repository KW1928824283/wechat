#!/usr/bin/env python
# coding=utf-8

import requests
from bs4 import BeautifulSoup
import re
import lxml
import json
import sys
from random import choice

class DengLu_jiaowu:
	def __init__(self,username,password):
		self.username=username
		self.password=password

	def postHtmlinfo(self,url,header,data):
		try:
			r = requests.post(url,headers=header,data=data,timeout =30,allow_redirects=False)
			r.raise_for_status()
			r.encoding = r.apparent_encoding
			str=r.cookies["iPlanetDirectoryPro"]
			lo=r.headers['Location']
			return lo,str
		except:
			print("")

	def getscore(self,url,header):
		try:
			r=requests.get(url,headers=header,timeout=30)
			r.raise_for_status()
			r.encoding=r.apparent_encoding
			cookie=r.headers['Set-Cookie']
			s=re.findall(';.*',cookie)
			cookie=cookie.replace(s[0],'')
			return r.text,cookie
		except:
			print("成绩查询失败")

	def parsescore(self,url,header):
		list=[]
		html=self.getscore(url,header)
		cookie=html[1]
		html=html[0].replace('\n','')
		html=html.replace('\t','')
		soup=BeautifulSoup(html,'html.parser')
		table=soup.find(attrs={'class':'gridtable'}) 
		tbody=table.tbody
		tr=tbody.find_all('tr')
		for tr1 in tr:
			td=tr1.find_all('td')
			if(td[5].a==None):
				href=None
			else:
				href=td[5].a["href"]
			list.append((td[1].string,
					td[3].string,
					href
					))
		return list,cookie

	def getcookies(self,url,header):
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

	def getLT(self,text):
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

	def getHTMLCookie(self,url,headers1):
		try:
			r=requests.get(url,headers=headers1,timeout =30)
			r.raise_for_status()
			r.encoding = r.apparent_encoding
			cookie=r.headers['Set-Cookie']
			cookie=cookie.replace(',',';')
			cookie=cookie.replace('; Path=/','')
			lt=self.getLT(r.text)
			return lt,cookie
		except:
			print("")

	def pingjiao(self,list,header,cookie):
		list_status=[]
		for kecheng in list:
			href=kecheng[2]
			name=kecheng[0]
			teacher=kecheng[1]
			result=self.tijiao(href,header,cookie)
			list_status.append({'kecheng':name,
								'teacher':teacher,
								'status':result
								})
		jsonStr=json.dumps(list_status)
		return jsonStr

	def tijiao(self,url,header,cookie):
		list_manyi=['没有意见',
		'希望能将知识系统的教授我们，而不是完全照本宣科',
		'老师挺好的，对我们挺不错的',
		'希望老师教授我们课本知识的同时，也可以给我们讲讲小故事和一些人生道理',
		'超级喜欢我们老师嘞，最棒，没有之一',
		'能不能考试对我们好一些，哈哈哈',
		'希望老师讲课时候生动形象一些，而不是念PPT',
		'希望这个评教不是走形式，而是对老师有作用',
		'没啥意见，老师讲得不赖',
		'希望老师不要为难我们考试',
		'希望大学老师也能像高中老师那样认识我们，知道我们的名字',
		'我们老师还是挺好滴',
		'优秀',
		'好',
		'挺好的',
		'希望所有的老师都可以幽默风趣一些，这样课堂不无聊',
		'没啥意见',
		'考试能对我们好一些就更好了',
		'没毛病',
		'老师对我们还是不错滴']
		cho=choice(list_manyi)
		if(url==None):
			result='你已评教'
		else:
			url='http://bkjw.chd.edu.cn'+url
			s=re.findall(r'\d+',url)
			data={
			'teacher.id':s[1],
			'evaluationLesson.id':s[0],
			'result1_0.questionName':'思路清晰，表达清楚，板书规范，知识渊博，讲解逻辑性强',
			'result1_0.content':'A（满意）',
			'result1_0.score':'0.5',
			'result1_1.questionName':'具有良好的职业道德，教学责任心强，对待学生公正、客观',
			'result1_1.content':'A（满意）',
			'result1_1.score':'0.5',
			'result1_2.questionName':'耐心辅导答疑，认真批改作业',
			'result1_2.content':'A（满意）',
			'result1_2.score':'0.5',
			'result1_3.questionName':'平易近人，能虚心听取学生的意见和建议并及时改进工作',
			'result1_3.content':'A（满意）',
			'result1_3.score':'0.5',
			'result1_4.questionName':'能根据课程性质，采用有效的教学方法和手段',
			'result1_4.content':'A（满意）',
			'result1_4.score':'0.5',
			'result1_5.questionName':'上课认真，注意与学生的沟通和交流，讲课富有感染力',
			'result1_5.content':'A（满意）',
			'result1_5.score':'0.5',
			'result1_6.questionName':'内容充实，概念准确，重点讲解突出，难点分析透彻',
			'result1_6.content':'A（满意）',
			'result1_6.score':'0.5',
			'result1_7.questionName':'注意理论联系实际，案例新颖且具代表性，能联系学科最新动态',
			'result1_7.content':'A（满意）',
			'result1_7.score':'0.5',
			'result1_8.questionName':'学生对本课程知识掌握得较好，分析问题和解决问题的能力有所提高',
			'result1_8.content':'A（满意）',
			'result1_8.score':'0.5',
			'result1_9.questionName':'教师的授课能有效激发学生的学习兴趣',
			'result1_9.content':'A（满意）',
			'result1_9.score':'0.5',
			'result2_0.questionName':'上课是否按照大纲进行教学？',
			'result2_0.content':'是',
			'result2_1.questionName':'您对该授课教师的意见和建议',
			'result2_1.content':cho,
			'result1Num':'10',
			'result2Num':'2'
			}
			#print(data)
			try:
				r = requests.get(url,headers=header,timeout=30)
				r.raise_for_status()
				r.encoding = r.apparent_encoding
				header5={
				'Host': 'bkjw.chd.edu.cn',
				'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
				'Accept': '*/*',
				'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
				'Accept-Encoding': 'gzip, deflate',
				'Referer': url,
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				'X-Requested-With': 'XMLHttpRequest',
				'Content-Length': '3520',
				'Cookie': cookie,
				'Connection': 'keep-alive'
				}
				posturl = 'http://bkjw.chd.edu.cn/eams/quality/stdEvaluate!finishAnswer.action'
				r = requests.post(posturl,headers=header5,data=data,timeout=30)
				r.raise_for_status()
				r.encoding = r.apparent_encoding
				result="评教成功"
				return result
			except:
				return("评教失败")
		return result


	def main(self):
		url = "http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fbkjw.chd.edu.cn%2Feams%2Findex.action"
		username = self.username
		password = self.password
		headers1 = {
               'Host': 'ids.chd.edu.cn',
               'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
               'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
               'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
               'Accept-Encoding': 'gzip, deflate',
               'Connection': 'keep-alive',
               'Upgrade-Insecure-Requests': '1',
		}
		result = self.getHTMLCookie(url,headers1)#第一个cookie
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
		post = self.postHtmlinfo(url,header,data)#post[0]第二个Cookie
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
		ina = self.getcookies(post[0],header2)#ina[0]第三个Cookie
		strco=ina[1]+'; '+'iPlanetDirectoryPro='+post[1]
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
		url_pingjiao_list='http://bkjw.chd.edu.cn/eams/quality/stdEvaluate.action?_=1514939497264'
		index = self.parsescore(url_pingjiao_list,header3)
		strco2=ina[1]+'; '+index[1]+'; '+'iPlanetDirectoryPro='+post[1]
		header4 = {
				'Host': 'bkjw.chd.edu.cn',
				'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0',
				'Accept': 'text/html, */*; q=0.01',
				'Accept-Language': 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
				'Accept-Encoding': 'gzip, deflate',
				'Referer': 'http://bkjw.chd.edu.cn/eams/quality/stdEvaluate.action',
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				'X-Requested-With': 'XMLHttpRequest',
				'Content-Length': '18',
				'Cookie':strco2,
				'Connection': 'keep-alive',
		}
		str=self.pingjiao(index[0],header4,strco2)
		print(str)

a=DengLu_jiaowu(sys.argv[1],sys.argv[2])
a.main()
