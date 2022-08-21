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
from zhon.hanzi import punctuation
# data_utils.download_data_gdown("./")


def sortedDictValues2(adict): 
	keys = adict.keys() 
	keys.sort()
def print_word_pos_sentence(word_sentence, pos_sentence , uid):
    assert len(word_sentence) == len(pos_sentence)
    for word, pos in zip(word_sentence, pos_sentence):
        # print(f"{word}({pos})", end="\u3000")
        # print(pos)
        a = ['N', 'V']
        if any(x in pos for x in a) and len(pos)<5:
        	# print(pos)

	        if f"{word}" in weightDic[uid]:
	        	weightDic[uid][f"{word}"] += 1
	        	# print(weightDic[f"{word}({pos})"])

	        else:
	        	weightDic[uid][f"{word}"] = 1

    # print()
    return

getlasttime = rq.get('http://localhost/reply/lasttime')
getlasttime=ast.literal_eval( getlasttime.text)
print(getlasttime[0]['time'])

getHistory = rq.get('http://localhost/chat/aicontent/'+getlasttime[0]['time'])
fullData = getHistory.json()
count = 0

weightDic = {}


ws = WS("./data")
pos = POS("./data")
ner = NER("./data")
requests.packages.urllib3.disable_warnings()

for data in fullData:
	sentence_list = []
	# print(data['UID'])


	if((data['UID']) not in weightDic):
		weightDic[data['UID']] = {}

	if "<a href" in data['content']:
		continue
	# cont=ast.literal_eval(data['content'].strip())
	cont =  (re.sub("[{}] ".format(punctuation), "",data['content']))
	cont = cont.replace('<br />','')
	sentence_list.append(cont)
	word_sentence_list = ws(
    	sentence_list,
	)

	pos_sentence_list = pos(word_sentence_list)

	entity_sentence_list = ner(word_sentence_list, pos_sentence_list)
	for i, sentence in enumerate(sentence_list):
	    # print()
	    # print(f"'{sentence}'")
		print_word_pos_sentence(word_sentence_list[i],  pos_sentence_list[i] , (data['UID']))
		# for entity in sorted(entity_sentence_list[i]):
	    	# print('',entity)
	count = count+1
	if(count == 100):

		test = json.dumps(weightDic,ensure_ascii=False)
		test = test.replace("\'", "\"");
		headers = {'Content-Type':'application/json; charest=utf-8'}
		go = rq.post('http://localhost/chatData',  data=test.encode(), headers = headers)

		count=0	
		weightDic = {}
			

# print(weightDic)
del ws
del pos
del ner
# print((weightDic))
# print(sorted(weightDic.items(), key=lambda d: d[0]) )
# print(sorted(weightDic.key()))
# print(dict(reversed(sorted(weightDic.items(), key=lambda item: item[1],))))

#print(type(getHistory))
#getHistory=ast.literal_eval( getHistory.text)
#print(getHistory)


test = json.dumps(weightDic,ensure_ascii=False)

test = test.replace("\'", "\"");
print(test)

# f = open('text.text','w')
# f.write(test)
# f.close()

headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/chatData',  data=test.encode(), headers = headers)
print(go.content)




