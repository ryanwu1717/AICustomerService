# coding=utf-8

import requests as rq
from bs4 import BeautifulSoup
# import jieba
import nltk
# import datetime
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


def sortedDictValues2(adict): 
  keys = adict.keys() 
  keys.sort() 
  return [dict[key] for key in keys] 
def print_word_pos_sentence(word_sentence, pos_sentence):
    assert len(word_sentence) == len(pos_sentence)
    assoiateArr=[]
    for word, pos in zip(word_sentence, pos_sentence):
        # print(f"{word}({pos})", end="\u3000")
        # print(pos)
        a = ['N', 'V']
        if any(x in pos for x in a) and len(pos)<5:
          # print(f"{word}")

          # if f"{word}" in weightDic:
          #   weightDic[f"{word}"] += 1
          #   # print(weightDic[f"{word}({pos})"])

          # else:
          #   weightDic[f"{word}"] = 1
          assoiateArr.append(f"{word}")
    # print()
    return assoiateArr
# data_utils.download_data_gdown("./")
def insert(major,assoiate):
  print(major,assoiate)
  if (major not in allDict):
    allDict[major] = {}
    # for item in resultArr:
    allDict[major][assoiate] = 1
  else:
    if(assoiate in  allDict[major]):
      allDict[major][assoiate] +=1
    else:
      allDict[major][assoiate] = 1 

ws = WS("./data")
pos = POS("./data")
ner = NER("./data")
requests.packages.urllib3.disable_warnings()

allDict = {}
arr = []

getOld = rq.get('http://localhost/line/lastUpdated')
getOld=ast.literal_eval( getOld.text)
print()
# print(getOld['Test1'])

rs = rq.session()
url = "https://tw.yahoo.com/" 
# response = rs.post('https://www.ptt.cc/ask/over18', verify=False, data=payload)
response = rs.get(url, verify=False) # 用 requests 的 get 方法把網頁抓下來
html_doc = response.text # text 屬性就是 html 檔案
soup = BeautifulSoup(html_doc, "html.parser")
selectSoup = soup.select('div.header-search-keywords a')
# selectSoup.find_all('a').text
# teee = 'https://tw.search.yahoo.com/search;_ylt=AwrtFqt41wdgoSAA1RZq1gt.;_ylc=X1MDMjExNDcwNTAwMgRfcgMyBGZyAwRmcjIDc2EtZ3AEZ3ByaWQDeHZ1WERBWjRTNC45NUVNc2ZieDJoQQRuX3JzbHQDMARuX3N1Z2cDMTAEb3JpZ2luA3R3LnNlYXJjaC55YWhvby5jb20EcG9zAzEEcHFzdHIDBHBxc3RybAMwBHFzdHJsAzYEcXVlcnkDJUU0JUJBJUJBJUU0JUJBJThCJUU4JTk2JUFBJUU4JUIzJTg3JUU4JUJCJTlGJUU5JUFCJTk0BHRfc3RtcAMxNjExMTI2NjY3BHVzZV9jYXNlAw--?p=%E4%BA%BA%E4%BA%8B%E8%96%AA%E8%B3%87%E8%BB%9F%E9%AB%94&fr=&iscqry=&fr2=sa-gp'

for item in selectSoup:

  try:

    if(item.text  in getOld.keys()):
      continue
  except:
    pass

  sentence_list = []
  tmpStr = item.text
  arr.append(tmpStr)
  tmpHref = item['href']

  response = rs.get(tmpHref, verify=False) # 用 requests 的 get 方法把網頁抓下來
  html_doc = response.text # text 屬性就是 html 檔案
  soup = BeautifulSoup(html_doc, "html.parser")
  selectSoup = soup.select('ul.d-i a')

  for item in selectSoup:
    if(item.text != '更多...'):
      print(item.text)
      sentence_list.append(item.text)
  word_sentence_list = ws(
  sentence_list,
    # sentence_segmentation = True, # To consider delimiters
    # segment_delimiter_set = {",", "。", ":", "?", "!", ";"}), # This is the defualt set of delimiters
    # recommend_dictionary = dictionary1, # words in this dictionary are encouraged
    # coerce_dictionary = dictionary2, # words in this dictionary are forced
  )

  pos_sentence_list = pos(word_sentence_list)

  entity_sentence_list = ner(word_sentence_list, pos_sentence_list)
  for i, sentence in enumerate(sentence_list):
    # print(arr[i])
    # print(f"'{sentence}'")

    resultArr = print_word_pos_sentence(word_sentence_list[i],  pos_sentence_list[i])
    # for entity in sorted(entity_sentence_list[i]):
    #     print('',entity)

    print(resultArr)
    for result in resultArr:
      print('result',result)
      try:
        if (result not in  tmpStr):
          print('insert',result)
          insert(tmpStr,result)
          #  print(item)
      except Exception as e:
        
        pass
 
print(allDict)  
# print(arr)

del ws
del pos
del ner
test = json.dumps(allDict,ensure_ascii=False)

# test = test.replace("\'", "\"");
headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/ptt/news',  data=test.encode(), headers = headers)
# print(go.content)


test = json.dumps(arr,ensure_ascii=False)

# test = test.replace("\'", "\"");
headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/line/lastUpdated',  data=test.encode(), headers = headers)
# print(go.content)

f = open('texttest.text','w')
f.write(test)
f.close()

