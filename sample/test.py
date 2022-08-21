# coding=utf-8
import requests as rq
from bs4 import BeautifulSoup
import jieba
import nltk
import datetime
import requests.packages.urllib3
import re



ws = WS("./data")
pos = POS("./data")
ner = NER("./data")
requests.packages.urllib3.disable_warnings()

tmpHref=''
count = 0
sentence_list = []
date_time_str = '2020-10-20'

weightDic = {}

print(limitDate.date())

while True:
	payload = {
	    'from' : '/bbs/Gossiping/index'+tmpHref+'.html',
	    'yes' : 'yes'
	}
	rs = rq.session()
	url = "https://www.ptt.cc/bbs/Gossiping/index"+tmpHref+".html" # PTT NBA 板
	response = rs.post('https://www.ptt.cc/ask/over18', verify=False, data=payload)
	response = rs.get(url, verify=False) # 用 requests 的 get 方法把網頁抓下來
	html_doc = response.text # text 屬性就是 html 檔案
	soup = BeautifulSoup(html_doc, "html.parser") # 指定 lxml 作為解析器
	print(soup.select('.btn.wide')[1]['href'])
	tmpHref = soup.select('.btn.wide')[1]['href']
	tmpHref = tmpHref.split('/')
	# tmpHref.split('/')
	tmpHref = tmpHref[3].split('.')
	tmpHref = tmpHref[0].split('x')
	tmpHref = tmpHref[1]
	# rs.close()
	print(tmpHref)
	for entry in soup.select('.r-ent'):
		# print(entry.select('.date')[0].text,entry.select('.title')[0].text,entry.select('.author')[0].text)
		# print(entry.select('.title a')[0]['href'])
		
		tmpurl = 'https://www.ptt.cc/'+entry.select('.title a')[0]['href']
		response = rs.get(tmpurl, verify=False)
		artical = response.text
		articalSoup = BeautifulSoup(artical, "html.parser")
		# print(articalSoup.select('.article-metaline .article-meta-value')[2].text)
		tmpDate = (articalSoup.select('.article-metaline .article-meta-value')[2].text)
		# tmpDate = tmpDate.split(' ')
		tmpDate = re.split(r"  | ", tmpDate)
		tmpDate = tmpDate[4]+' '+tmpDate[1]+' '+tmpDate[2]
		tmpDate = datetime.datetime.strptime(tmpDate, '%Y %b %d')
		if(tmpDate>=limitDate):
			# print(string[3:5])
			tmpStr = entry.select('.title')[0].text
			len(tmpStr)
			tmpStr = tmpStr[1:len(tmpStr)-1]

			sentence_list.append(tmpStr)
		else:
			count+=1
		# print(tmpDate)
		# print(date_time_obj.date())

	# count = count+1
	print(count)
	if count > 4:
		break
print(sentence_list)
word_sentence_list = ws(
    sentence_list,
    # sentence_segmentation = True, # To consider delimiters
    # segment_delimiter_set = {",", "。", ":", "?", "!", ";"}), # This is the defualt set of delimiters
    # recommend_dictionary = dictionary1, # words in this dictionary are encouraged
    # coerce_dictionary = dictionary2, # words in this dictionary are forced
)



