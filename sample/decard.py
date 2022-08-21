import requests as rq
from bs4 import BeautifulSoup
import jieba
import nltk
import datetime
import requests.packages.urllib3
import re
requests.packages.urllib3.disable_warnings()
# payload = {
#     'from' : '/bbs/NBA/index'+tmpHref+'.html',
#     'yes' : 'yes'
# }
rs = rq.session()
url = "https://www.dcard.tw/f/trending" # PTT NBA 板
# response = rs.post('https://www.ptt.cc/ask/over18', verify=False, data=payload)
response = rs.get(url, verify=False) # 用 requests 的 get 方法把網頁抓下來
html_doc = response.text # text 屬性就是 html 檔案
soup = BeautifulSoup(html_doc, "html.parser") # 指定 lxml 作為解析器
# print(soup.select('.tgn9uw-2.dHOiIN'))
for entry in soup.select('.tgn9uw-2.dHOiIN'):
	print(entry.text)

