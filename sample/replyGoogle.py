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

getOld = rq.get('http://localhost/reply/title')
getOld=ast.literal_eval( getOld.text)

addDict = {}



for item in getOld:
	# print(item['name'])

	driver = webdriver.Firefox()
	driver.get('https://news.google.com/search?q='+item['name']+'%20when%3A1h&hl=zh-TW&gl=TW&ceid=TW%3Azh-Hant')
	# sleep(2)
	addDict[item['id']] = []

	source = driver.page_source
	soup = BeautifulSoup(source, "html.parser")

	article = soup.select('article.MQsxIb')
	for stri in article:
		try:
			tmpStr = stri.select('h3')[0].text
			if(tmpStr.index(item['name'])>0):
				addDict[item['id']].append(tmpStr)
		except:
			# print("An exception occurred")
			pass
			


	# arr.append(str1)
	# print(str1)

	driver.delete_all_cookies()
	driver.quit()  # remove this line to leave the browser open


print(addDict)
test = json.dumps(addDict,ensure_ascii=False)


headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/reply/appear',  data=test.encode(), headers = headers)
print ((go.content).decode('utf-8'))
