import requests as rq
from bs4 import BeautifulSoup
# import jieba
import nltk
import datetime
import requests.packages.urllib3
import re
import math
from ckiptagger import data_utils, construct_dictionary, WS, POS, NER
import json 
import codecs
from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from time import sleep
import ast
import requests.packages.urllib3
requests.packages.urllib3.disable_warnings()



getAppear = rq.get('http://localhost/reply/appear/count')
getAppear=ast.literal_eval( getAppear.text)
print(getAppear)


getHistory = rq.get('http://localhost/chat/chatroom/0000')
getHistory=ast.literal_eval( getHistory.text)
print(getHistory)



for appear in getAppear:
	addDict = {}
	# print(appear['count'])
	href = 'https://hylib.typl.gov.tw/booksearch.do?searchtype=simplesearch&search_field=TI&search_input='+appear['name']+'&searchsymbol=hyLibCore.webpac.search.common_symbol&execodehidden=true&execode=&ebook=#searchtype=simplesearch&search_field=TI&search_input='+appear['name']+'&searchsymbol=hyLibCore.webpac.search.common_symbol&execodehidden=true&execode=&ebook=&resid=188828147&nowpage=1'
	# rs = rq.session()
	# response = rs.get(href, verify=False) # 用 requests 的 get 方法把網頁抓下來
	# html_doc = response.text # text 屬性就是 html 檔案
	# soup = BeautifulSoup(html_doc, "html.parser") # 指定 lxml 作為解析器
	# print(soup.select('.bookname'))
	

	driver = webdriver.Firefox()
	driver.get(href)
	sleep(2)
	source = driver.page_source
	soup = BeautifulSoup(source, "html.parser")
	Books = soup.select('.booklist')
	bookInfoMsg = ''
	bookArray = []
	# print(Book)
	for item in Books:
		book = item.select('.bookname')[0]
		# print(book.text)
		bookInfoMsg=book.text
		bookDetail = item.select('.bookDetail li')
		for detail in bookDetail:
			# print(detail.text)
			bookInfoMsg = bookInfoMsg+'</br>'+detail.text
		results = item.select('tr[valign="top"]')[0]

		# results = item.find("tr", {"valign" : "top"})
		tdresults = results.select("td")[0]
		tdresults = tdresults.text.replace("\n","")
		tdresults = tdresults.split('更多館藏')[0]
		bookInfoMsg = bookInfoMsg+'</br>'+tdresults
		# print('new')
		# print(tdresults)

		bookArray.append(bookInfoMsg)	
	# print(bookArray)
	# print(appear['count'])


	# continue
	if(appear['count']>5):
		for chatID in getHistory:
			message = appear['name'] +'為焦點新聞!若有興趣以下為相關書籍'
			# href = 'https://hylib.typl.gov.tw/bookSearchList.do?searchtype=simplesearch&execodeHidden=true&execode=&authoriz=1&search_field=TI&search_input='+appear['name']+'&searchsymbol=hyLibCore.webpac.search.common_symbol&keepsitelimit=#searchtype=simplesearch&execodeHidden=true&execode=&authoriz=1&search_field=TI&search_input='+appear['name']+'&searchsymbol=hyLibCore.webpac.search.common_symbol&keepsitelimit=&resid=188826479&nowpage=1'
			# href='https://booksearch.do?search_input='+appear['name']+'&search_field=TI'
			print(message)
			addDict['Msg'] = href
			addDict['word'] = message
			addDict['chatID'] = chatID['chatID']
			addDict['UID'] = '0000'
			addDict['_METHOD'] = 'PATCH'
			test = json.dumps(addDict,ensure_ascii=False)
			headers = {'Content-Type':'application/json; charest=utf-8'}
			go = rq.post('http://localhost/chat/message/ai',  data=test.encode(), headers = headers)
			print ((go.content).decode('utf-8'))


			for tmpMsg in bookArray:
				addDict['Msg'] = tmpMsg
				addDict['chatID'] = chatID['chatID']
				addDict['UID'] = '0000'
				addDict['_METHOD'] = 'PATCH'
				test = json.dumps(addDict,ensure_ascii=False)
				headers = {'Content-Type':'application/json; charest=utf-8'}
				go = rq.post('http://localhost/chat/message',  data=test.encode(), headers = headers)
				print ((go.content).decode('utf-8'))


			finishDict = {}
			finishDict['_METHOD'] = 'PATCH'
			finishDict['titleID'] = appear['titleID']

			# test = json.dumps(finishDict,ensure_ascii=False)
			# headers = {'Content-Type':'application/json; charest=utf-8'}
			# go = rq.post('http://localhost/reply/appear/count',  data=test.encode(), headers = headers)
			# print ((go.content).decode('utf-8'))
	driver.delete_all_cookies()
	driver.quit()





